<?php

namespace App\Services;

use App\Models\Userteam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Process\Process;

/**
 * Inventory, backup, and backfill for ffb_userteam_slot normalization.
 */
class UserteamSlotBackfillService
{
    /**
     * @return array{
     *     userteam_count: int,
     *     wide_nonzero_slots: int,
     *     empty_wide_slots: int,
     *     duplicate_user_round_groups: int,
     *     slot_row_count: int
     * }
     */
    public function inventory(): array
    {
        $userteamCount = (int) DB::table('ffb_userteam')->count();
        $wideNonZero = 0;
        $emptyWide = 0;

        if (Userteam::hasWideSlotColumns()) {
            foreach (Userteam::playerSlotColumns() as $column) {
                $wideNonZero += (int) DB::table('ffb_userteam')->where($column, '>', 0)->count();
                $emptyWide += (int) DB::table('ffb_userteam')->where($column, '<=', 0)->count();
            }
        }

        $duplicates = (int) DB::table('ffb_userteam')
            ->select('userteam_user_id', 'userteam_matchround_id', DB::raw('COUNT(*) as c'))
            ->groupBy('userteam_user_id', 'userteam_matchround_id')
            ->having('c', '>', 1)
            ->count();

        $slotCount = Schema::hasTable('ffb_userteam_slot')
            ? (int) DB::table('ffb_userteam_slot')->count()
            : 0;

        return [
            'userteam_count' => $userteamCount,
            'wide_nonzero_slots' => $wideNonZero,
            'empty_wide_slots' => $emptyWide,
            'duplicate_user_round_groups' => $duplicates,
            'slot_row_count' => $slotCount,
        ];
    }

    /**
     * @return array{path: string, bytes: int}
     */
    public function backup(?string $dir = null): array
    {
        $connection = (string) config('database.default');
        $config = config('database.connections.'.$connection);
        if (! is_array($config) || ($config['driver'] ?? '') !== 'mysql') {
            throw new \RuntimeException('Backup currently supports the mysql driver only.');
        }

        $dir ??= storage_path('app/userteam-slot/backups');
        File::ensureDirectoryExists($dir);

        $stamp = now()->format('Ymd-His');
        $file = rtrim($dir, '\\/').DIRECTORY_SEPARATOR.'ffb-userteam-'.$stamp.'.sql';
        $tables = ['ffb_userteam'];
        if (Schema::hasTable('ffb_userteam_slot')) {
            $tables[] = 'ffb_userteam_slot';
        }

        $host = (string) ($config['host'] ?? '127.0.0.1');
        $port = (string) ($config['port'] ?? '3306');
        $database = (string) ($config['database'] ?? '');
        $username = (string) ($config['username'] ?? '');
        $password = (string) ($config['password'] ?? '');

        $command = [
            'mysqldump',
            '--host='.$host,
            '--port='.$port,
            '--user='.$username,
            '--single-transaction',
            '--routines=false',
            '--triggers=false',
            $database,
            ...$tables,
        ];

        $process = new Process($command);
        $process->setTimeout(600);
        if ($password !== '') {
            $process->setEnv(['MYSQL_PWD' => $password] + $_ENV);
        }
        $process->run();

        if (! $process->isSuccessful()) {
            throw new \RuntimeException(
                trim($process->getErrorOutput()) !== '' ? $process->getErrorOutput() : $process->getOutput()
            );
        }

        File::put($file, $process->getOutput());

        return ['path' => $file, 'bytes' => (int) File::size($file)];
    }

    /**
     * @return array{
     *     processed_userteams: int,
     *     slots_written: int,
     *     skipped_empty: int,
     *     already_had_slots: int
     * }
     */
    public function backfill(bool $execute): array
    {
        if (! Userteam::hasWideSlotColumns()) {
            throw new \RuntimeException('Wide userteam_player_id columns already removed; nothing to backfill.');
        }

        if (! Schema::hasTable('ffb_userteam_slot')) {
            throw new \RuntimeException('ffb_userteam_slot table missing; run migrations first.');
        }

        $processed = 0;
        $written = 0;
        $skippedEmpty = 0;
        $alreadyHad = 0;

        $userteams = DB::table('ffb_userteam')->orderBy('userteam_id')->get();
        foreach ($userteams as $row) {
            $processed++;
            $userteamId = (int) $row->userteam_id;
            $existing = (int) DB::table('ffb_userteam_slot')
                ->where('userteam_slot_userteam_id', $userteamId)
                ->count();
            if ($existing > 0) {
                $alreadyHad++;

                continue;
            }

            $inserts = [];
            foreach (Userteam::playerSlotColumns() as $index => $column) {
                $playerteamId = (int) ($row->{$column} ?? 0);
                if ($playerteamId <= 0) {
                    $skippedEmpty++;

                    continue;
                }
                $inserts[] = [
                    'userteam_slot_userteam_id' => $userteamId,
                    'userteam_slot_slot' => $index + 1,
                    'userteam_slot_playerteam_id' => $playerteamId,
                ];
            }

            if ($inserts === []) {
                continue;
            }

            if ($execute) {
                DB::table('ffb_userteam_slot')->insert($inserts);
            }
            $written += count($inserts);
        }

        return [
            'processed_userteams' => $processed,
            'slots_written' => $written,
            'skipped_empty' => $skippedEmpty,
            'already_had_slots' => $alreadyHad,
        ];
    }

    /**
     * @return array{ok: bool, issues: list<string>}
     */
    public function verify(): array
    {
        $issues = [];

        if (! Schema::hasTable('ffb_userteam_slot')) {
            return ['ok' => false, 'issues' => ['ffb_userteam_slot missing']];
        }

        if (Userteam::hasWideSlotColumns()) {
            $wideNonZero = 0;
            foreach (Userteam::playerSlotColumns() as $column) {
                $wideNonZero += (int) DB::table('ffb_userteam')->where($column, '>', 0)->count();
            }
            $slotCount = (int) DB::table('ffb_userteam_slot')->where('userteam_slot_playerteam_id', '>', 0)->count();
            if ($wideNonZero !== $slotCount) {
                $issues[] = "wide nonzero slots ({$wideNonZero}) != slot rows ({$slotCount})";
            }

            $mismatches = 0;
            foreach (DB::table('ffb_userteam')->orderBy('userteam_id')->cursor() as $row) {
                $userteamId = (int) $row->userteam_id;
                $fromWide = [];
                foreach (Userteam::playerSlotColumns() as $index => $column) {
                    $id = (int) ($row->{$column} ?? 0);
                    if ($id > 0) {
                        $fromWide[$index + 1] = $id;
                    }
                }
                $fromSlots = DB::table('ffb_userteam_slot')
                    ->where('userteam_slot_userteam_id', $userteamId)
                    ->where('userteam_slot_playerteam_id', '>', 0)
                    ->orderBy('userteam_slot_slot')
                    ->pluck('userteam_slot_playerteam_id', 'userteam_slot_slot')
                    ->map(fn ($id) => (int) $id)
                    ->all();
                if ($fromWide !== $fromSlots) {
                    $mismatches++;
                    if ($mismatches <= 5) {
                        $issues[] = "slot mismatch for userteam_id={$userteamId}";
                    }
                }
            }
            if ($mismatches > 5) {
                $issues[] = '... and '.($mismatches - 5).' more mismatches';
            }
        }

        $dupSlots = (int) DB::table('ffb_userteam_slot')
            ->select('userteam_slot_userteam_id', 'userteam_slot_slot', DB::raw('COUNT(*) as c'))
            ->groupBy('userteam_slot_userteam_id', 'userteam_slot_slot')
            ->having('c', '>', 1)
            ->count();
        if ($dupSlots > 0) {
            $issues[] = "{$dupSlots} duplicate (userteam, slot) groups";
        }

        return ['ok' => $issues === [], 'issues' => $issues];
    }
}
