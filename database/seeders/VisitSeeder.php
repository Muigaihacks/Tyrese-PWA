<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visit;

class VisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Remove all test data creation
        // Visit::create([...]);
        
        // Keep the structure but don't create test data
        // Data will come from user submissions
    }
}
