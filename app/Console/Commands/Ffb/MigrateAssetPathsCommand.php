<?php

namespace App\Console\Commands\Ffb;

use App\Models\League;
use App\Models\Player;
use App\Models\Team;
use App\Support\AssetKey;
use App\Support\LeagueSymbol;
use App\Support\PlayerPicture;
use App\Support\TeamShirt;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

#[Signature('ffb:migrate-asset-paths
                            {--execute : Write keys and copy/rename files (default dry-run)}
                            {--cleanup : Remove legacy numeric-ID media paths after a successful copy}')]
#[Description('Backfill asset_key columns and migrate league/team/player media paths off numeric IDs')]
class MigrateAssetPathsCommand extends Command
{
    public function handle(): int
    {
        $execute = (bool) $this->option('execute');
        $cleanup = (bool) $this->option('cleanup');
        $this->info($execute ? 'Migrating asset keys and paths…' : 'Dry-run asset path migration…');

        if (! Schema::hasColumn('ffb_league', 'asset_key')) {
            $this->error('asset_key column missing — ALTER TABLE first.');

            return self::FAILURE;
        }

        $keyStats = $this->backfillKeys($execute);
        $fileStats = $this->migrateFiles($execute);

        if ($execute) {
            $this->ensureUniqueIndexes();
        }

        $cleanupStats = $cleanup ? $this->cleanupLegacyIdPaths($execute) : [];

        $this->table(
            ['Metric', 'Value'],
            array_merge(
                collect($keyStats)->map(fn ($v, $k) => [$k, $v])->values()->all(),
                collect($fileStats)->map(fn ($v, $k) => [$k, $v])->values()->all(),
                collect($cleanupStats)->map(fn ($v, $k) => [$k, $v])->values()->all(),
            ),
        );

        return self::SUCCESS;
    }

    /**
     * @return array<string, int>
     */
    private function backfillKeys(bool $execute): array
    {
        $stats = [
            'leagues_keyed' => 0,
            'teams_keyed' => 0,
            'players_keyed' => 0,
            'keys_already_set' => 0,
        ];

        League::query()->orderBy('league_id')->each(function (League $league) use ($execute, &$stats): void {
            if (AssetKey::isValid((string) ($league->asset_key ?? ''))) {
                $stats['keys_already_set']++;

                return;
            }
            $key = AssetKey::generate('ffb_league', (string) $league->league_title);
            if ($execute) {
                $league->asset_key = $key;
                $league->save();
            }
            $stats['leagues_keyed']++;
        });

        Team::query()->orderBy('team_id')->each(function (Team $team) use ($execute, &$stats): void {
            if (AssetKey::isValid((string) ($team->asset_key ?? ''))) {
                $stats['keys_already_set']++;

                return;
            }
            $key = AssetKey::generate('ffb_team', (string) $team->team_name);
            if ($execute) {
                $team->asset_key = $key;
                $team->save();
            }
            $stats['teams_keyed']++;
        });

        Player::query()->orderBy('player_id')->each(function (Player $player) use ($execute, &$stats): void {
            if (AssetKey::isValid((string) ($player->asset_key ?? ''))) {
                $stats['keys_already_set']++;

                return;
            }
            $source = AssetKey::playerSourceName(
                (string) $player->player_fname,
                (string) $player->player_lname,
            );
            $key = AssetKey::generate('ffb_player', $source);
            if ($execute) {
                $player->asset_key = $key;
                $player->save();
            }
            $stats['players_keyed']++;
        });

        return $stats;
    }

    /**
     * @return array<string, int>
     */
    private function migrateFiles(bool $execute): array
    {
        $stats = [
            'league_files_copied' => 0,
            'shirt_files_copied' => 0,
            'player_files_copied' => 0,
            'files_skipped_exists' => 0,
            'files_missing_source' => 0,
        ];

        $leagues = League::query()
            ->where('asset_key', '!=', '')
            ->get(['league_id', 'asset_key'])
            ->keyBy('league_id');
        $teams = Team::query()
            ->where('asset_key', '!=', '')
            ->get(['team_id', 'asset_key', 'team_nationality'])
            ->keyBy('team_id');
        $players = Player::query()
            ->where('asset_key', '!=', '')
            ->get(['player_id', 'asset_key'])
            ->keyBy('player_id');

        // Dry-run without execute still needs provisional keys for path preview —
        // when not executing, re-read after virtual generation is awkward; skip file
        // preview unless keys already exist, or generate ephemeral map.
        if (! $execute && $leagues->isEmpty() && $teams->isEmpty()) {
            // Build ephemeral key map for dry-run reporting
            $leagues = League::query()->orderBy('league_id')->get(['league_id', 'league_title', 'asset_key']);
            $map = collect();
            foreach ($leagues as $league) {
                $key = AssetKey::isValid((string) $league->asset_key)
                    ? (string) $league->asset_key
                    : AssetKey::slug((string) $league->league_title).'-dry1';
                $map[(int) $league->league_id] = (object) [
                    'league_id' => (int) $league->league_id,
                    'asset_key' => $key,
                ];
            }
            $leagues = collect($map);

            $teamMap = collect();
            foreach (Team::query()->orderBy('team_id')->get(['team_id', 'team_name', 'asset_key', 'team_nationality']) as $team) {
                $key = AssetKey::isValid((string) $team->asset_key)
                    ? (string) $team->asset_key
                    : AssetKey::slug((string) $team->team_name).'-dry1';
                $teamMap[(int) $team->team_id] = (object) [
                    'team_id' => (int) $team->team_id,
                    'asset_key' => $key,
                    'team_nationality' => (string) $team->team_nationality,
                ];
            }
            $teams = collect($teamMap);

            $playerMap = collect();
            foreach (Player::query()->orderBy('player_id')->get(['player_id', 'player_fname', 'player_lname', 'asset_key']) as $player) {
                $key = AssetKey::isValid((string) $player->asset_key)
                    ? (string) $player->asset_key
                    : AssetKey::slug(AssetKey::playerSourceName((string) $player->player_fname, (string) $player->player_lname)).'-dry1';
                $playerMap[(int) $player->player_id] = (object) [
                    'player_id' => (int) $player->player_id,
                    'asset_key' => $key,
                ];
            }
            $players = collect($playerMap);
        }

        $leagueIdToKey = [];
        foreach ($leagues as $league) {
            $leagueIdToKey[(int) $league->league_id] = (string) $league->asset_key;
        }
        $teamIdToKey = [];
        foreach ($teams as $team) {
            $teamIdToKey[(int) $team->team_id] = (string) $team->asset_key;
        }
        $playerIdToKey = [];
        foreach ($players as $player) {
            $playerIdToKey[(int) $player->player_id] = (string) $player->asset_key;
        }

        // League logos: leagues/{id}.* → leagues/{key}.*
        $leaguesDir = LeagueSymbol::leaguesDir();
        if (is_dir($leaguesDir)) {
            foreach ($leagueIdToKey as $id => $key) {
                foreach (['png', 'webp', 'jpg', 'jpeg', 'gif'] as $ext) {
                    $source = $leaguesDir.DIRECTORY_SEPARATOR.$id.'.'.$ext;
                    if (! is_file($source)) {
                        continue;
                    }
                    $target = $leaguesDir.DIRECTORY_SEPARATOR.$key.'.'.$ext;
                    $this->copyFile($source, $target, $execute, $stats, 'league_files_copied');
                }
            }
        }

        // Shirts: shirts/{team_id}/{nat}[-{league_id}].* → shirts/{team_key}/…
        $shirtsDir = TeamShirt::shirtsDir();
        if (is_dir($shirtsDir)) {
            foreach ($teamIdToKey as $teamId => $teamKey) {
                $oldDir = $shirtsDir.DIRECTORY_SEPARATOR.$teamId;
                if (! is_dir($oldDir)) {
                    continue;
                }
                foreach (File::files($oldDir) as $file) {
                    $name = $file->getFilename();
                    if (! preg_match('/^([a-z0-9_]+)(?:-(\d+))?\\.(svg|png)$/i', $name, $m)) {
                        continue;
                    }
                    $nat = strtolower($m[1]);
                    $leagueId = isset($m[2]) && $m[2] !== '' ? (int) $m[2] : 0;
                    $ext = strtolower($m[3]);
                    $newName = $nat;
                    if ($leagueId > 0) {
                        $leagueKey = $leagueIdToKey[$leagueId] ?? null;
                        if ($leagueKey === null) {
                            $stats['files_missing_source']++;

                            continue;
                        }
                        $newName .= '-'.$leagueKey;
                    }
                    $newName .= '.'.$ext;
                    $target = $shirtsDir.DIRECTORY_SEPARATOR.$teamKey.DIRECTORY_SEPARATOR.$newName;
                    $this->copyFile($file->getPathname(), $target, $execute, $stats, 'shirt_files_copied');
                }
            }
        }

        // Players: players/{team_id}/{team_id}-{player_id}.jpg → players/{team_key}/{player_key}.jpg
        $playersDir = PlayerPicture::playersDir();
        if (is_dir($playersDir)) {
            foreach ($teamIdToKey as $teamId => $teamKey) {
                $oldDir = $playersDir.DIRECTORY_SEPARATOR.$teamId;
                if (! is_dir($oldDir)) {
                    continue;
                }
                foreach (File::files($oldDir) as $file) {
                    $name = $file->getFilename();
                    $playerId = 0;
                    if (preg_match('/^'.$teamId.'-(\d+)\\.jpg$/i', $name, $m)) {
                        $playerId = (int) $m[1];
                    } elseif (preg_match('/^(\d+)\\.jpg$/i', $name, $m)) {
                        $playerId = (int) $m[1];
                    }
                    if ($playerId <= 0 || ! isset($playerIdToKey[$playerId])) {
                        $stats['files_missing_source']++;

                        continue;
                    }
                    $target = PlayerPicture::storagePath($teamKey, $playerIdToKey[$playerId]);
                    $this->copyFile($file->getPathname(), $target, $execute, $stats, 'player_files_copied');
                }
            }

            // Flat legacy players/{player_id}.jpg
            foreach (File::files($playersDir) as $file) {
                $name = $file->getFilename();
                if (! preg_match('/^(\d+)\\.jpg$/i', $name, $m)) {
                    continue;
                }
                $playerId = (int) $m[1];
                $playerKey = $playerIdToKey[$playerId] ?? null;
                if ($playerKey === null) {
                    continue;
                }
                $target = $playersDir.DIRECTORY_SEPARATOR.$playerKey.'.jpg';
                $this->copyFile($file->getPathname(), $target, $execute, $stats, 'player_files_copied');
            }
        }

        return $stats;
    }

    /**
     * @param  array<string, int>  $stats
     */
    private function copyFile(string $source, string $target, bool $execute, array &$stats, string $copiedKey): void
    {
        if (is_file($target)) {
            $stats['files_skipped_exists']++;

            return;
        }
        if (! is_file($source)) {
            $stats['files_missing_source']++;

            return;
        }
        if ($execute) {
            File::ensureDirectoryExists(dirname($target));
            if (! @copy($source, $target)) {
                $this->warn("Failed to copy {$source} → {$target}");

                return;
            }
        }
        $stats[$copiedKey]++;
    }

    private function ensureUniqueIndexes(): void
    {
        foreach (
            [
                'ffb_league' => 'league_asset_key_unique',
                'ffb_team' => 'team_asset_key_unique',
                'ffb_player' => 'player_asset_key_unique',
            ] as $table => $index
        ) {
            $exists = collect(DB::select('SHOW INDEX FROM `'.$table.'` WHERE Key_name = ?', [$index]))->isNotEmpty();
            if ($exists) {
                continue;
            }
            DB::statement("ALTER TABLE `{$table}` ADD UNIQUE KEY `{$index}` (`asset_key`)");
            $this->line("Added UNIQUE index {$index}");
        }
    }

    /**
     * Remove numeric-ID media only when the key-based target already exists.
     *
     * @return array<string, int>
     */
    private function cleanupLegacyIdPaths(bool $execute): array
    {
        $stats = [
            'legacy_league_files_removed' => 0,
            'legacy_shirt_dirs_removed' => 0,
            'legacy_player_dirs_removed' => 0,
            'legacy_player_flat_removed' => 0,
            'legacy_cleanup_skipped' => 0,
        ];

        $leagueIdToKey = League::query()
            ->where('asset_key', '!=', '')
            ->pluck('asset_key', 'league_id')
            ->all();
        $teamIdToKey = Team::query()
            ->where('asset_key', '!=', '')
            ->pluck('asset_key', 'team_id')
            ->all();

        $leaguesDir = LeagueSymbol::leaguesDir();
        if (is_dir($leaguesDir)) {
            foreach ($leagueIdToKey as $id => $key) {
                foreach (['png', 'webp', 'jpg', 'jpeg', 'gif'] as $ext) {
                    $legacy = $leaguesDir.DIRECTORY_SEPARATOR.$id.'.'.$ext;
                    $modern = $leaguesDir.DIRECTORY_SEPARATOR.$key.'.'.$ext;
                    if (! is_file($legacy)) {
                        continue;
                    }
                    if (! is_file($modern)) {
                        $stats['legacy_cleanup_skipped']++;

                        continue;
                    }
                    if ($execute) {
                        @unlink($legacy);
                    }
                    $stats['legacy_league_files_removed']++;
                }
            }
        }

        $shirtsDir = TeamShirt::shirtsDir();
        if (is_dir($shirtsDir)) {
            foreach ($teamIdToKey as $id => $key) {
                $legacy = $shirtsDir.DIRECTORY_SEPARATOR.$id;
                $modern = $shirtsDir.DIRECTORY_SEPARATOR.$key;
                if (! is_dir($legacy)) {
                    continue;
                }
                if (! is_dir($modern)) {
                    $stats['legacy_cleanup_skipped']++;

                    continue;
                }
                if ($execute) {
                    File::deleteDirectory($legacy);
                }
                $stats['legacy_shirt_dirs_removed']++;
            }
        }

        $playersDir = PlayerPicture::playersDir();
        if (is_dir($playersDir)) {
            foreach ($teamIdToKey as $id => $key) {
                $legacy = $playersDir.DIRECTORY_SEPARATOR.$id;
                $modern = $playersDir.DIRECTORY_SEPARATOR.$key;
                if (! is_dir($legacy)) {
                    continue;
                }
                if (! is_dir($modern)) {
                    $stats['legacy_cleanup_skipped']++;

                    continue;
                }
                if ($execute) {
                    File::deleteDirectory($legacy);
                }
                $stats['legacy_player_dirs_removed']++;
            }

            foreach (File::files($playersDir) as $file) {
                $name = $file->getFilename();
                if (! preg_match('/^(\d+)\.jpg$/i', $name, $m)) {
                    continue;
                }
                $playerId = (int) $m[1];
                $playerKey = (string) (Player::query()->whereKey($playerId)->value('asset_key') ?? '');
                if ($playerKey === '') {
                    continue;
                }
                $modern = $playersDir.DIRECTORY_SEPARATOR.$playerKey.'.jpg';
                if (! is_file($modern)) {
                    $stats['legacy_cleanup_skipped']++;

                    continue;
                }
                if ($execute) {
                    @unlink($file->getPathname());
                }
                $stats['legacy_player_flat_removed']++;
            }
        }

        return $stats;
    }
}
