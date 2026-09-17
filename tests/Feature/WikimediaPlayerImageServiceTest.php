<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\Player;
use App\Models\Playerteam;
use App\Models\Team;
use App\Services\WikimediaPlayerImageService;
use App\Support\PlayerPicture;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WikimediaPlayerImageServiceTest extends TestCase
{
    private string $imagesRoot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imagesRoot = sys_get_temp_dir().DIRECTORY_SEPARATOR.'ffb-wm-'.uniqid('', true);
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
    public function commons_filename_is_extracted_from_filepath_urls(): void
    {
        $service = new WikimediaPlayerImageService;

        $this->assertSame(
            'Alex_Grimaldo_Argentina_v_Spain_19_July_2026-314.jpg',
            $service->commonsFilenameFromUrl(
                'https://commons.wikimedia.org/wiki/Special:FilePath/Alex_Grimaldo_Argentina_v_Spain_19_July_2026-314.jpg'
            ),
        );
        $this->assertSame(
            'Alex_Grimaldo_Argentina_v_Spain_19_July_2026-314.jpg',
            $service->commonsFilenameFromUrl(
                'https://commons.wikimedia.org/wiki/File:Alex_Grimaldo_Argentina_v_Spain_19_July_2026-314.jpg'
            ),
        );
    }

    #[Test]
    public function resolve_images_queries_wikidata_and_commons_api(): void
    {
        Http::fake([
            'query.wikidata.org/*' => Http::response([
                'results' => [
                    'bindings' => [
                        [
                            'name' => ['type' => 'literal', 'value' => 'Álex Grimaldo', 'xml:lang' => 'en'],
                            'image' => [
                                'type' => 'uri',
                                'value' => 'http://commons.wikimedia.org/wiki/Special:FilePath/Alex_Grimaldo_Argentina_v_Spain_19_July_2026-314.jpg',
                            ],
                        ],
                    ],
                ],
            ], 200),
            'commons.wikimedia.org/w/api.php*' => Http::response([
                'query' => [
                    'pages' => [
                        [
                            'title' => 'File:Alex_Grimaldo_Argentina_v_Spain_19_July_2026-314.jpg',
                            'imageinfo' => [
                                [
                                    'thumburl' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Alex_Grimaldo.jpg/200px-Alex_Grimaldo.jpg',
                                    'url' => 'https://upload.wikimedia.org/wikipedia/commons/a/a1/Alex_Grimaldo.jpg',
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $resolved = (new WikimediaPlayerImageService)->resolveImagesByPlayerNames(['Álex Grimaldo', 'Unknown Player']);

        $this->assertArrayHasKey('Álex Grimaldo', $resolved);
        $this->assertSame(
            'Alex_Grimaldo_Argentina_v_Spain_19_July_2026-314.jpg',
            $resolved['Álex Grimaldo']['commons_file'],
        );
        $this->assertStringContainsString('200px', $resolved['Álex Grimaldo']['thumbnail_url']);
        $this->assertArrayNotHasKey('Unknown Player', $resolved);

        Http::assertSent(function ($request): bool {
            return $request->hasHeader('User-Agent', 'SoccerSportsfan')
                && (
                    str_contains($request->url(), 'query.wikidata.org')
                    || str_contains($request->url(), 'commons.wikimedia.org')
                );
        });

        Http::assertSent(function ($request): bool {
            if (! str_contains($request->url(), 'query.wikidata.org')) {
                return false;
            }
            $query = (string) ($request->data()['query'] ?? '');

            return str_contains($query, '"Álex Grimaldo"@en')
                && str_contains($query, 'wdt:P106 wd:Q937857')
                && str_contains($query, 'wdt:P18 ?image');
        });
    }

    #[Test]
    public function resolve_images_keeps_first_wikidata_image_only(): void
    {
        Http::fake([
            'query.wikidata.org/*' => Http::response([
                'results' => [
                    'bindings' => [
                        [
                            'name' => ['type' => 'literal', 'value' => 'Álex Grimaldo'],
                            'image' => [
                                'type' => 'uri',
                                'value' => 'http://commons.wikimedia.org/wiki/Special:FilePath/First_Image.jpg',
                            ],
                        ],
                        [
                            'name' => ['type' => 'literal', 'value' => 'Álex Grimaldo'],
                            'image' => [
                                'type' => 'uri',
                                'value' => 'http://commons.wikimedia.org/wiki/Special:FilePath/Second_Image.jpg',
                            ],
                        ],
                    ],
                ],
            ], 200),
            'commons.wikimedia.org/w/api.php*' => Http::response([
                'query' => [
                    'pages' => [
                        [
                            'title' => 'File:First_Image.jpg',
                            'imageinfo' => [
                                ['thumburl' => 'https://upload.wikimedia.org/first-200.jpg'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $resolved = (new WikimediaPlayerImageService)->resolveImagesByPlayerNames(['Álex Grimaldo']);

        $this->assertSame('First_Image.jpg', $resolved['Álex Grimaldo']['commons_file']);
    }

    #[Test]
    public function assign_images_prefers_pre_resolved_commons_file(): void
    {
        Http::fake([
            'commons.wikimedia.org/w/api.php*' => Http::response([
                'query' => [
                    'pages' => [
                        [
                            'title' => 'File:Pre_Resolved.jpg',
                            'imageinfo' => [
                                ['thumburl' => 'https://upload.wikimedia.org/pre.jpg'],
                            ],
                        ],
                    ],
                ],
            ], 200),
            'upload.wikimedia.org/*' => Http::response($this->tinyJpegBytes(), 200, [
                'Content-Type' => 'image/jpeg',
            ]),
        ]);

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        $team = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Spain',
            'team_nationality' => 'esp',
            'team_num_players' => 0,
            'team_status' => 1,
        ]);
        $player = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Álex',
            'player_lname' => 'Grimaldo',
            'player_nationality' => 'ESP',
            'player_status' => 1,
            'player_status_description' => '',
            'player_commons_image' => '',
        ]);
        Playerteam::query()->create([
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_team_id' => (int) $team->team_id,
            'playerteam_league_id' => (int) $league->league_id,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_price' => 5,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);

        $result = (new WikimediaPlayerImageService)->assignImagesToNewSquadPlayers([
            [
                'player_id' => (int) $player->player_id,
                'name' => 'Álex Grimaldo',
                'commons_file' => 'Pre_Resolved.jpg',
            ],
        ], (int) $team->team_id, (int) $league->league_id);

        $this->assertSame(1, $result['resolved']);
        $this->assertSame(1, $result['stored']);
        $this->assertDatabaseHas('ffb_player', [
            'player_id' => (int) $player->player_id,
            'player_commons_image' => 'Pre_Resolved.jpg',
        ]);

        Http::assertNotSent(fn ($request): bool => str_contains($request->url(), 'query.wikidata.org'));
    }

    #[Test]
    public function assign_images_stores_commons_handle_and_squad_portrait(): void
    {
        Http::fake([
            'query.wikidata.org/*' => Http::response([
                'results' => [
                    'bindings' => [
                        [
                            'name' => ['type' => 'literal', 'value' => 'Álex Grimaldo'],
                            'image' => [
                                'type' => 'uri',
                                'value' => 'http://commons.wikimedia.org/wiki/Special:FilePath/Alex_Grimaldo_test.jpg',
                            ],
                        ],
                    ],
                ],
            ], 200),
            'commons.wikimedia.org/w/api.php*' => Http::response([
                'query' => [
                    'pages' => [
                        [
                            'title' => 'File:Alex_Grimaldo_test.jpg',
                            'imageinfo' => [
                                [
                                    'thumburl' => 'https://upload.wikimedia.org/thumb.jpg',
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
            'upload.wikimedia.org/*' => Http::response($this->tinyJpegBytes(), 200, [
                'Content-Type' => 'image/jpeg',
            ]),
        ]);

        $league = League::query()->create([
            'league_title' => 'WM 2026',
            'league_visible' => 1,
            'league_archive' => 0,
            'league_symbol' => '',
        ]);
        $team = Team::query()->create([
            'team_foreign_id' => '',
            'team_name' => 'Spain',
            'team_nationality' => 'esp',
            'team_num_players' => 0,
            'team_status' => 1,
        ]);
        $player = Player::query()->create([
            'player_foreign_id' => '',
            'player_fname' => 'Álex',
            'player_lname' => 'Grimaldo',
            'player_nationality' => 'ESP',
            'player_status' => 1,
            'player_status_description' => '',
            'player_commons_image' => '',
        ]);
        Playerteam::query()->create([
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_team_id' => (int) $team->team_id,
            'playerteam_league_id' => (int) $league->league_id,
            'playerteam_player_picture' => '',
            'playerteam_status' => 1,
            'playerteam_player_price' => 5,
            'playerteam_player_position' => 'd',
            'playerteam_date_transfer' => '2008-01-01 00:00:00',
        ]);

        $result = (new WikimediaPlayerImageService)->assignImagesToNewSquadPlayers([
            [
                'player_id' => (int) $player->player_id,
                'name' => 'Álex Grimaldo',
            ],
        ], (int) $team->team_id, (int) $league->league_id);

        $this->assertSame(1, $result['resolved']);
        $this->assertSame(1, $result['stored']);
        $this->assertDatabaseHas('ffb_player', [
            'player_id' => (int) $player->player_id,
            'player_commons_image' => 'Alex_Grimaldo_test.jpg',
        ]);
        $this->assertDatabaseHas('ffb_playerteam', [
            'playerteam_player_id' => (int) $player->player_id,
            'playerteam_player_picture' => $team->team_id.'-'.$player->player_id.'.jpg',
        ]);
        $this->assertFileExists(PlayerPicture::storagePath((int) $team->team_id, (int) $player->player_id));
    }

    #[Test]
    public function display_name_collapses_legacy_single_names(): void
    {
        $service = new WikimediaPlayerImageService;

        $this->assertSame('Pelé', $service->displayName('Pelé', 'Pelé'));
        $this->assertSame('Álex Grimaldo', $service->displayName('Álex', 'Grimaldo'));
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
