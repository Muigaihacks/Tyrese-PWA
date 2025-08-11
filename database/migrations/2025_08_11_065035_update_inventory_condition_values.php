<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update old condition values to new ones
        DB::table('inventories')->where('condition', 'New')->update(['condition' => 'excellent']);
        DB::table('inventories')->where('condition', 'Good')->update(['condition' => 'good']);
        DB::table('inventories')->where('condition', 'Fair')->update(['condition' => 'fair']);
        DB::table('inventories')->where('condition', 'Poor')->update(['condition' => 'poor']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert condition values back to old ones
        DB::table('inventories')->where('condition', 'excellent')->update(['condition' => 'New']);
        DB::table('inventories')->where('condition', 'good')->update(['condition' => 'Good']);
        DB::table('inventories')->where('condition', 'fair')->update(['condition' => 'Fair']);
        DB::table('inventories')->where('condition', 'poor')->update(['condition' => 'Poor']);
    }
};
