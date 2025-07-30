<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Visit;
use Carbon\Carbon;

class UpdateMissedVisits extends Command
{
    protected $signature = 'visits:update-missed';
    protected $description = 'Update missed visits';

    public function handle()
    {
        $missedVisits = Visit::where('status', Visit::STATUS_UPCOMING)
            ->where('scheduled_date', '<', Carbon::now())
            ->update(['status' => Visit::STATUS_MISSED]);

        $this->info("Updated {$missedVisits} visits to missed status.");
    }
}
