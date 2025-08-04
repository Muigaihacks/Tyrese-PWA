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
        Schema::create('casual_labourers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gender', ['M', 'F']);
            $table->enum('age_group', ['18-35', '36+ YEARS']);
            $table->string('phone_number');
            $table->string('id_number');
            
            // Emergency contact
            $table->string('next_of_kin_name');
            $table->string('next_of_kin_phone');
            
            // Safety compliance
            $table->boolean('health_declaration')->default(false);
            $table->boolean('skills_confirmation')->default(false);
            $table->boolean('ppe_provided')->default(false);
            $table->boolean('safety_briefing')->default(false);
            $table->boolean('tool_safety_agreement')->default(false);
            $table->boolean('accident_cover_enrolled')->default(false);
            $table->boolean('data_consent')->default(false);
            
            // Account status
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->date('contract_start_date');
            $table->date('contract_end_date')->nullable();
            
            // User account link
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casual_labourers');
    }
};
