<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryAction;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\User;
use App\Models\Visit;

class InventoryActionSeeder extends Seeder
{
    public function run(): void
    {
        $inventory = \App\Models\Inventory::first();
        $location = \App\Models\Location::first();
        $user = \App\Models\User::first();
        $visit = \App\Models\Visit::first();

        InventoryAction::create([
            'inventory_id' => $inventory ? $inventory->id : 1,
            'location_id' => $location ? $location->id : 1,
            'user_id' => $user ? $user->id : 1,
            'visit_id' => $visit ? $visit->id : null,
            'action_type' => 'checkout',
            'quantity' => 2,
            'condition_before' => 'Good',
            'condition_after' => null,
            'notes' => 'Checked out for maintenance',
        ]);

        InventoryAction::create([
            'inventory_id' => $inventory ? $inventory->id : 1,
            'location_id' => $location ? $location->id : 1,
            'user_id' => $user ? $user->id : 1,
            'visit_id' => $visit ? $visit->id : null,
            'action_type' => 'return',
            'quantity' => 2,
            'condition_before' => 'Good',
            'condition_after' => 'Good',
            'notes' => 'Returned after maintenance',
        ]);
    }
}
