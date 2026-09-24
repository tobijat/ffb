<?php

namespace App\Console\Commands\Ffb;

use App\Services\EloRatingClient;
use App\Services\TeamEloBackfillService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ffb:backfill-teamelo {--execute : Persist ffb_teamelo rows (default dry-run)} {--league-id= : Single league to backfill (omit to run the default historical map)} {--elo-url= : Elo ratings TSV URL (required with --league-id)} {--elo-year= : Elo year (defaults to YYYY parsed from --elo-url)}')]
#[Description('Backfill ffb_teamelo with historical Elo ratings per team × league')]
class BackfillTeamEloCommand extends Command
{
    public function handle(TeamEloBackfillService $backfill): int
    {
        $execute = (bool) $this->option('execute');
        $leagueIdOption = $this->option('league-id');
        $sources = $this->resolveSources($leagueIdOption);

        if ($sources === null) {
            return self::FAILURE;
        }

        $exit = self::SUCCESS;
        foreach ($sources as $source) {
            $leagueId = $source['league_id'];
            $eloUrl = $source['elo_url'];
            $eloYear = $source['elo_year'];

            $this->info($execute
                ? "Executing team-elo backfill for league {$leagueId} from {$eloUrl} (year={$eloYear})..."
                : "Dry-run team-elo backfill for league {$leagueId} from {$eloUrl} (year={$eloYear})...");

            $elo = new EloRatingClient($eloUrl);
            $result = $backfill->backfillLeague($leagueId, $elo, $eloYear, $execute);

            if (! ($result['ok'] ?? false)) {
                foreach ($result['errors'] ?? ['Unbekannter Fehler.'] as $error) {
                    $this->error($error);
                }
                $exit = self::FAILURE;

                continue;
            }

            $this->info((string) ($result['message'] ?? 'OK'));

            if (($result['skipped_teams'] ?? []) !== []) {
                $this->warn('Teams without Elo mapping:');
                foreach ($result['skipped_teams'] as $skipped) {
                    $this->line('  #'.$skipped['team_id'].' '.$skipped['team_name']);
                }
            }

            $teams = $result['teams'] ?? [];
            if ($teams !== []) {
                $this->table(
                    ['Team', 'ELO', 'Year'],
                    collect($teams)->map(fn (array $row): array => [
                        $row['team_name'] ?? '',
                        $row['elo_rating'] ?? '',
                        $eloYear,
                    ])->all(),
                );
            }

            $this->line(sprintf(
                '%s %d ffb_teamelo row(s).',
                $execute ? 'Wrote' : 'Would write',
                (int) ($result['written'] ?? 0),
            ));
        }

        if (! $execute && $exit === self::SUCCESS) {
            $this->comment('Re-run with --execute to persist.');
        }

        return $exit;
    }

    /**
     * @return list<array{league_id: int, elo_url: string, elo_year: int}>|null
     */
    private function resolveSources(mixed $leagueIdOption): ?array
    {
        if ($leagueIdOption === null || $leagueIdOption === '') {
            return TeamEloBackfillService::defaultLeagueSources();
        }

        $leagueId = (int) $leagueIdOption;
        $eloUrl = trim((string) $this->option('elo-url'));
        if ($eloUrl === '') {
            $this->error('--elo-url is required when --league-id is set.');

            return null;
        }

        $yearOption = $this->option('elo-year');
        $eloYear = ($yearOption !== null && $yearOption !== '')
            ? (int) $yearOption
            : TeamEloBackfillService::yearFromEloUrl($eloUrl);

        if ($eloYear === null) {
            $this->error('Could not parse Elo year from URL; pass --elo-year=YYYY.');

            return null;
        }

        return [[
            'league_id' => $leagueId,
            'elo_url' => $eloUrl,
            'elo_year' => $eloYear,
        ]];
    }
}
