<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            LeasedUnitSeeder::class,
            VisitSeeder::class,
            InventoryActionSeeder::class,
            // Add any other seeders you have
        ]);
    }
}
