<?php

namespace App\Console\Commands\Ffb;

use App\Services\UserteamSlotBackfillService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ffb:userteam-slot-backup {--dir= : Output directory}')]
#[Description('mysqldump backup of ffb_userteam (+ slots if present)')]
class UserteamSlotBackupCommand extends Command
{
    public function handle(UserteamSlotBackfillService $backfill): int
    {
        try {
            $dir = $this->option('dir');
            $result = $backfill->backup(is_string($dir) && $dir !== '' ? $dir : null);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf('Backup written: %s (%s bytes)', $result['path'], number_format($result['bytes'])));

        return self::SUCCESS;
    }
}
