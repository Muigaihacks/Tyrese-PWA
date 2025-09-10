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
        Schema::table('inventories', function (Blueprint $table) {
            // Add notes field
            $table->text('notes')->nullable()->after('quantity');
        });

        // Update the enum to include 'asset' - PostgreSQL compatible
        DB::statement("ALTER TABLE inventories DROP CONSTRAINT IF EXISTS inventories_item_type_check");
        DB::statement("ALTER TABLE inventories ALTER COLUMN item_type TYPE VARCHAR(20)");
        DB::statement("ALTER TABLE inventories ADD CONSTRAINT inventories_item_type_check CHECK (item_type IN ('tool', 'spare_part', 'asset'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            // Remove notes field
            $table->dropColumn('notes');
        });

        // Revert enum back to original values
        DB::statement("ALTER TABLE inventories DROP CONSTRAINT IF EXISTS inventories_item_type_check");
        DB::statement("ALTER TABLE inventories ADD CONSTRAINT inventories_item_type_check CHECK (item_type IN ('tool', 'spare_part'))");
    }
};