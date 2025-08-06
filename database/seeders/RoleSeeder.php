<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Only the roles that are actually needed and used
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);
        Role::firstOrCreate(['name' => 'labourer']);
        Role::firstOrCreate(['name' => 'security']);
        Role::firstOrCreate(['name' => 'manager']);
    }
}
