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
        Schema::table('leased_units', function (Blueprint $table) {
            // Add new fields for cold storage units
            $table->enum('ownership_status', ['SokoFresh', 'SokoFresh LLP'])->default('SokoFresh')->after('lessee_contact');
            $table->enum('unit_status', ['leased', 'lease-to-own', 'outright_purchase'])->default('leased')->after('ownership_status');
            $table->enum('unit_type', ['cold_storage', 'NTU'])->default('cold_storage')->after('unit_status');
            $table->integer('battery_count')->default(2)->after('unit_type'); // Number of batteries in unit
            $table->text('unit_notes')->nullable()->after('battery_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leased_units', function (Blueprint $table) {
            $table->dropColumn(['ownership_status', 'unit_status', 'unit_type', 'battery_count', 'unit_notes']);
        });
    }
};
