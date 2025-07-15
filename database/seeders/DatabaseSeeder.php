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
            PermissionSeeder::class,
        ]);
    }
}
