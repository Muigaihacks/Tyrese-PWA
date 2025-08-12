<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateSimpleAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a simple admin user using existing user:create command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $this->info("Creating admin user with email: {$email}");

        // Use the existing user:create command
        $this->call('user:create', [
            'name' => 'Admin User',
            'email' => $email,
            '--role' => 'admin',
            '--password' => $password,
        ]);

        $this->info('✅ Admin user created successfully!');
        $this->info('You can now log in to the admin panel.');

        return 0;
    }
}
