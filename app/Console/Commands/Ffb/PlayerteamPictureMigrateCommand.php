<?php

namespace App\Console\Commands\Ffb;

use App\Models\Playerteam;
use App\Support\PlayerPicture;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('ffb:playerteam-picture-migrate {--execute : Copy/rename files (default dry-run)}')]
#[Description('Migrate legacy {playerteam_id}.jpg pictures to {team_id}-{player_id}.jpg')]
class PlayerteamPictureMigrateCommand extends Command
{
    public function handle(): int
    {
        $execute = (bool) $this->option('execute');
        $this->info($execute ? 'Migrating player pictures...' : 'Dry-run picture migration...');

        $dir = PlayerPicture::playersDir();
        $copied = 0;
        $skipped = 0;
        $missing = 0;

        $rows = Playerteam::query()
            ->orderBy('playerteam_id')
            ->get(['playerteam_id', 'playerteam_team_id', 'playerteam_player_id', 'playerteam_player_picture']);

        foreach ($rows as $row) {
            $teamId = (int) $row->playerteam_team_id;
            $playerId = (int) $row->playerteam_player_id;
            $ptId = (int) $row->playerteam_id;
            if ($teamId <= 0 || $playerId <= 0) {
                continue;
            }

            $target = PlayerPicture::storagePath($teamId, $playerId);
            if (is_file($target)) {
                $skipped++;

                continue;
            }

            $candidates = [
                $dir.DIRECTORY_SEPARATOR.$teamId.DIRECTORY_SEPARATOR.$ptId.'.jpg',
                $dir.DIRECTORY_SEPARATOR.$teamId.DIRECTORY_SEPARATOR.trim((string) ($row->playerteam_player_picture ?? '')),
            ];

            $source = null;
            foreach ($candidates as $candidate) {
                if ($candidate !== '' && is_file($candidate)) {
                    $source = $candidate;
                    break;
                }
            }

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
