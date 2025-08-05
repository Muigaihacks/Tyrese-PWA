<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class LabourerPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions for casual labourer functionality
        $permissions = [
            'view_casual_labourer_dashboard',
            'clock_in',
            'clock_out',
            'update_labourer_profile',
            'view_attendance_history',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Get the labourer role
        $labourerRole = Role::where('name', 'labourer')->first();

        if ($labourerRole) {
            // Assign all labourer permissions to the labourer role
            $labourerRole->givePermissionTo($permissions);
        }

        // Also give basic user permissions to labourer
        $basicPermissions = [
            'view_inventory',
            'view_storage',
            'view_map',
            'checkout tool',
            'return tool',
        ];

        foreach ($basicPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        if ($labourerRole) {
            $labourerRole->givePermissionTo($basicPermissions);
        }
    }
}
