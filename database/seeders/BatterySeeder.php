<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Battery;
use App\Models\LeasedUnit;

class BatterySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing cold storage units
        $units = LeasedUnit::all();
        
        if ($units->isEmpty()) {
            $this->command->info('No cold storage units found. Please create units first.');
            return;
        }

        // Create sample batteries
        $batteryCodes = [
            'BAT001', 'BAT002', 'BAT003', 'BAT004', 'BAT005',
            'BAT006', 'BAT007', 'BAT008', 'BAT009', 'BAT010',
            'BAT011', 'BAT012', 'BAT013', 'BAT014', 'BAT015'
        ];

        $conditions = ['excellent', 'good', 'fair', 'poor'];
        $statuses = ['active', 'active', 'active', 'maintenance'];

        foreach ($batteryCodes as $index => $code) {
            $unit = $units->random(); // Assign to random unit
            
            Battery::create([
                'unique_code' => $code,
                'cold_storage_unit_id' => $unit->id,
                'condition' => $conditions[array_rand($conditions)],
                'status' => $statuses[array_rand($statuses)],
                'notes' => 'Sample battery for testing',
            ]);
        }

        $this->command->info('Sample batteries created successfully!');
    }
} 