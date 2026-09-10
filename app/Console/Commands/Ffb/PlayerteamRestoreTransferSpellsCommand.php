<?php

namespace App\Console\Commands\Ffb;

use App\Services\PlayerteamLeagueBackfillService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ffb:playerteam-restore-transfer-spells {--execute : Persist restores (default dry-run)}')]
#[Description('Restore transfer-spell playerteam rows wrongly merged before league backfill')]
class PlayerteamRestoreTransferSpellsCommand extends Command
{
    public function handle(PlayerteamLeagueBackfillService $backfill): int
    {
        $execute = (bool) $this->option('execute');
        $this->info($execute ? 'Restoring transfer spells...' : 'Dry-run transfer-spell restore...');

        $result = $backfill->restoreTransferSpellRows(
            $backfill->feffernitzTransferSpellRestores(),
            $execute,
        );

        foreach ($result['restored'] as $row) {
            $from = $row['from_id'] !== null ? (string) $row['from_id'] : 'none';
            $this->line(sprintf(
                '  restore #%d (league %d, %s/%s) replacing copy #%s',
                $row['restored_id'],
                $row['league_id'],
                $row['position'],
                $row['transfer'],
                $from,
            ));
        }

        foreach ($result['skipped'] as $message) {
            $this->warn('  skip: '.$message);
        }

        $this->table(
            ['Metric', 'Value'],
            [
                [$execute ? 'restored_rows' : 'would_restore_rows', count($result['restored'])],
                ['skipped', count($result['skipped'])],
            ],
        );

        if ($execute) {
            $verify = $backfill->verifyConsistency();
            if (! $verify['ok']) {
                foreach ($verify['issues'] as $issue) {
                    $this->error($issue);
                }

                return self::FAILURE;
            }
            $this->info('Verification passed.');
        }

        return self::SUCCESS;
    }
}
