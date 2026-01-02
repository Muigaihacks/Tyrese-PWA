<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateAdminFromEnv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create-from-env';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an admin user from environment variables (for Render free tier deployment)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get credentials from environment variables with defaults
        $adminName = env('ADMIN_NAME', 'Admin');
        $adminEmail = env('ADMIN_EMAIL', 'admin@demo.com');
        $adminPassword = env('ADMIN_PASSWORD', 'demo123');
        $adminRole = env('ADMIN_ROLE', 'admin');

        // Ensure super_admin role exists first
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'sanctum']);
        
        // Check if user already exists
        $existingUser = User::where('email', $adminEmail)->first();
        if ($existingUser) {
            // Always update existing user to have super_admin role
            $superAdminRole = Role::where('name', 'super_admin')->where('guard_name', 'web')->first();
            if ($superAdminRole) {
                // Remove all existing roles
                $existingUser->roles()->detach();
                // Assign super_admin role
                $existingUser->assignRole($superAdminRole);
                $existingUser->update(['role' => 'super_admin']);
                $existingUser->refresh();
                $this->info("✅ Updated existing user with super_admin role!");
                $this->info("User now has roles: " . implode(', ', $existingUser->roles->pluck('name')->toArray()));
            }
            return 0;
        }

        try {
            // Ensure roles exist (run role seeder if needed)
            try {
                $this->call('db:seed', ['--class' => 'RoleSeeder', '--force' => true]);
            } catch (\Exception $e) {
                // Role seeder might fail if roles already exist, that's ok
                $this->warn("Note: Could not run RoleSeeder (roles may already exist): " . $e->getMessage());
            }

            // Ensure super_admin role exists (required for Filament full access)
            Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
            Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'sanctum']);

            // Create the admin user
            $user = User::create([
                'name' => $adminName,
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'role' => 'super_admin', // Use super_admin for full Filament access
                'status' => true,
                'email_verified_at' => now(),
            ]);

            // Assign super_admin role (required for Filament permissions)
            $superAdminRole = Role::where('name', 'super_admin')->where('guard_name', 'web')->first();
            if ($superAdminRole) {
                $user->assignRole($superAdminRole);
            }

            $this->info("✅ Successfully created admin user!");
            $this->info("Name: {$user->name}");
            $this->info("Email: {$user->email}");
            $this->info("Role: {$user->role}");
            $this->info("Password: {$adminPassword}");
            $this->info("");
            $this->info("🔐 You can now log in to:");
            $this->info("   Admin Panel: /admin");
            $this->info("   User Interface: /login");

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Failed to create admin user: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return 1;
        }
    }
}

