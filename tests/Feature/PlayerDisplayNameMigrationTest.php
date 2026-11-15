<?php

namespace Tests\Feature;

use App\Models\Player;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PlayerDisplayNameMigrationTest extends TestCase
{
    #[Test]
    public function migration_adds_column_and_backfills_defaults(): void
    {
        Schema::dropIfExists('ffb_player');
        Schema::create('ffb_player', function (Blueprint $table) {
            $table->increments('player_id');
            $table->string('asset_key')->default('');
            $table->string('player_foreign_id')->default('');
            $table->string('player_uefa_id')->default('');
            $table->string('player_fifa_id')->default('');
            $table->string('player_fname')->default('');
            $table->string('player_lname')->default('');
            $table->string('player_nationality')->default('');
            $table->tinyInteger('player_status')->default(1);
            $table->string('player_status_description')->default('');
            $table->string('player_commons_image')->default('');
        });

        Player::query()->insert([
            [
                'player_id' => 1,
                'asset_key' => 'a-1',
                'player_fname' => 'Ronaldo',
                'player_lname' => 'Ronaldo',
                'player_nationality' => 'BRA',
            ],
            [
                'player_id' => 2,
                'asset_key' => 'b-2',
                'player_fname' => 'Rubin Rafael',
                'player_lname' => 'Okotie',
                'player_nationality' => 'AUT',
            ],
            [
                'player_id' => 3,
                'asset_key' => 'c-3',
                'player_fname' => 'Luka',
                'player_lname' => 'Modric',
                'player_nationality' => 'CRO',
            ],
        ]);

        $this->assertFalse(Schema::hasColumn('ffb_player', 'player_display_name'));

        Artisan::call('migrate', [
            '--path' => 'database/migrations/2026_11_15_120000_add_player_display_name_to_ffb_player.php',
            '--force' => true,
        ]);

        $this->assertTrue(Schema::hasColumn('ffb_player', 'player_display_name'));
        $this->assertSame('Ronaldo', (string) Player::query()->find(1)->player_display_name);
        $this->assertSame('Rubin Rafael Okotie', (string) Player::query()->find(2)->player_display_name);
        $this->assertSame('Luka Modric', (string) Player::query()->find(3)->player_display_name);
    }
}
