<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Storage;

class StorageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::create([
            'client_name' => 'Jane Doe',
            'phone_number' => '0712345678',
            'product_name' => 'Mangoes',
            'quantity' => 100,
            'date' => now()->toDateString(),
            'fee' => 500.00,
        ]);
        // Add more records as needed
    }
}
