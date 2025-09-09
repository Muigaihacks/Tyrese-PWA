<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\LeasedUnit;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create KIBIKU leased unit if it doesn't exist
        $kibikuUnit = LeasedUnit::where('name', 'KIBIKU')->first();
        
        if (!$kibikuUnit) {
            LeasedUnit::create([
                'name' => 'KIBIKU',
                'lessee_name' => 'SokoFresh',
                'lessee_contact' => '+254700000000',
                'address' => 'Kibiku Hub Location',
                'latitude' => -1.2921,
                'longitude' => 36.8219,
                'ownership_status' => 'SokoFresh',
                'unit_status' => 'leased',
                'unit_type' => 'cold_storage',
                'battery_count' => 0,
                'unit_notes' => 'KIBIKU hub location for batteries not assigned to specific units',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove KIBIKU unit
        LeasedUnit::where('name', 'KIBIKU')->delete();
    }
};