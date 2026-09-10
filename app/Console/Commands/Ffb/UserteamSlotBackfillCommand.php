<?php

namespace App\Console\Commands\Ffb;

use App\Services\UserteamSlotBackfillService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('ffb:userteam-slot-backfill {--execute : Persist slot rows (default dry-run)}')]
#[Description('Backfill ffb_userteam_slot from wide userteam_player_id1..11 columns')]
class UserteamSlotBackfillCommand extends Command
{
    public function handle(UserteamSlotBackfillService $backfill): int
    {
        $execute = (bool) $this->option('execute');
        $this->info($execute ? 'Executing slot backfill...' : 'Dry-run slot backfill...');

        try {
            $result = $backfill->backfill($execute);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->table(
            ['Metric', 'Value'],
            collect($result)->map(fn ($v, $k) => [$k, $v])->values()->all(),
        );

        $dir = storage_path('app/userteam-slot');
        File::ensureDirectoryExists($dir);
        $path = $dir.'/backfill-'.now()->format('Ymd-His').($execute ? '-executed' : '-dry').'.json';
        File::put($path, json_encode($result, JSON_PRETTY_PRINT)."\n");
        $this->info('Wrote '.$path);

        if ($execute) {
            $verify = $backfill->verify();
            if (! $verify['ok']) {
                foreach ($verify['issues'] as $issue) {
                    $this->error($issue);
                }

                return self::FAILURE;
            }
            $this->info('Verification passed.');
        }

        return self::SUCCESS;
    }
}
