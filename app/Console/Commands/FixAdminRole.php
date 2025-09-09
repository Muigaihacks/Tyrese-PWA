<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class FixAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:admin-role {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix admin role assignment for a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        // Find the user
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        $this->info("Found user: {$user->name} ({$user->email})");
        $this->info("Current role: {$user->role}");

        // Check if admin role exists
        $adminRole = Role::where('name', 'admin')->first();
        if (!$adminRole) {
            $this->info("Creating admin role...");
            $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        }

        // Check if user already has admin role
        if ($user->hasRole('admin')) {
            $this->info("User already has admin role!");
            $this->info("All roles: " . implode(', ', $user->roles->pluck('name')->toArray()));
            return 0;
        }

        // Assign admin role
        $this->info("Assigning admin role...");
        $user->assignRole($adminRole);

        // Update user's role field
        $user->update(['role' => 'admin']);

        $this->info("✅ Admin role assigned successfully!");
        $this->info("User roles: " . implode(', ', $user->roles->pluck('name')->toArray()));

        return 0;
    }
}
