<?php

namespace App\Console\Commands\Ffb;

use App\Services\PlayerteamLeagueInventoryService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('ffb:playerteam-league-inventory {--json= : Optional path for the full JSON report}')]
#[Description('Phase 0 read-only inventory for playerteam league scoping')]
class PlayerteamLeagueInventoryCommand extends Command
{
    public function handle(PlayerteamLeagueInventoryService $inventory): int
    {
        $this->info('Building playerteam league inventory (read-only)...');
        $report = $inventory->report();

        $counts = $report['counts'];
        $this->table(
            ['Metric', 'Value'],
            [
                ['playerteam_total', $counts['playerteam_total']],
                ['playerteam_referenced', $counts['playerteam_referenced']],
                ['playerteam_unreferenced', $counts['playerteam_unreferenced']],
                ['with_derived_leagues', $counts['playerteam_with_derived_leagues']],
                ['with_no_derived_league', $counts['playerteam_with_no_derived_league']],
                ['in_multiple_leagues', $counts['playerteam_in_multiple_leagues']],
                ['duplicate_player_team_groups', $counts['duplicate_player_team_groups']],
                ['duplicate_player_team_entries', $counts['duplicate_player_team_entries']],
                ['has_playerteam_league_id', $report['schema']['has_playerteam_league_id'] ? 'yes' : 'no'],
            ],
        );

        $this->newLine();
        $this->info('Distinct playerteam IDs by reference source:');
        $refRows = [];
        foreach ($report['reference_distinct_playerteam_ids'] as $source => $count) {
            $refRows[] = [$source, $count];
        }
        $this->table(['Source', 'Distinct playerteam IDs'], $refRows);

        $this->newLine();
        $this->info('League-count histogram (how many leagues a playerteam appears in):');
        $histRows = [];
        foreach ($report['league_count_histogram'] as $leagueCount => $rows) {
            $histRows[] = [$leagueCount, $rows];
        }
        $this->table(['# leagues', '# playerteams'], $histRows);

        $this->newLine();
        $this->info('Pictures:');
        $pic = $report['pictures'];
        $this->table(
            ['Metric', 'Value'],
            [
                ['rows_with_picture_flag', $pic['rows_with_picture_flag']],
                ['legacy_files_exist', $pic['legacy_files_exist']],
                ['legacy_files_missing', $pic['legacy_files_missing']],
                ['unique_team_player_targets', $pic['unique_team_player_picture_targets']],
                ['extra_flags_same_team_player', $pic['extra_flags_sharing_same_team_player_target']],
            ],
        );

        if (($report['no_derived_league_breakdown'] ?? null) !== null) {
            $this->newLine();
            $this->info('Rows with no derived league (need a Phase 1/2 policy):');
            $b = $report['no_derived_league_breakdown'];
            $this->table(
                ['Bucket', 'Count'],
                [
                    ['unreferenced (delete candidates)', $b['unreferenced']],
                    ['playerfid only', $b['playerfid_only']],
                    ['other refs without league signal', $b['other_references_without_league']],
                ],
            );
        }

        if ($counts['duplicate_player_team_groups'] > 0) {
            $this->newLine();
            $this->warn('Duplicate (player, team) groups — resolve before backfill:');
            foreach ($report['duplicate_player_team_groups'] as $group) {
                $ids = implode(', ', array_column($group['entries'], 'playerteam_id'));
                $this->line(sprintf(
                    '  player %d %s %s / team %d %s → %d rows [%s]',
                    $group['player_id'],
                    $group['player_fname'],
                    $group['player_lname'],
                    $group['team_id'],
                    $group['team_name'],
                    $group['entry_count'],
                    $ids,
                ));
            }
        }

        $jsonPath = $this->option('json');
        if (! is_string($jsonPath) || $jsonPath === '') {
            $dir = storage_path('app/playerteam-league');
            File::ensureDirectoryExists($dir);
            $jsonPath = $dir.'/inventory-'.now()->format('Ymd-His').'.json';
        } else {
            File::ensureDirectoryExists(dirname($jsonPath));
        }

        File::put($jsonPath, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n");
        $this->newLine();
        $this->info('Full JSON report written to: '.$jsonPath);

        foreach ($report['notes'] as $note) {
            $this->comment('- '.$note);
        }

        return self::SUCCESS;
    }
}
