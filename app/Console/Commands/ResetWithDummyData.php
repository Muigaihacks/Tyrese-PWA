<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\ClearDatabaseSeeder;
use Database\Seeders\DummyDataSeeder;

class ResetWithDummyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:demo {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear database and populate with dummy data for demo/screenshots';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Confirmation prompt
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  This will DELETE ALL DATA and replace with dummy data. Continue?', false)) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->newLine();
        $this->info('🔄 Starting database reset with dummy data...');
        $this->newLine();

        // Clear all existing data
        $this->call(ClearDatabaseSeeder::class);
        
        $this->newLine();
        
        // Seed with dummy data
        $this->call(DummyDataSeeder::class);

        $this->newLine();
        $this->info('✅ Database reset complete!');
        $this->newLine();
        $this->info('📸 Your system is now ready for screenshots and demo videos.');
        $this->info('🔐 Login with: admin@demo.com / demo123');
        
        return 0;
    }
}
