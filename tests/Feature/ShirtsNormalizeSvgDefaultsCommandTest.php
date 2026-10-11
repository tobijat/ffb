<?php

namespace Tests\Feature;

use App\Support\TeamShirt;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShirtsNormalizeSvgDefaultsCommandTest extends TestCase
{
    private string $shirtsDir;

    protected function setUp(): void
    {
        parent::setUp();

        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite is required.');
        }

        foreach (['ffb_team', 'ffb_league'] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::create('ffb_league', function (Blueprint $table) {
            $table->increments('league_id');
            $table->string('league_title')->default('');
            $table->string('asset_key')->default('');
        });

        Schema::create('ffb_team', function (Blueprint $table) {
            $table->increments('team_id');
            $table->string('asset_key')->default('');
            $table->string('team_name')->default('');
            $table->string('team_nationality')->default('');
            $table->tinyInteger('team_status')->default(1);
        });

        $this->shirtsDir = storage_path('framework/testing/ffb-svg-normalize-'.uniqid('', true).DIRECTORY_SEPARATOR.'shirts');
        File::ensureDirectoryExists($this->shirtsDir);
        config(['ffb.legacy_images_path' => dirname($this->shirtsDir)]);

        DB::table('ffb_league')->insert([
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
    }

    protected function tearDown(): void
    {
        $root = dirname($this->shirtsDir);
        if (is_dir($root)) {
            File::deleteDirectory($root);
        }
        foreach (['ffb_team', 'ffb_league'] as $table) {
            Schema::dropIfExists($table);
        }

        parent::tearDown();
    }

    #[Test]
    public function dry_run_does_not_write_or_delete(): void
    {
        $this->seedTeam(1, 'england-tzbc', 'England', 'ENG');
        $dir = $this->shirtsDir.DIRECTORY_SEPARATOR.'england-tzbc';
        File::ensureDirectoryExists($dir);
        File::put(TeamShirt::leagueStoragePath('england-tzbc', 'eng', 'wm-2026-qkb1', 'svg'), 'wm-svg');

        $this->artisan('ffb:shirts-normalize-svg-defaults')
            ->assertSuccessful()
            ->expectsOutputToContain('would_promote_to_default');

        $this->assertFileDoesNotExist(TeamShirt::defaultStoragePath('england-tzbc', 'eng', 'svg'));
        $this->assertFileExists(TeamShirt::leagueStoragePath('england-tzbc', 'eng', 'wm-2026-qkb1', 'svg'));
    }

    #[Test]
    public function execute_promotes_preferred_league_svg_and_removes_league_svgs(): void
    {
        $this->seedTeam(1, 'england-tzbc', 'England', 'ENG');
        File::ensureDirectoryExists($this->shirtsDir.DIRECTORY_SEPARATOR.'england-tzbc');
        File::put(
            TeamShirt::leagueStoragePath('england-tzbc', 'eng', 'nations-league-2026-v738', 'svg'),
            'nl-svg',
        );
        File::put(
            TeamShirt::leagueStoragePath('england-tzbc', 'eng', 'wm-2026-qkb1', 'svg'),
            'wm-svg',
        );

        $this->artisan('ffb:shirts-normalize-svg-defaults', ['--execute' => true])
            ->assertSuccessful()
            ->expectsOutputToContain('promoted_to_default');

        $default = TeamShirt::defaultStoragePath('england-tzbc', 'eng', 'svg');
        $this->assertFileExists($default);
        $this->assertSame('wm-svg', File::get($default));
        $this->assertFileDoesNotExist(
            TeamShirt::leagueStoragePath('england-tzbc', 'eng', 'wm-2026-qkb1', 'svg'),
        );
        $this->assertFileDoesNotExist(
            TeamShirt::leagueStoragePath('england-tzbc', 'eng', 'nations-league-2026-v738', 'svg'),
        );
    }

    #[Test]
    public function execute_keeps_existing_default_and_only_removes_league_svgs(): void
    {
        $this->seedTeam(2, 'brasilien-ua1d', 'Brasilien', 'BRA');
        File::ensureDirectoryExists($this->shirtsDir.DIRECTORY_SEPARATOR.'brasilien-ua1d');
        File::put(TeamShirt::defaultStoragePath('brasilien-ua1d', 'bra', 'svg'), 'default-svg');
        File::put(
            TeamShirt::leagueStoragePath('brasilien-ua1d', 'bra', 'wm-2026-qkb1', 'svg'),
            'league-svg',
        );

        $this->artisan('ffb:shirts-normalize-svg-defaults', ['--execute' => true])
            ->assertSuccessful()
            ->expectsOutputToContain('already_had_default_svg');

        $this->assertSame('default-svg', File::get(TeamShirt::defaultStoragePath('brasilien-ua1d', 'bra', 'svg')));
        $this->assertFileDoesNotExist(
            TeamShirt::leagueStoragePath('brasilien-ua1d', 'bra', 'wm-2026-qkb1', 'svg'),
        );
    }

    private function seedTeam(int $id, string $key, string $name, string $nat): void
    {
        DB::table('ffb_team')->insert([
            'team_id' => $id,
            'asset_key' => $key,
            'team_name' => $name,
            'team_nationality' => $nat,
            'team_status' => 1,
        ]);
    }
}
