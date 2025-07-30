<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Storage;

class StorageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Remove all test data creation
        // Storage::create([...]);
        
        // Keep the structure but don't create test data
        // Data will come from user submissions
    }
}
