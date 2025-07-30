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
        // Remove all test data creation
        // LeasedUnit::create([...]);
        
        // Keep the structure but don't create test data
        // Data will come from user submissions
    }
}
