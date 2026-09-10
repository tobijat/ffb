<?php

namespace App\Console\Commands\Ffb;

use App\Services\UserteamSlotBackfillService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

#[Signature('ffb:userteam-slot-drop-wide {--execute : Drop wide columns after verify (default dry-run)}')]
#[Description('Drop ffb_userteam.userteam_player_id1..11 after slot backfill verification')]
class UserteamSlotDropWideCommand extends Command
{
    public function handle(UserteamSlotBackfillService $backfill): int
    {
        $execute = (bool) $this->option('execute');

        if (! Schema::hasColumn('ffb_userteam', 'userteam_player_id1')) {
            $this->info('Wide columns already removed.');

            return self::SUCCESS;
        }

        $verify = $backfill->verify();
        if (! $verify['ok']) {
            foreach ($verify['issues'] as $issue) {
                $this->error($issue);
            }

            return self::FAILURE;
        }

        if (! $execute) {
            $this->info('Verification passed. Dry-run only; pass --execute to drop userteam_player_id1..11.');

            return self::SUCCESS;
        }

        // Legacy rows can contain 0000-00-00 dates; ALTER TABLE rebuilds under strict sql_mode fail.
        $previousMode = (string) (DB::selectOne('SELECT @@SESSION.sql_mode AS m')->m ?? '');
        DB::statement("SET SESSION sql_mode = ''");

        try {
            $parts = [];
            for ($i = 2; $i <= 12; $i++) {
                $index = 'ffb_userteam_FI_'.$i;
                if ($this->indexExists('ffb_userteam', $index)) {
                    $parts[] = 'DROP INDEX `'.$index.'`';
                }
            }
            for ($i = 1; $i <= 11; $i++) {
                $parts[] = 'DROP COLUMN `userteam_player_id'.$i.'`';
            }

            if ($parts !== []) {
                DB::statement('ALTER TABLE `ffb_userteam` '.implode(', ', $parts));
            }

            $inventory = $backfill->inventory();
            if ($inventory['duplicate_user_round_groups'] === 0
                && ! $this->indexExists('ffb_userteam', 'userteam_user_round_unique')) {
                DB::statement(
                    'ALTER TABLE `ffb_userteam` ADD UNIQUE `userteam_user_round_unique` (`userteam_user_id`, `userteam_matchround_id`)'
                );
                $this->info('Added UNIQUE (userteam_user_id, userteam_matchround_id).');
            }
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$previousMode]);
        }

        $this->info('Dropped userteam_player_id1..11.');

        return self::SUCCESS;
    }

    private function indexExists(string $table, string $index): bool
    {
        foreach (DB::select('SHOW INDEX FROM '.$table) as $row) {
            if (($row->Key_name ?? '') === $index) {
                return true;
            }
        }

        return false;
    }
}
