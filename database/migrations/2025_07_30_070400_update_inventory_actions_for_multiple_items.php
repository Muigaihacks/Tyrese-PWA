<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventory_actions', function (Blueprint $table) {
            // Update existing action_type column to include new values
            $table->enum('action_type', ['checkout', 'return', 'tools', 'batteries'])->change();
            
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_actions', function (Blueprint $table) {
            $table->dropForeign(['from_unit_id', 'to_unit_id']);
            $table->dropColumn(['items_data', 'battery_condition_before', 'battery_condition_after', 'from_unit_id', 'to_unit_id']);
            
            // Revert action_type to original values
            $table->enum('action_type', ['checkout', 'return'])->change();
        });
    }
};
