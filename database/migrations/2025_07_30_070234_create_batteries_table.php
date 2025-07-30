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
        Schema::create('batteries', function (Blueprint $table) {
            $table->id();
            $table->string('unique_code')->unique(); // Unique battery identifier
            $table->unsignedBigInteger('cold_storage_unit_id'); // Links to cold storage unit
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor', 'defective'])->default('good');
            $table->enum('status', ['active', 'inactive', 'maintenance', 'retired'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('cold_storage_unit_id')->references('id')->on('leased_units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batteries');
    }
};
