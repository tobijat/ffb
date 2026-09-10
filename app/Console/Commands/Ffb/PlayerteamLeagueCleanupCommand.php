<?php

namespace App\Console\Commands\Ffb;

use App\Services\PlayerteamLeagueBackfillService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ffb:playerteam-league-cleanup {--execute : Persist deletes/merges (default dry-run)}')]
#[Description('Phase 1: delete unreferenced playerteams and merge duplicate (player, team) pairs')]
class PlayerteamLeagueCleanupCommand extends Command
{
    public function handle(PlayerteamLeagueBackfillService $backfill): int
    {
        $execute = (bool) $this->option('execute');
        $this->info($execute ? 'Executing cleanup...' : 'Dry-run cleanup (pass --execute to apply)...');

        $unreferenced = $backfill->deleteUnreferenced($execute);
        $this->table(
            ['Action', 'Count'],
            [
                [$execute ? 'deleted_unreferenced' : 'would_delete_unreferenced', count($unreferenced['ids'])],
            ],
        );
        if ($unreferenced['ids'] !== []) {
            $this->line('IDs: '.implode(', ', array_slice($unreferenced['ids'], 0, 50)));
        }

        $dupes = $backfill->mergeDuplicatePlayerTeamPairs($execute);
        $this->table(
            ['Action', 'Count'],
            [
                [$execute ? 'merged_duplicate_groups' : 'would_merge_duplicate_groups', $dupes['merged_groups']],
                ['remapped_duplicate_ids', count($dupes['remapped_ids'])],
                ['skipped_transfer_spell_groups', $dupes['skipped_transfer_spell_groups']],
            ],
        );
        foreach ($dupes['remapped_ids'] as $from => $to) {
            $this->line("  {$from} → {$to}");
        }

        return self::SUCCESS;
    }
}
