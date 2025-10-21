<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearDatabaseSeeder extends Seeder
{
    /**
     * Clear all data from the database (except migrations).
     */
    public function run(): void
    {
        if ($this->command) {
            $this->command->warn('⚠️  Clearing all database data...');
        }
        
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // List of tables to clear (in order due to relationships)
        $tables = [
            'crate_movements',
            'casual_labourer_attendance',
            'casual_labourers',
            'inventory_actions',
            'battery_movements',
            'batteries',
            'visits',
            'cold_storage_units',
            'inventory_locations',
            'inventories',
            'hubs',
            'storages',
            'leased_units',
            'locations',
            'maps',
            'insurances',
            'password_change_logs',
            'personal_access_tokens',
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
            'permissions',
            'roles',
            'users',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                if ($this->command) {
                    $this->command->info("  ✓ Cleared: $table");
                }
            }
        }

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        if ($this->command) {
            $this->command->info('✅ Database cleared successfully!');
        }
    }
}

