<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeasedUnit;

class LeasedUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample cold storage units
        $units = [
            [
                'name' => 'Unit 001',
                'lessee_name' => 'SokoFresh',
                'lessee_contact' => '+254700000001',
                'address' => 'Nairobi',
                'latitude' => -1.2921,
                'longitude' => 36.8219,
                'ownership_status' => 'SokoFresh',
                'unit_status' => 'leased',
                'unit_type' => 'cold_storage',
                'battery_count' => 2,
                'unit_notes' => 'Main cold storage unit',
            ],
            [
                'name' => 'Unit 002',
                'lessee_name' => 'SokoFresh LLP',
                'lessee_contact' => '+254700000002',
                'address' => 'Mombasa',
                'latitude' => -4.0435,
                'longitude' => 39.6682,
                'ownership_status' => 'SokoFresh LLP',
                'unit_status' => 'lease-to-own',
                'unit_type' => 'NTU',
                'battery_count' => 2,
                'unit_notes' => 'Heavy-duty freezer unit',
            ],
            [
                'name' => 'Unit 003',
                'lessee_name' => 'SokoFresh',
                'lessee_contact' => '+254700000003',
                'address' => 'Kisumu',
                'latitude' => -0.1022,
                'longitude' => 34.7617,
                'ownership_status' => 'SokoFresh',
                'unit_status' => 'outright_purchase',
                'unit_type' => 'cold_storage',
                'battery_count' => 2,
                'unit_notes' => 'Regional storage unit',
            ],
        ];

        foreach ($units as $unitData) {
            LeasedUnit::create($unitData);
        }

        $this->command->info('Sample cold storage units created successfully!');
    }
}
