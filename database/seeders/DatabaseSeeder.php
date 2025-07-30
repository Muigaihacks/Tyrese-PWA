<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            LeasedUnitSeeder::class, // Create cold storage units first
            StorageSeeder::class,
            VisitSeeder::class,
            InventorySeeder::class, // Create inventory items
            InventoryActionSeeder::class,
            BatterySeeder::class, // Batteries need units to exist
            UpdateLeasedUnitsSeeder::class, // Update units with new fields
        ]);
    }
}
