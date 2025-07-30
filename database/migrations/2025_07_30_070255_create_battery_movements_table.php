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
        Schema::create('battery_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('battery_id');
            $table->unsignedBigInteger('user_id'); // Who performed the movement
            $table->unsignedBigInteger('from_unit_id')->nullable(); // Source unit
            $table->unsignedBigInteger('to_unit_id')->nullable(); // Destination unit
            $table->enum('movement_type', ['checkout', 'return', 'swap']);
            $table->enum('condition_before', ['excellent', 'good', 'fair', 'poor', 'defective'])->nullable();
            $table->enum('condition_after', ['excellent', 'good', 'fair', 'poor', 'defective'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('battery_id')->references('id')->on('batteries')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('from_unit_id')->references('id')->on('leased_units')->onDelete('set null');
            $table->foreign('to_unit_id')->references('id')->on('leased_units')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('battery_movements');
    }
};
