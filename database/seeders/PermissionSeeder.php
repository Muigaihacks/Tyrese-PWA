<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Admin Side
            ['name' => 'view inventory', 'group' => 'Admin'],
            ['name' => 'create inventory', 'group' => 'Admin'],
            ['name' => 'edit inventory', 'group' => 'Admin'],
            ['name' => 'delete inventory', 'group' => 'Admin'],
            ['name' => 'view storage', 'group' => 'Admin'],
            ['name' => 'create storage', 'group' => 'Admin'],
            ['name' => 'edit storage', 'group' => 'Admin'],
            ['name' => 'delete storage', 'group' => 'Admin'],
            ['name' => 'view leased units', 'group' => 'Admin'],
            ['name' => 'create leased unit', 'group' => 'Admin'],
            ['name' => 'edit leased unit', 'group' => 'Admin'],
            ['name' => 'delete leased unit', 'group' => 'Admin'],
            ['name' => 'view visits', 'group' => 'Admin'],
            ['name' => 'edit visit', 'group' => 'Admin'],
            ['name' => 'delete visit', 'group' => 'Admin'],
            ['name' => 'view users', 'group' => 'Admin'],
            ['name' => 'create user', 'group' => 'Admin'],
            ['name' => 'edit user', 'group' => 'Admin'],
            ['name' => 'delete user', 'group' => 'Admin'],
            ['name' => 'manage roles', 'group' => 'Admin'],
            ['name' => 'manage permissions', 'group' => 'Admin'],

            // User Side
            ['name' => 'view inventory', 'group' => 'User'],
            ['name' => 'checkout tool', 'group' => 'User'],
            ['name' => 'return tool', 'group' => 'User'],
            ['name' => 'create storage', 'group' => 'User'],
            ['name' => 'view map', 'group' => 'User'],
            ['name' => 'schedule maintenance visit', 'group' => 'User'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], ['group' => $perm['group']]);
        }
    }
} 