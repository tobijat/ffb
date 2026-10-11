<?php

namespace App\Console\Commands\Ffb;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Matchround;
use App\Models\Team;
use App\Support\AssetKey;
use App\Support\TeamShirt;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('ffb:shirts-preserve-league-pngs {--execute : Copy default shirt.png into league-specific PNG paths (default dry-run)}')]
#[Description('For past leagues (excl. Nations League 2026 / WM 2026), copy each participating team\'s default PNG shirt to {nat}-{league_key}.png')]
class ShirtsPreserveLeaguePngsCommand extends Command
{
    /** @var list<string> */
    private const EXCLUDED_LEAGUE_TITLES = [
        'Nations League 2026',
        'WM 2026',
    ];

    public function handle(): int
    {
        $execute = (bool) $this->option('execute');
        $this->info($execute
            ? 'Preserving league-specific PNG shirts...'
            : 'Dry-run: preserve league-specific PNG shirts...');

        $copied = 0;
        $skippedExisting = 0;
        $missingSource = 0;
        $skippedLeague = 0;
        $pairs = 0;

        $leagues = League::query()
            ->whereNotIn('league_title', self::EXCLUDED_LEAGUE_TITLES)
            ->orderBy('league_id')
            ->get(['league_id', 'league_title', 'asset_key']);

        foreach ($leagues as $league) {
            $leagueKey = (string) ($league->asset_key ?? '');
            if (! AssetKey::isValid($leagueKey)) {
                $skippedLeague++;
                $this->warn("Skip league #{$league->league_id} ({$league->league_title}): invalid asset_key");

                continue;
            }

            $teamIds = $this->participatingTeamIds((int) $league->league_id);
            if ($teamIds === []) {
                continue;
            }

            $teams = Team::query()
                ->whereIn('team_id', $teamIds)
                ->get(['team_id', 'team_name', 'team_nationality', 'asset_key']);

            foreach ($teams as $team) {
                $teamKey = (string) ($team->asset_key ?? '');
                $nat = TeamShirt::normalizeNationality((string) ($team->team_nationality ?? ''));
                if (! AssetKey::isValid($teamKey) || $nat === '') {
                    continue;
                }

                $pairs++;
                $source = TeamShirt::defaultStoragePath($teamKey, $nat, 'png');
                $target = TeamShirt::leagueStoragePath($teamKey, $nat, $leagueKey, 'png');

                if (is_file($target)) {
                    $skippedExisting++;

                    continue;
                }

                if (! is_file($source)) {
                    $missingSource++;

                    continue;
                }

                if ($execute) {
                    File::ensureDirectoryExists(dirname($target));
                    if (! @copy($source, $target)) {
                        $this->warn("Failed to copy {$source} → {$target}");

                        continue;
                    }
                }

                $copied++;
            }
        }

        $this->table(
            ['Metric', 'Value'],
            [
                ['leagues_considered', $leagues->count()],
                ['leagues_skipped_invalid_key', $skippedLeague],
                ['team_league_pairs', $pairs],
                [$execute ? 'copied' : 'would_copy', $copied],
                ['already_had_league_png', $skippedExisting],
                ['missing_default_png', $missingSource],
            ],
        );

        return self::SUCCESS;
    }

    /**
     * @return list<int>
     */
    private function participatingTeamIds(int $leagueId): array
    {
        $roundIds = Matchround::query()
            ->where('matchround_league_id', $leagueId)
            ->pluck('matchround_id')
            ->map(fn ($id): int => (int) $id)
            ->all();

        if ($roundIds === []) {
            return [];
        }

        return MatchGame::query()
            ->whereIn('match_round', $roundIds)
            ->get(['match_hometeam_id', 'match_guestteam_id'])
            ->flatMap(fn (MatchGame $match): array => [
                (int) $match->match_hometeam_id,
                (int) $match->match_guestteam_id,
            ])
            ->filter(fn (int $id): bool => $id > 0)
            ->unique()
            ->values()
            ->all();
    }
}
