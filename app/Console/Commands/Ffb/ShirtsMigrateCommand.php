<?php

namespace App\Console\Commands\Ffb;

use App\Models\Team;
use App\Support\AssetKey;
use App\Support\TeamShirt;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('ffb:shirts-migrate {--execute : Copy legacy shirt_NAT.png into team folders (default dry-run)}')]
#[Description('Migrate flat shirts/shirt_NAT.png files into shirts/{team_asset_key}/{nat}.png')]
class ShirtsMigrateCommand extends Command
{
    public function handle(): int
    {
        $execute = (bool) $this->option('execute');
        $this->info($execute ? 'Migrating team shirts...' : 'Dry-run shirt migration...');

        $copied = 0;
        $skipped = 0;
        $missing = 0;

        $teams = Team::query()
            ->orderBy('team_id')
            ->get(['team_id', 'team_nationality', 'asset_key']);

        foreach ($teams as $team) {
            $teamKey = (string) ($team->asset_key ?? '');
            $nat = TeamShirt::normalizeNationality((string) ($team->team_nationality ?? ''));
            if (! AssetKey::isValid($teamKey) || $nat === '') {
                continue;
            }

            $target = TeamShirt::defaultStoragePath($teamKey, $nat);
            if (is_file($target)) {
                $skipped++;

                continue;
            }

            $source = TeamShirt::legacyPath($nat);
            if ($source === null) {
                $missing++;

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

        $this->table(
            ['Metric', 'Value'],
            [
                [$execute ? 'copied' : 'would_copy', $copied],
                ['already_had_target', $skipped],
                ['no_legacy_source', $missing],
            ],
        );

        return self::SUCCESS;
    }
}
