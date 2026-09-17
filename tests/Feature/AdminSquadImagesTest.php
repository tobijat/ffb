<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\Player;
use App\Models\Playerteam;
use App\Models\Team;
use App\Services\AdminCenterService;
use App\Services\AdminPlayerService;
use App\Services\AdminSquadService;
use App\Services\WikimediaPlayerImageService;
use App\Support\PlayerPicture;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminSquadImagesTest extends TestCase
{
    private string $imagesRoot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imagesRoot = sys_get_temp_dir().DIRECTORY_SEPARATOR.'ffb-squad-img-'.uniqid('', true);
        mkdir($this->imagesRoot.DIRECTORY_SEPARATOR.'players', 0775, true);
        config(['ffb.legacy_images_path' => $this->imagesRoot]);
        $this->createSchema();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');
        $this->removeDirectory($this->imagesRoot);
        parent::tearDown();
    }

    #[Test]
    public function check_resolves_images_without_storing_and_honors_lookup_name(): void
    {
        Http::swap(new Factory);
        Http::fake([
            'query.wikidata.org/*' => Http::response([
                'results' => [
                    'bindings' => [
                        [
                            'name' => ['type' => 'literal', 'value' => 'Matej Kovar'],
                            'image' => [
                                'type' => 'uri',
                                'value' => 'http://commons.wikimedia.org/wiki/Special:FilePath/Matej_Kovar.jpg',
                            ],
                        ],
                    ],
                ],
            ], 200),
            'commons.wikimedia.org/w/api.php*' => Http::response([
                'query' => [
                    'pages' => [
                        [
                            'title' => 'File:Matej_Kovar.jpg',
                            'imageinfo' => [
                                ['thumburl' => 'https://upload.wikimedia.org/matej.jpg'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        [$teamId, $leagueId] = $this->seedTeamAndLeague('cze');

        $withImage = $this->createActivePlayer($teamId, $leagueId, 'Tomáš', 'Holeš', 'd', true);
        $needsImage = $this->createActivePlayer($teamId, $leagueId, 'Matěj', 'Kovář', 'g', false);
        $unknown = $this->createActivePlayer($teamId, $leagueId, 'Unknown', 'Player', 'm', false);
        $inactive = $this->createActivePlayer($teamId, $leagueId, 'Patrik', 'Schick', 's', false, status: 0);

        $result = $this->service()->checkWikimediaImagesForSquadPlayers($teamId, $leagueId, [
            (int) $needsImage->player_id => 'Matej Kovar',
        ]);

        $this->assertTrue($result['ok']);
        $this->assertTrue($result['images']['checked']);

        $byId = [];
        foreach ($result['images']['players'] as $row) {
            $byId[(int) $row['player_id']] = $row;
        }

        $this->assertSame('vorhanden', $byId[(int) $withImage->player_id]['status']);
        $this->assertSame('gefunden', $byId[(int) $needsImage->player_id]['status']);
        $this->assertSame('Matej_Kovar.jpg', $byId[(int) $needsImage->player_id]['commons_file']);
        $this->assertSame('nicht_gefunden', $byId[(int) $unknown->player_id]['status']);
        $this->assertSame('nicht_gefunden', $byId[(int) $inactive->player_id]['status']);

        $this->assertDatabaseHas('ffb_player', [
            'player_id' => (int) $needsImage->player_id,
            'player_commons_image' => '',
        ]);
        $this->assertFileDoesNotExist(PlayerPicture::storagePath($teamId, (int) $needsImage->player_id));
    }

    #[Test]
    public function apply_stores_found_commons_images(): void
    {
        Http::swap(new Factory);
        Http::fake([
            'upload.wikimedia.org/*' => Http::response($this->tinyJpegBytes(), 200, [
                'Content-Type' => 'image/jpeg',
            ]),
        ]);

        [$teamId, $leagueId] = $this->seedTeamAndLeague('cze');
        $needsImage = $this->createActivePlayer($teamId, $leagueId, 'Matěj', 'Kovář', 'g', false);

        $result = $this->service()->applyWikimediaImagesForSquadPlayers($teamId, $leagueId, [
            [
                'player_id' => (int) $needsImage->player_id,
                'commons_file' => 'Matej_Kovar.jpg',
                'thumbnail_url' => 'https://upload.wikimedia.org/matej.jpg',
            ],
        ]);

        $this->assertTrue($result['ok']);
        $this->assertStringContainsString('1 Bild übernommen', $result['message']);
        $this->assertDatabaseHas('ffb_player', [
            'player_id' => (int) $needsImage->player_id,
            'player_commons_image' => 'Matej_Kovar.jpg',
        ]);
        $this->assertFileExists(PlayerPicture::storagePath($teamId, (int) $needsImage->player_id));
        Http::assertNotSent(fn ($request): bool => str_contains($request->url(), 'commons.wikimedia.org'));
        Http::assertNotSent(fn ($request): bool => str_contains($request->url(), 'query.wikidata.org'));
    }

    #[Test]
    public function apply_batches_commons_thumbnail_lookup_for_multiple_players(): void
    {
        Http::swap(new Factory);
        Http::fake([
            'commons.wikimedia.org/w/api.php*' => Http::response([
                'query' => [
                    'pages' => [
                        [
                            'title' => 'File:Matej_Kovar.jpg',
                            'imageinfo' => [
                                ['thumburl' => 'https://upload.wikimedia.org/matej.jpg'],
                            ],
                        ],
                        [
                            'title' => 'File:Tomas_Holes.jpg',
                            'imageinfo' => [
                                ['thumburl' => 'https://upload.wikimedia.org/tomas.jpg'],
                            ],
                        ],
                        [
                            'title' => 'File:Patrik_Schick.jpg',
                            'imageinfo' => [
                                ['thumburl' => 'https://upload.wikimedia.org/schick.jpg'],
                            ],
                        ],
                    ],
                ],
            ], 200),
            'upload.wikimedia.org/*' => Http::response($this->tinyJpegBytes(), 200, [
                'Content-Type' => 'image/jpeg',
            ]),
        ]);

        [$teamId, $leagueId] = $this->seedTeamAndLeague('cze');
        $a = $this->createActivePlayer($teamId, $leagueId, 'Matěj', 'Kovář', 'g', false);
        $b = $this->createActivePlayer($teamId, $leagueId, 'Tomáš', 'Holeš', 'd', false);
        $c = $this->createActivePlayer($teamId, $leagueId, 'Patrik', 'Schick', 's', false);

        $result = $this->service()->applyWikimediaImagesForSquadPlayers($teamId, $leagueId, [
            ['player_id' => (int) $a->player_id, 'commons_file' => 'Matej_Kovar.jpg'],
            ['player_id' => (int) $b->player_id, 'commons_file' => 'Tomas_Holes.jpg'],
            ['player_id' => (int) $c->player_id, 'commons_file' => 'Patrik_Schick.jpg'],
        ]);

        $this->assertTrue($result['ok']);
        $this->assertStringContainsString('3 Bilder übernommen', $result['message']);

        $commonsCalls = 0;
        Http::assertSent(function ($request) use (&$commonsCalls): bool {
            if (! str_contains($request->url(), 'commons.wikimedia.org/w/api.php')) {
                return false;
            }
            $commonsCalls++;
            $titles = (string) ($request->data()['titles'] ?? '');

            return str_contains($titles, 'File:Matej_Kovar.jpg')
                && str_contains($titles, 'File:Tomas_Holes.jpg')
                && str_contains($titles, 'File:Patrik_Schick.jpg')
                && str_contains($titles, '|');
        });
        $this->assertSame(1, $commonsCalls);
    }

    private function service(): AdminSquadService
    {
        $adminCenter = Mockery::mock(AdminCenterService::class);
        $players = new AdminPlayerService($adminCenter);

        return new AdminSquadService($adminCenter, $players, new WikimediaPlayerImageService);
    }

    private function createActivePlayer(
        int $teamId,
        int $leagueId,
        string $fname,
        string $lname,
        string $position,
        bool $withPicture,
        int $status = 1,
    ): Player {
        $player = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => $fname,
            'player_lname' => $lname,
            'player_nationality' => 'CZE',
            'player_status' => 1,
            'player_status_description' => '',
            'player_commons_image' => '',
        ]);

        $picture = '';
        if ($withPicture) {
            $path = PlayerPicture::storagePath($teamId, (int) $player->player_id);
            mkdir(dirname($path), 0775, true);
            file_put_contents($path, $this->tinyJpegBytes());
            $picture = $teamId.'-'.$player->player_id.'.jpg';
        }

        Playerteam::query()->create([
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_team_id' => $teamId,
            'playerteam_league_id' => $leagueId,
            'playerteam_player_picture' => $picture,
            'playerteam_status' => $status,
            'playerteam_player_price' => 5,
            'playerteam_player_position' => $position,
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);

        return $player;
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function seedTeamAndLeague(string $nationality): array
    {
        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);

        $team = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Czech Republic',
            'team_nationality' => $nationality,
            'team_num_players' => 0,
            'team_status' => 1,
        ]);

        return [(int) $team->team_id, (int) $league->league_id];
    }

    private function tinyJpegBytes(): string
    {
        $image = imagecreatetruecolor(2, 2);
        ob_start();
        imagejpeg($image, null, 90);
        imagedestroy($image);

        return (string) ob_get_clean();
    }

    private function createSchema(): void
    {
        Schema::dropIfExists('ffb_playerteam');
        Schema::dropIfExists('ffb_player');
        Schema::dropIfExists('ffb_team');
        Schema::dropIfExists('ffb_league');

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->tinyInteger('league_visible')->default(1);
            $table->tinyInteger('league_archive')->default(0);
            $table->string('league_symbol')->default('');
        });

        Schema::create('ffb_team', function (Blueprint $table) {
            $table->increments('team_id');
            $table->string('team_foreign_id')->default('');
            $table->string('team_name')->default('');
            $table->string('team_nationality')->default('');
            $table->integer('team_num_players')->default(0);
            $table->tinyInteger('team_status')->default(1);
        });

        Schema::create('ffb_player', function (Blueprint $table) {
            $table->increments('player_id');
            $table->string('player_foreign_id')->default('');
            $table->string('player_fname')->default('');
            $table->string('player_lname')->default('');
            $table->string('player_nationality')->default('');
            $table->tinyInteger('player_status')->default(1);
            $table->string('player_status_description')->default('');
            $table->string('player_commons_image')->default('');
        });

        Schema::create('ffb_playerteam', function (Blueprint $table) {
            $table->increments('playerteam_id');
            $table->unsignedInteger('playerteam_player_id');
            $table->unsignedInteger('playerteam_team_id');
            $table->unsignedInteger('playerteam_league_id');
            $table->string('playerteam_player_picture')->default('');
            $table->tinyInteger('playerteam_status')->default(1);
            $table->integer('playerteam_player_price')->default(5);
            $table->string('playerteam_player_position', 1)->default('d');
            $table->string('playerteam_date_transfer')->nullable();
        });
    }

    private function removeDirectory(string $path): void
    {
        if (! is_dir($path)) {
            return;
        }

        $items = scandir($path);
        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $full = $path.DIRECTORY_SEPARATOR.$item;
            if (is_dir($full)) {
                $this->removeDirectory($full);
            } else {
                @unlink($full);
            }
        }

        @rmdir($path);
    }
}
