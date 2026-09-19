<?php

namespace App\Console\Commands;

use App\Services\ExtremeTeamService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ffb:backfill-extremeteams')]
#[Description('Compute and store top/flop extreme teams for past rounds of visible leagues')]
class BackfillExtremeteamsCommand extends Command
{
    public function handle(ExtremeTeamService $extremeTeams): int
    {
        $this->info('Backfilling extreme teams for visible leagues…');

        $result = $extremeTeams->backfillVisibleLeagues();

        $this->info(sprintf(
            'Leagues: %d · Matchrounds: %d · Stored: %d · Skipped: %d',
            $result['leagues'],
            $result['matchrounds'],
            $result['stored'],
            $result['skipped'],
        ));

        if ($this->output->isVerbose()) {
            foreach ($result['details'] as $line) {
                $this->line($line);
            }
        }

        return self::SUCCESS;
    }
}
