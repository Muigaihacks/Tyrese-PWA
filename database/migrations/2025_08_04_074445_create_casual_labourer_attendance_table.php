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
        Schema::create('casual_labourer_attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('casual_labourer_id');
            $table->date('work_date');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->string('job_description')->nullable();
            $table->text('notes')->nullable();
            
            // Calculated fields
            $table->integer('total_hours')->nullable(); // in minutes
            $table->decimal('total_hours_decimal', 4, 2)->nullable(); // in hours
            
            $table->foreign('casual_labourer_id')->references('id')->on('casual_labourers')->onDelete('cascade');
            $table->unique(['casual_labourer_id', 'work_date']); // One record per labourer per day
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casual_labourer_attendance');
    }
};
