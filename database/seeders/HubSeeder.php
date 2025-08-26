<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hub;

class HubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hubs = [
            [
                'name' => 'Muranga',
                'location' => 'Muranga',
                'crate_count' => 0,
                'scale_count' => 0,
                'is_kibiku' => false,
            ],
            [
                'name' => 'Kiganjo',
                'location' => 'Kiganjo',
                'crate_count' => 0,
                'scale_count' => 0,
                'is_kibiku' => false,
            ],
            [
                'name' => 'Homa Bay',
                'location' => 'Homa Bay',
                'crate_count' => 0,
                'scale_count' => 0,
                'is_kibiku' => false,
            ],
            [
                'name' => 'Migori',
                'location' => 'Migori',
                'crate_count' => 0,
                'scale_count' => 0,
                'is_kibiku' => false,
            ],
            [
                'name' => 'Nakuru',
                'location' => 'Nakuru',
                'crate_count' => 0,
                'scale_count' => 0,
                'is_kibiku' => false,
            ],
            [
                'name' => 'Parkhouse',
                'location' => 'Thika',
                'crate_count' => 0,
                'scale_count' => 0,
                'is_kibiku' => false,
            ],
            [
                'name' => 'Kibiku',
                'location' => 'Kibiku',
                'crate_count' => 0,
                'scale_count' => 0,
                'is_kibiku' => true,
            ],
            [
                'name' => 'CSU',
                'location' => 'Cold Storage Unit',
                'crate_count' => 0,
                'scale_count' => 0,
                'is_kibiku' => false,
            ],
        ];

        foreach ($hubs as $hub) {
            Hub::create($hub);
        }
    }
}
