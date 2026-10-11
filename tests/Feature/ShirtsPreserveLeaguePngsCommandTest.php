<?php

namespace Tests\Feature;

use App\Support\TeamShirt;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShirtsPreserveLeaguePngsCommandTest extends TestCase
{
    private string $shirtsDir;

    protected function setUp(): void
    {
        parent::setUp();

        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite is required.');
        }

        foreach (['ffb_match', 'ffb_matchround', 'ffb_team', 'ffb_league'] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->string('asset_key')->default('');
            $table->tinyInteger('league_visible')->default(1);
            $table->tinyInteger('league_archive')->default(0);
        });

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->increments('matchround_id');
            $table->unsignedInteger('matchround_league_id');
            $table->string('matchround_title')->default('');
            $table->tinyInteger('matchround_status')->default(1);
        });

        Schema::create('ffb_team', function (Blueprint $table) {
            $table->increments('team_id');
            $table->string('asset_key')->default('');
            $table->string('team_name')->default('');
            $table->string('team_nationality')->default('');
            $table->tinyInteger('team_status')->default(1);
        });

        Schema::create('ffb_match', function (Blueprint $table) {
            $table->increments('match_id');
            $table->unsignedInteger('match_round');
            $table->unsignedInteger('match_hometeam_id')->default(0);
            $table->unsignedInteger('match_guestteam_id')->default(0);
        });

        $this->shirtsDir = storage_path('framework/testing/ffb-league-pngs-'.uniqid('', true).DIRECTORY_SEPARATOR.'shirts');
        File::ensureDirectoryExists($this->shirtsDir);
        config(['ffb.legacy_images_path' => dirname($this->shirtsDir)]);
    }

    protected function tearDown(): void
    {
        $root = dirname($this->shirtsDir);
        if (is_dir($root)) {
            File::deleteDirectory($root);
        }
        foreach (['ffb_match', 'ffb_matchround', 'ffb_team', 'ffb_league'] as $table) {
            Schema::dropIfExists($table);
        }

        parent::tearDown();
    }

    #[Test]
    public function dry_run_does_not_write_league_pngs_and_skips_excluded_leagues(): void
    {
        $this->seedLeaguesAndMatch();
        File::ensureDirectoryExists($this->shirtsDir.DIRECTORY_SEPARATOR.'brasilien-ua1d');
        File::put(TeamShirt::defaultStoragePath('brasilien-ua1d', 'bra', 'png'), 'png-bytes');

        $this->artisan('ffb:shirts-preserve-league-pngs')
            ->assertSuccessful()
            ->expectsOutputToContain('would_copy');

        $this->assertFileDoesNotExist(
            TeamShirt::leagueStoragePath('brasilien-ua1d', 'bra', 'em-quali-2012-abcd', 'png'),
        );
        $this->assertFileDoesNotExist(
            TeamShirt::leagueStoragePath('brasilien-ua1d', 'bra', 'wm-2026-qkb1', 'png'),
        );
    }

    #[Test]
    public function execute_copies_default_png_to_league_specific_path(): void
    {
        $this->seedLeaguesAndMatch();
        File::ensureDirectoryExists($this->shirtsDir.DIRECTORY_SEPARATOR.'brasilien-ua1d');
        File::put(TeamShirt::defaultStoragePath('brasilien-ua1d', 'bra', 'png'), 'png-bytes');

        $this->artisan('ffb:shirts-preserve-league-pngs', ['--execute' => true])
            ->assertSuccessful()
            ->expectsOutputToContain('copied');

        $target = TeamShirt::leagueStoragePath('brasilien-ua1d', 'bra', 'em-quali-2012-abcd', 'png');
        $this->assertFileExists($target);
        $this->assertSame('png-bytes', File::get($target));
        $this->assertFileDoesNotExist(
            TeamShirt::leagueStoragePath('brasilien-ua1d', 'bra', 'wm-2026-qkb1', 'png'),
        );
        $this->assertFileDoesNotExist(
            TeamShirt::leagueStoragePath('brasilien-ua1d', 'bra', 'nations-league-2026-v738', 'png'),
        );
    }

    #[Test]
    public function execute_skips_when_league_png_already_exists(): void
    {
        $this->seedLeaguesAndMatch();
        File::ensureDirectoryExists($this->shirtsDir.DIRECTORY_SEPARATOR.'brasilien-ua1d');
        File::put(TeamShirt::defaultStoragePath('brasilien-ua1d', 'bra', 'png'), 'new');
        $target = TeamShirt::leagueStoragePath('brasilien-ua1d', 'bra', 'em-quali-2012-abcd', 'png');
        File::put($target, 'existing');

        $this->artisan('ffb:shirts-preserve-league-pngs', ['--execute' => true])
            ->assertSuccessful()
            ->expectsOutputToContain('already_had_league_png');

        $this->assertSame('existing', File::get($target));
    }

    private function seedLeaguesAndMatch(): void
    {
        DB::table('ffb_league')->insert([
            [
                'league_id' => 12,
                'league_title' => 'EM Qualifikation 2012',
                'asset_key' => 'em-quali-2012-abcd',
            ],
            [
                'league_id' => 38,
                'league_title' => 'Nations League 2026',
                'asset_key' => 'nations-league-2026-v738',
            ],
            [
                'league_id' => 39,
                'league_title' => 'WM 2026',
                'asset_key' => 'wm-2026-qkb1',
            ],
        ]);

        DB::table('ffb_matchround')->insert([
            [
                'matchround_id' => 1,
                'matchround_league_id' => 12,
                'matchround_title' => 'Tag 1',
                'matchround_status' => 1,
            ],
            [
                'matchround_id' => 2,
                'matchround_league_id' => 39,
                'matchround_title' => 'Gruppe',
                'matchround_status' => 1,
            ],
        ]);

        DB::table('ffb_team')->insert([
            'team_id' => 73,
            'asset_key' => 'brasilien-ua1d',
            'team_name' => 'Brasilien',
            'team_nationality' => 'BRA',
            'team_status' => 1,
        ]);

        DB::table('ffb_match')->insert([
            [
                'match_id' => 1,
                'match_round' => 1,
                'match_hometeam_id' => 73,
                'match_guestteam_id' => 73,
            ],
            [
                'match_id' => 2,
                'match_round' => 2,
                'match_hometeam_id' => 73,
                'match_guestteam_id' => 73,
            ],
        ]);
    }
}
