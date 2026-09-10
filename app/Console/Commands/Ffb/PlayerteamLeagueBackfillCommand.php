<?php

namespace App\Console\Commands\Ffb;

use App\Services\PlayerteamLeagueBackfillService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('ffb:playerteam-league-backfill {--execute : Persist backfill/remap (default dry-run)} {--finalize : After execute, apply NOT NULL + unique indexes}')]
#[Description('Phase 2: add league_id, duplicate rows per league, remap FKs')]
class PlayerteamLeagueBackfillCommand extends Command
{
    public function handle(PlayerteamLeagueBackfillService $backfill): int
    {
        $execute = (bool) $this->option('execute');
        $finalize = (bool) $this->option('finalize');

        $this->info($execute ? 'Executing backfill...' : 'Dry-run backfill (pass --execute to apply)...');

        if ($execute) {
            $backfill->ensureLeagueColumn();
            $this->info('Ensured playerteam_league_id column exists.');
        }

        $result = $backfill->backfill($execute);
        $this->table(
            ['Metric', 'Value'],
            [
                ['processed', $result['processed']],
                ['copies_created', $result['copies_created']],
                ['remapped_fk_rows', $result['remapped_fk_rows']],
                ['fallback_assigned', $result['fallback_assigned']],
            ],
        );

        $dir = storage_path('app/playerteam-league');
        File::ensureDirectoryExists($dir);
        $path = $dir.'/backfill-'.now()->format('Ymd-His').($execute ? '-executed' : '-dry').'.json';
        File::put($path, json_encode($result, JSON_PRETTY_PRINT)."\n");
        $this->info('Mapping written to: '.$path);

        if ($execute && $finalize) {
            $this->info('Finalizing constraints...');
            $backfill->finalizeConstraints();
            $verify = $backfill->verifyConsistency();
            if (! $verify['ok']) {
                foreach ($verify['issues'] as $issue) {
                    $this->error($issue);
                }

                return self::FAILURE;
            }
            $this->info('Verification passed.');
        } elseif ($execute) {
            $verify = $backfill->verifyConsistency();
            if (! $verify['ok']) {
                $this->warn('Post-backfill issues (run with --finalize after fixing):');
                foreach ($verify['issues'] as $issue) {
                    $this->warn('- '.$issue);
                }
            } else {
                $this->info('Verification passed (nullable column still allowed until --finalize).');
            }
        }

        return self::SUCCESS;
    }
}
