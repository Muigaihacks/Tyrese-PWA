<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visit;
use App\Models\LeasedUnit;
use App\Models\User;

class VisitSeeder extends Seeder
{
    public function run(): void
    {
        $unit = LeasedUnit::first();
        $user = \App\Models\User::first();

        Visit::create([
            'unit_id' => $unit ? $unit->id : 1,
            'scheduled_by' => $user ? $user->id : 1,
            'scheduled_for' => now()->addDays(3)->toDateString(),
            'status' => 'scheduled',
            'notes' => 'Routine maintenance visit',
        ]);
        // Add more visits as needed
    }
}
