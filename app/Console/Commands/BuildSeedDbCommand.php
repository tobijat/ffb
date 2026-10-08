<?php

namespace App\Console\Commands;

use App\Services\SeedDb\SeedDbBuilder;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Throwable;

#[Signature('ffb:build-seed-db
                            {--leagues=38,39 : Source league IDs to keep}
                            {--users=tobijat,Ronaldo,Haaland : Nicknames to keep}
                            {--password=password : Dummy password for seed users}
                            {--dump=database/seed/ffb_seed.sql : SQL dump output path}
                            {--force : Required confirmation}')]
#[Description('Build a stripped FFB seed SQL dump keeping original IDs (does not mutate the source DB)')]
class BuildSeedDbCommand extends Command
{
    public function handle(SeedDbBuilder $builder): int
    {
        if (! $this->option('force')) {
            $this->error('Refusing to run without --force.');

            return self::FAILURE;
        }

        $leagues = array_values(array_filter(array_map(
            static fn (string $v): int => (int) trim($v),
            explode(',', (string) $this->option('leagues')),
        )));
        $users = array_values(array_filter(array_map(
            static fn (string $v): string => trim($v),
            explode(',', (string) $this->option('users')),
        )));
        $dump = base_path((string) $this->option('dump'));

        $this->line('Source DB: '.(string) config('database.connections.mysql.database'));
        $this->line('Leagues: '.implode(', ', $leagues));
        $this->line('Users: '.implode(', ', $users));
        $this->line('Dump: '.$dump);

        try {
            $result = $builder->buildDump(
                $leagues,
                $users,
                (string) $this->option('password'),
                $dump,
                fn (string $msg) => $this->line($msg),
            );
        } catch (Throwable $e) {
            $this->error($e->getMessage());
            if ($this->output->isVerbose()) {
                $this->error($e->getTraceAsString());
            }

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Seed dump ready: '.$result['dump']);
        $this->info('Size: '.$this->formatBytes((int) filesize($result['dump'])));
        $this->line('Logins: tobijat / Ronaldo / Haaland — password: '.(string) $this->option('password'));
        $this->line('League IDs preserved: '.implode(', ', $result['league_ids']));

        return self::SUCCESS;
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        $n = (float) $bytes;
        while ($n >= 1024 && $i < count($units) - 1) {
            $n /= 1024;
            $i++;
        }

        return sprintf('%.2f %s', $n, $units[$i]);
    }
}
