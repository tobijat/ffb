<?php

namespace App\Console\Commands\Ffb;

use App\Models\Playerteam;
use App\Support\AssetKey;
use App\Support\PlayerPicture;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Note: legacy sources live under players/{team_id}/ (ID-based); targets use asset keys
 * (players/{team_asset_key}/{player_asset_key}.jpg). For a full ID-to-key directory
 * migration, prefer ffb:migrate-asset-paths.
 */
#[Signature('ffb:playerteam-picture-migrate {--execute : Copy/rename files (default dry-run)}')]
#[Description('Migrate legacy ID-based player pictures to players/{team_asset_key}/{player_asset_key}.jpg')]
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
            ->join('ffb_team', 'ffb_team.team_id', '=', 'ffb_playerteam.playerteam_team_id')
            ->join('ffb_player', 'ffb_player.player_id', '=', 'ffb_playerteam.playerteam_player_id')
            ->orderBy('ffb_playerteam.playerteam_id')
            ->get([
                'ffb_playerteam.playerteam_id',
                'ffb_playerteam.playerteam_team_id',
                'ffb_playerteam.playerteam_player_id',
                'ffb_playerteam.playerteam_player_picture',
                'ffb_team.asset_key as team_asset_key',
                'ffb_player.asset_key as player_asset_key',
            ]);

        foreach ($rows as $row) {
            $teamId = (int) $row->playerteam_team_id;
            $playerId = (int) $row->playerteam_player_id;
            $ptId = (int) $row->playerteam_id;
            $teamKey = (string) ($row->team_asset_key ?? '');
            $playerKey = (string) ($row->player_asset_key ?? '');
            if ($teamId <= 0 || $playerId <= 0 || ! AssetKey::isValid($teamKey) || ! AssetKey::isValid($playerKey)) {
                continue;
            }

            $target = PlayerPicture::storagePath($teamKey, $playerKey);
            if (is_file($target)) {
                $skipped++;

                continue;
            }

            $legacyDir = $dir.DIRECTORY_SEPARATOR.$teamId;
            $pictureName = trim((string) ($row->playerteam_player_picture ?? ''));
            $candidates = [
                $legacyDir.DIRECTORY_SEPARATOR.$ptId.'.jpg',
                $legacyDir.DIRECTORY_SEPARATOR.$teamId.'-'.$playerId.'.jpg',
            ];
            if ($pictureName !== '') {
                $candidates[] = $legacyDir.DIRECTORY_SEPARATOR.$pictureName;
            }

            $source = null;
            foreach ($candidates as $candidate) {
                if (is_file($candidate)) {
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
