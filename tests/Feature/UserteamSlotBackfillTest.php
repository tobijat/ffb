<?php

namespace Tests\Feature;

use App\Models\Userteam;
use App\Services\UserteamSlotBackfillService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserteamSlotBackfillTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite is required for in-memory legacy schema fixtures.');
        }

        $this->createWideSchema();
    }

    #[Test]
    public function dry_run_counts_nonzero_slots_without_writing(): void
    {
        $this->seedWideUserteam(1, [10, 0, 20, 0, 0, 0, 0, 0, 0, 0, 30]);

        $result = app(UserteamSlotBackfillService::class)->backfill(false);

        $this->assertSame(1, $result['processed_userteams']);
        $this->assertSame(3, $result['slots_written']);
        $this->assertSame(8, $result['skipped_empty']);
        $this->assertSame(0, (int) DB::table('ffb_userteam_slot')->count());
    }

    #[Test]
    public function execute_copies_nonzero_wide_columns_and_verify_passes(): void
    {
        $this->seedWideUserteam(1, [11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21]);
        $this->seedWideUserteam(2, [31, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]);

        $service = app(UserteamSlotBackfillService::class);
        $result = $service->backfill(true);

        $this->assertSame(2, $result['processed_userteams']);
        $this->assertSame(12, $result['slots_written']);
        $this->assertSame(10, $result['skipped_empty']);

        $verify = $service->verify();
        $this->assertTrue($verify['ok'], implode('; ', $verify['issues']));

        $userteam = Userteam::query()->findOrFail(1);
        $this->assertSame(range(11, 21), $userteam->playerteamIdsInSlotOrder());
        $this->assertSame([31], Userteam::query()->findOrFail(2)->playerteamIdsInSlotOrder());
    }

    #[Test]
    public function execute_skips_userteams_that_already_have_slots(): void
    {
        $this->seedWideUserteam(1, [5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]);
        DB::table('ffb_userteam_slot')->insert([
            'userteam_slot_userteam_id' => 1,
            'userteam_slot_slot' => 1,
            'userteam_slot_playerteam_id' => 5,
        ]);

        $result = app(UserteamSlotBackfillService::class)->backfill(true);

        $this->assertSame(1, $result['already_had_slots']);
        $this->assertSame(0, $result['slots_written']);
        $this->assertSame(1, (int) DB::table('ffb_userteam_slot')->count());
    }

    /**
     * @param  list<int>  $slotIds
     */
    private function seedWideUserteam(int $id, array $slotIds): void
    {
        $row = [
            'userteam_id' => $id,
            'userteam_user_id' => $id,
            'userteam_matchround_id' => 1,
        ];
        foreach ($slotIds as $index => $playerteamId) {
            $row['userteam_player_id'.($index + 1)] = $playerteamId;
        }
        DB::table('ffb_userteam')->insert($row);
    }

    private function createWideSchema(): void
    {
        Schema::dropIfExists('ffb_userteam_slot');
        Schema::dropIfExists('ffb_userteam');
        Schema::dropIfExists('ffb_matchround');

        Schema::create('ffb_matchround', function (Blueprint $table) {
            $table->integer('matchround_id')->primary();
            $table->integer('matchround_league_id')->default(1);
        });
        DB::table('ffb_matchround')->insert(['matchround_id' => 1, 'matchround_league_id' => 1]);

        Schema::create('ffb_userteam', function (Blueprint $table) {
            $table->integer('userteam_id')->primary();
            $table->integer('userteam_user_id')->default(0);
            $table->integer('userteam_matchround_id')->nullable();
            for ($i = 1; $i <= 11; $i++) {
                $table->integer('userteam_player_id'.$i)->default(0);
            }
        });

        Schema::create('ffb_userteam_slot', function (Blueprint $table) {
            $table->increments('userteam_slot_id');
            $table->unsignedInteger('userteam_slot_userteam_id');
            $table->unsignedTinyInteger('userteam_slot_slot');
            $table->unsignedInteger('userteam_slot_playerteam_id');
            $table->unique(['userteam_slot_userteam_id', 'userteam_slot_slot']);
        });
    }
}
