<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeasedUnit;

class LeasedUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeasedUnit::create([
            'name' => 'Unit 001',
            'address' => '123 Main St',
            'latitude' => -1.2921,
            'longitude' => 36.8219,
            'lessee_name' => 'John Doe',
            'lessee_contact' => '0712345678',
            'notes' => 'First leased unit',
        ]);
        LeasedUnit::create([
            'name' => 'Unit 002',
            'address' => '456 Main St',
            'latitude' => -1.2921,
            'longitude' => 36.8219,
            'lessee_name' => 'Jane Doe',
            'lessee_contact' => '0712345678',
            'notes' => 'Second leased unit',
        ]);
        LeasedUnit::create([
            'name' => 'Unit 003',
            'address' => '789 Main St',
            'latitude' => -1.2921,
            'longitude' => 36.8219,
            'lessee_name' => 'Jim Doe',
            'lessee_contact' => '0712345678',
            'notes' => 'Third leased unit',
        ]);
    }
}
