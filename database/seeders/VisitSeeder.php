<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visit;

class VisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample visits
        $visits = [
            [
                'unit_id' => 1, // Unit 001
                'scheduled_by' => 1, // Admin user
                'scheduled_for' => now()->addDays(2),
                'status' => 'upcoming',
                'location' => 'Nairobi',
                'notes' => 'Regular maintenance visit to Nairobi cold storage unit',
            ],
            [
                'unit_id' => 2, // Unit 002
                'scheduled_by' => 1, // Admin user
                'scheduled_for' => now()->addHours(6),
                'status' => 'upcoming',
                'location' => 'Mombasa',
                'notes' => 'Urgent repair needed for compressor system',
            ],
            [
                'unit_id' => 3, // Unit 003
                'scheduled_by' => 1, // Admin user
                'scheduled_for' => now()->addDays(5),
                'status' => 'upcoming',
                'location' => 'Kisumu',
                'notes' => 'Monthly routine inspection and maintenance',
            ],
            [
                'unit_id' => 1, // Unit 001
                'scheduled_by' => 1, // Admin user
                'scheduled_for' => now()->subDays(1),
                'status' => 'completed',
                'location' => 'Nairobi',
                'notes' => 'Battery swap completed successfully',
            ],
            [
                'unit_id' => 2, // Unit 002
                'scheduled_by' => 1, // Admin user
                'scheduled_for' => now()->subDays(3),
                'status' => 'completed',
                'location' => 'Mombasa',
                'notes' => 'Control panel upgrade completed',
            ],
        ];

        foreach ($visits as $visitData) {
            Visit::create($visitData);
        }

        $this->command->info('Sample visits created successfully!');
    }
}
