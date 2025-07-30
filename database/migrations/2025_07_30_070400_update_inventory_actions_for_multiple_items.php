<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Added DB facade import

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventory_actions', function (Blueprint $table) {
            // Add support for multiple items (JSON field)
            $table->json('items_data')->nullable()->after('action_type'); // Store multiple items data
            
            // Add battery-specific fields (nullable for tools)
            $table->enum('battery_condition_before', ['excellent', 'good', 'fair', 'poor', 'defective'])->nullable()->after('items_data');
            $table->enum('battery_condition_after', ['excellent', 'good', 'fair', 'poor', 'defective'])->nullable()->after('battery_condition_before');
            $table->unsignedBigInteger('from_unit_id')->nullable()->after('battery_condition_after'); // For battery swaps
            $table->unsignedBigInteger('to_unit_id')->nullable()->after('from_unit_id'); // For battery swaps
            
            // Add foreign key constraints for battery movements
            $table->foreign('from_unit_id')->references('id')->on('leased_units')->onDelete('set null');
            $table->foreign('to_unit_id')->references('id')->on('leased_units')->onDelete('set null');
        });
        
        // Update existing action_type values to include new options (PostgreSQL compatible)
        DB::statement("ALTER TABLE inventory_actions ALTER COLUMN action_type TYPE VARCHAR(255)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_actions', function (Blueprint $table) {
            // Drop foreign keys if they exist
            try {
                $table->dropForeign(['from_unit_id']);
            } catch (Exception $e) {
                // Foreign key doesn't exist, ignore
            }
            try {
                $table->dropForeign(['to_unit_id']);
            } catch (Exception $e) {
                // Foreign key doesn't exist, ignore
            }
            
            // Drop columns
            $table->dropColumn(['items_data', 'battery_condition_before', 'battery_condition_after', 'from_unit_id', 'to_unit_id']);
        });
        
        // Revert action_type to original values
        DB::statement("ALTER TABLE inventory_actions ALTER COLUMN action_type TYPE VARCHAR(255)");
    }
};
