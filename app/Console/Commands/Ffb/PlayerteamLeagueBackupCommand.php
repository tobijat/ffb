<?php

namespace App\Console\Commands\Ffb;

use App\Services\PlayerteamLeagueInventoryService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

#[Signature('ffb:playerteam-league-backup {--dir= : Output directory (default storage/app/playerteam-league/backups)}')]
#[Description('Phase 0 mysqldump backup of tables touched by playerteam league scoping')]
class PlayerteamLeagueBackupCommand extends Command
{
    public function handle(PlayerteamLeagueInventoryService $inventory): int
    {
        $connection = (string) config('database.default');
        $config = config('database.connections.'.$connection);
        if (! is_array($config) || ($config['driver'] ?? '') !== 'mysql') {
            $this->error('Backup currently supports the mysql driver only (got '.$connection.').');

            return self::FAILURE;
        }

        $dir = $this->option('dir');
        if (! is_string($dir) || $dir === '') {
            $dir = storage_path('app/playerteam-league/backups');
        }
        File::ensureDirectoryExists($dir);

        $stamp = now()->format('Ymd-His');
        $file = rtrim($dir, '\\/').DIRECTORY_SEPARATOR.'ffb-playerteam-league-'.$stamp.'.sql';
        $tables = $inventory->backupTableNames();

        $host = (string) ($config['host'] ?? '127.0.0.1');
        $port = (string) ($config['port'] ?? '3306');
        $database = (string) ($config['database'] ?? '');
        $username = (string) ($config['username'] ?? '');
        $password = (string) ($config['password'] ?? '');

        if ($database === '' || $username === '') {
            $this->error('Database name/username missing from config.');

            return self::FAILURE;
        }

        // mysqldump db_name table1 table2 ...
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

        $this->info('Dumping tables: '.implode(', ', $tables));
        $process->run();

        if (! $process->isSuccessful()) {
            $this->error('mysqldump failed.');
            $this->line(trim($process->getErrorOutput()) !== '' ? $process->getErrorOutput() : $process->getOutput());

            return self::FAILURE;
        }

        File::put($file, $process->getOutput());
        $bytes = File::size($file);
        $this->info(sprintf('Backup written: %s (%s bytes)', $file, number_format($bytes)));

        $meta = [
            'created_at' => now()->toIso8601String(),
            'connection' => $connection,
            'database' => $database,
            'tables' => $tables,
            'file' => $file,
            'bytes' => $bytes,
        ];
        File::put(
            rtrim($dir, '\\/').DIRECTORY_SEPARATOR.'ffb-playerteam-league-'.$stamp.'.json',
            json_encode($meta, JSON_PRETTY_PRINT)."\n",
        );

        return self::SUCCESS;
    }
}
