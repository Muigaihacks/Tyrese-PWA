<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample tools and spare parts
        $items = [
            // Tools
            [
                'product' => 'Screwdriver Set',
                'item_type' => 'tool',
                'quantity' => 5,
                'condition' => 'good',
                'stock_level' => 'high',
                'date_added' => now(),
            ],
            [
                'product' => 'Wrench Set',
                'item_type' => 'tool',
                'quantity' => 3,
                'condition' => 'excellent',
                'stock_level' => 'medium',
                'date_added' => now(),
            ],
            [
                'product' => 'Hammer',
                'item_type' => 'tool',
                'quantity' => 2,
                'condition' => 'good',
                'stock_level' => 'low',
                'date_added' => now(),
            ],
            [
                'product' => 'Pliers',
                'item_type' => 'tool',
                'quantity' => 4,
                'condition' => 'fair',
                'stock_level' => 'medium',
                'date_added' => now(),
            ],
            [
                'product' => 'Drill Machine',
                'item_type' => 'tool',
                'quantity' => 1,
                'condition' => 'excellent',
                'stock_level' => 'low',
                'date_added' => now(),
            ],
            // Spare Parts
            [
                'product' => 'Compressor Belt',
                'item_type' => 'spare_part',
                'quantity' => 10,
                'condition' => 'good',
                'stock_level' => 'high',
                'date_added' => now(),
            ],
            [
                'product' => 'Thermostat Sensor',
                'item_type' => 'spare_part',
                'quantity' => 8,
                'condition' => 'excellent',
                'stock_level' => 'high',
                'date_added' => now(),
            ],
            [
                'product' => 'Fan Motor',
                'item_type' => 'spare_part',
                'quantity' => 3,
                'condition' => 'good',
                'stock_level' => 'medium',
                'date_added' => now(),
            ],
            [
                'product' => 'Refrigerant Valve',
                'item_type' => 'spare_part',
                'quantity' => 6,
                'condition' => 'fair',
                'stock_level' => 'medium',
                'date_added' => now(),
            ],
            [
                'product' => 'Control Panel',
                'item_type' => 'spare_part',
                'quantity' => 2,
                'condition' => 'excellent',
                'stock_level' => 'low',
                'date_added' => now(),
            ],
        ];

        foreach ($items as $itemData) {
            Inventory::create($itemData);
        }

        $this->command->info('Sample inventory items created successfully!');
    }
}
