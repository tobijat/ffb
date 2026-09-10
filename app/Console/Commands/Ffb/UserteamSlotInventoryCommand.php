<?php

namespace App\Console\Commands\Ffb;

use App\Services\UserteamSlotBackfillService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('ffb:userteam-slot-inventory')]
#[Description('Inventory ffb_userteam wide slots vs ffb_userteam_slot')]
class UserteamSlotInventoryCommand extends Command
{
    public function handle(UserteamSlotBackfillService $backfill): int
    {
        $report = $backfill->inventory();
        $this->table(['Metric', 'Value'], collect($report)->map(fn ($v, $k) => [$k, $v])->values()->all());

        $dir = storage_path('app/userteam-slot');
        File::ensureDirectoryExists($dir);
        $path = $dir.'/inventory-'.now()->format('Ymd-His').'.json';
        File::put($path, json_encode($report, JSON_PRETTY_PRINT)."\n");
        $this->info('Wrote '.$path);

        return self::SUCCESS;
    }
}
