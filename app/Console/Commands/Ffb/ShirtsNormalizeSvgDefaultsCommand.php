<?php

namespace App\Console\Commands\Ffb;

use App\Models\League;
use App\Models\Team;
use App\Support\AssetKey;
use App\Support\TeamShirt;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('ffb:shirts-normalize-svg-defaults {--execute : Write default {nat}.svg and remove league-specific SVGs (default dry-run)}')]
#[Description('Ensure teams with any shirt SVG have {nat}.svg as default, then remove league-specific SVGs')]
class ShirtsNormalizeSvgDefaultsCommand extends Command
{
    public function handle(): int
    {
        $execute = (bool) $this->option('execute');
        $this->info($execute
            ? 'Normalizing SVG shirt defaults...'
            : 'Dry-run: normalize SVG shirt defaults...');

        $preferredLeagueKeys = $this->preferredLeagueKeys();

        $teamsWithSvg = 0;
        $promoted = 0;
        $alreadyHadDefault = 0;
        $removedLeagueSvg = 0;
        $failed = 0;

        $teams = Team::query()
            ->orderBy('team_id')
            ->get(['team_id', 'team_name', 'team_nationality', 'asset_key']);

        foreach ($teams as $team) {
            $teamKey = (string) ($team->asset_key ?? '');
            $nat = TeamShirt::normalizeNationality((string) ($team->team_nationality ?? ''));
            if (! AssetKey::isValid($teamKey) || $nat === '') {
                continue;
            }

            $dir = TeamShirt::shirtsDir().DIRECTORY_SEPARATOR.$teamKey;
            if (! is_dir($dir)) {
                continue;
            }

            $defaultPath = TeamShirt::defaultStoragePath($teamKey, $nat, 'svg');
            $leagueSvgs = $this->leagueSvgPaths($dir, $nat);

            $hasDefault = is_file($defaultPath);
            if (! $hasDefault && $leagueSvgs === []) {
                continue;
            }

            $teamsWithSvg++;

            if (! $hasDefault) {
                $source = $this->pickPromotionSource($leagueSvgs, $nat, $preferredLeagueKeys);
                if ($source === null) {
                    $failed++;
                    $this->warn("No SVG source for team #{$team->team_id} ({$team->team_name})");

                    continue;
                }

                if ($execute) {
                    if (! @copy($source, $defaultPath)) {
                        $failed++;
                        $this->warn("Failed to promote {$source} → {$defaultPath}");

                        continue;
                    }
                }
                $promoted++;
                $hasDefault = true;
            } else {
                $alreadyHadDefault++;
            }

            if (! $hasDefault) {
                continue;
            }

            // Re-scan after possible promotion so we don't delete the file we just copied
            // if source and target somehow matched (they shouldn't).
            foreach ($this->leagueSvgPaths($dir, $nat) as $leagueSvg) {
                if ($execute) {
                    if (! @unlink($leagueSvg)) {
                        $failed++;
                        $this->warn("Failed to remove {$leagueSvg}");

                        continue;
                    }
                }
                $removedLeagueSvg++;
            }
        }

        $this->table(
            ['Metric', 'Value'],
            [
                ['teams_with_svg', $teamsWithSvg],
                [$execute ? 'promoted_to_default' : 'would_promote_to_default', $promoted],
                ['already_had_default_svg', $alreadyHadDefault],
                [$execute ? 'removed_league_svg' : 'would_remove_league_svg', $removedLeagueSvg],
                ['failed', $failed],
            ],
        );

        return self::SUCCESS;
    }

    /**
     * @return list<string>
     */
    private function preferredLeagueKeys(): array
    {
        $keys = [];
        foreach (['WM 2026', 'Nations League 2026'] as $title) {
            $key = League::query()->where('league_title', $title)->value('asset_key');
            if (is_string($key) && AssetKey::isValid($key)) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /**
     * @return list<string>
     */
    private function leagueSvgPaths(string $dir, string $nat): array
    {
        $prefix = $nat.'-';
        $paths = [];
        foreach (File::files($dir) as $file) {
            $name = $file->getFilename();
            if (! str_ends_with(strtolower($name), '.svg')) {
                continue;
            }
            if (! str_starts_with(strtolower($name), $prefix)) {
                continue;
            }
            // {nat}.svg is default; league files are {nat}-{league_key}.svg
            if (strtolower($name) === $nat.'.svg') {
                continue;
            }
            $paths[] = $file->getPathname();
        }

        return $paths;
    }

    /**
     * @param  list<string>  $leagueSvgs
     * @param  list<string>  $preferredLeagueKeys
     */
    private function pickPromotionSource(array $leagueSvgs, string $nat, array $preferredLeagueKeys): ?string
    {
        if ($leagueSvgs === []) {
            return null;
        }

        $byBasename = [];
        foreach ($leagueSvgs as $path) {
            $byBasename[strtolower(basename($path))] = $path;
        }

        foreach ($preferredLeagueKeys as $leagueKey) {
            $name = $nat.'-'.$leagueKey.'.svg';
            if (isset($byBasename[$name])) {
                return $byBasename[$name];
            }
        }

        usort($leagueSvgs, function (string $a, string $b): int {
            return filemtime($b) <=> filemtime($a);
        });

        return $leagueSvgs[0];
    }
}
