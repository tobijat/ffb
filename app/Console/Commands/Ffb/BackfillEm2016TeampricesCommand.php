<?php

namespace App\Console\Commands\Ffb;

use App\Services\AdminPlayerpriceService;
use App\Services\EloRatingClient;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ffb:backfill-em2016-teamprices {--execute : Persist ffb_teamprice rows (default dry-run)} {--elo-url=http://www.eloratings.net/2015.tsv : Elo ratings TSV URL} {--league-id=25 : League to backfill} {--min-price=1 : Minimum team price for the weakest team}')]
#[Description('Backfill ffb_teamprice for EM 2016 (or another league) using Elo Team-Preis logic')]
class BackfillEm2016TeampricesCommand extends Command
{
    public function handle(AdminPlayerpriceService $playerprice): int
    {
        $execute = (bool) $this->option('execute');
        $leagueId = (int) $this->option('league-id');
        $eloUrl = (string) $this->option('elo-url');
        $minPrice = (float) $this->option('min-price');

        $this->info($execute
            ? "Executing teamprice backfill for league {$leagueId} from {$eloUrl} (min_price={$minPrice})..."
            : "Dry-run teamprice backfill for league {$leagueId} from {$eloUrl} (min_price={$minPrice})...");

        $elo = new EloRatingClient($eloUrl);
        $result = $playerprice->backfillHistoricalLeagueTeamPrices($leagueId, $elo, [
            'min_price' => $minPrice,
        ], $execute);

        if (! ($result['ok'] ?? false)) {
            foreach ($result['errors'] ?? ['Unbekannter Fehler.'] as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $this->info((string) ($result['message'] ?? 'OK'));

        if (($result['skipped_teams'] ?? []) !== []) {
            $this->warn('Teams without Elo mapping:');
            foreach ($result['skipped_teams'] as $skipped) {
                $this->line('  #'.$skipped['team_id'].' '.$skipped['team_name']);
            }
        }

        $previewTeams = $result['preview']['teams'] ?? [];
        if ($previewTeams !== []) {
            $this->table(
                ['Team', 'ELO', 'Price'],
                collect($previewTeams)->map(static fn (array $row): array => [
                    $row['team_name'] ?? '',
                    $row['elo_rating'] ?? '',
                    $row['price'] ?? '',
                ])->all(),
            );
        }

        $this->line(sprintf(
            'Matchrounds: %s',
            implode(', ', $result['matchround_ids'] ?? []),
        ));
        $this->line(sprintf(
            'Would write %d teamprice rows (%d teams × %d rounds).',
            count($result['details'] ?? []),
            (int) ($result['team_count'] ?? 0),
            count($result['matchround_ids'] ?? []),
        ));

        if (! $execute) {
            $this->comment('Re-run with --execute to persist.');
        }

        return self::SUCCESS;
    }
}
