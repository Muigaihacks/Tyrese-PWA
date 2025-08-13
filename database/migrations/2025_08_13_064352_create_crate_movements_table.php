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
        Schema::create('crate_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_hub_id')->constrained('hubs')->onDelete('cascade');
            $table->foreignId('to_hub_id')->constrained('hubs')->onDelete('cascade');
            $table->integer('crate_count')->default(0);
            $table->enum('scale_type', ['platform_scale', 'field_scale', 'kitchen_scale', 'crane_scale'])->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('visit_id')->nullable()->constrained('visits')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crate_movements');
    }
};
