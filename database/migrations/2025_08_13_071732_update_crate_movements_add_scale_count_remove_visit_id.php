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
        Schema::table('crate_movements', function (Blueprint $table) {
            $table->integer('scale_count')->nullable()->after('scale_type');
            $table->dropForeign(['visit_id']);
            $table->dropColumn('visit_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crate_movements', function (Blueprint $table) {
            $table->foreignId('visit_id')->nullable()->constrained('visits')->onDelete('set null')->after('user_id');
            $table->dropColumn('scale_count');
        });
    }
};
