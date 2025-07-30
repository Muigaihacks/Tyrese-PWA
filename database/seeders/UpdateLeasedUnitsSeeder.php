<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeasedUnit;

class UpdateLeasedUnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = LeasedUnit::all();
        
        foreach ($units as $unit) {
            $unit->update([
                'ownership_status' => 'SokoFresh',
                'unit_status' => 'leased',
                'unit_type' => 'cold_storage',
                'battery_count' => 2,
                'unit_notes' => 'Updated with new fields',
            ]);
        }

        $this->command->info('Existing leased units updated with new fields!');
    }
} 