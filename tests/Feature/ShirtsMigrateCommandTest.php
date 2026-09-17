<?php

namespace Tests\Feature;

use App\Support\TeamShirt;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShirtsMigrateCommandTest extends TestCase
{
    private string $shirtsDir;

    protected function setUp(): void
    {
        parent::setUp();

        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite is required.');
        }

        Schema::dropIfExists('ffb_team');
        Schema::create('ffb_team', function (Blueprint $table) {
            $table->integer('team_id')->primary();
            $table->string('team_name')->default('');
            $table->string('team_nationality')->default('');
            $table->string('team_foreign_id')->default('');
            $table->integer('team_num_players')->default(0);
            $table->tinyInteger('team_status')->default(1);
        });

        $this->shirtsDir = storage_path('framework/testing/ffb-shirts-'.uniqid('', true).DIRECTORY_SEPARATOR.'shirts');
        File::ensureDirectoryExists($this->shirtsDir);
        config(['ffb.legacy_images_path' => dirname($this->shirtsDir)]);
    }

    protected function tearDown(): void
    {
        $root = dirname($this->shirtsDir);
        if (is_dir($root)) {
            File::deleteDirectory($root);
        }
        Schema::dropIfExists('ffb_team');

        parent::tearDown();
    }

    #[Test]
    public function dry_run_does_not_copy_files(): void
    {
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.'shirt_AUT.png', 'png');
        DB::table('ffb_team')->insert([
            'team_id' => 3,
            'team_name' => 'Rapid',
            'team_nationality' => 'aut',
            'team_foreign_id' => '',
            'team_num_players' => 0,
            'team_status' => 1,
        ]);

        $this->artisan('ffb:shirts-migrate')
            ->assertSuccessful()
            ->expectsOutputToContain('would_copy');

        $this->assertFileDoesNotExist(TeamShirt::defaultStoragePath(3, 'aut'));
        $this->assertFileExists($this->shirtsDir.DIRECTORY_SEPARATOR.'shirt_AUT.png');
    }

    #[Test]
    public function execute_copies_legacy_shirt_into_team_folder(): void
    {
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.'shirt_AUT.png', 'png-bytes');
        File::put($this->shirtsDir.DIRECTORY_SEPARATOR.'shirt_BLANK.png', 'blank');
        DB::table('ffb_team')->insert([
            [
                'team_id' => 3,
                'team_name' => 'Rapid',
                'team_nationality' => 'aut',
                'team_foreign_id' => '',
                'team_num_players' => 0,
                'team_status' => 1,
            ],
            [
                'team_id' => 4,
                'team_name' => 'Austria',
                'team_nationality' => 'aut',
                'team_foreign_id' => '',
                'team_num_players' => 0,
                'team_status' => 1,
            ],
        ]);

        $this->artisan('ffb:shirts-migrate', ['--execute' => true])->assertSuccessful();

        $this->assertFileExists(TeamShirt::defaultStoragePath(3, 'aut'));
        $this->assertFileExists(TeamShirt::defaultStoragePath(4, 'aut'));
        $this->assertSame('png-bytes', File::get(TeamShirt::defaultStoragePath(3, 'aut')));
        $this->assertFileExists($this->shirtsDir.DIRECTORY_SEPARATOR.'shirt_BLANK.png');
        $this->assertFileExists($this->shirtsDir.DIRECTORY_SEPARATOR.'shirt_AUT.png');
    }
}
