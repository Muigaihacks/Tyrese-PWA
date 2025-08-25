<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {name} {email} {--role=user} {--password=password123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user with default password';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $role = $this->option('role');
        $password = $this->option('password');

        // Log user creation attempt
        Log::info('User creation attempt started', [
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'password_provided' => !empty($password),
            'timestamp' => now()->toDateTimeString()
        ]);

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            Log::warning('User creation failed - Email already exists', [
                'email' => $email,
                'timestamp' => now()->toDateTimeString()
            ]);
            $this->error("User with email {$email} already exists!");
            return 1;
        }

        try {
            // Create user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role,
                'status' => true, // boolean instead of string
                'email_verified_at' => now(), // Auto-verify email to avoid login issues
            ]);

            // Log successful user creation
            Log::info('User created successfully', [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'email_verified_at' => $user->email_verified_at,
                'password_hash_exists' => !empty($user->password),
                'timestamp' => now()->toDateTimeString()
            ]);

            $this->info("User created successfully!");
            $this->info("Name: {$user->name}");
            $this->info("Email: {$user->email}");
            $this->info("Role: {$user->role}");
            $this->info("Password: {$password}");
            $this->info("Status: " . ($user->status ? 'Active' : 'Inactive'));
            $this->info("Email Verified: " . ($user->email_verified_at ? 'Yes' : 'No'));
            $this->info("Login URL: http://localhost:8000/login");
            $this->info("Note: User should change password after first login for security.");

            return 0;

        } catch (\Exception $e) {
            // Log user creation error
            Log::error('User creation failed', [
                'name' => $name,
                'email' => $email,
                'role' => $role,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'timestamp' => now()->toDateTimeString()
            ]);

            $this->error("Failed to create user: " . $e->getMessage());
            return 1;
        }
    }
}
