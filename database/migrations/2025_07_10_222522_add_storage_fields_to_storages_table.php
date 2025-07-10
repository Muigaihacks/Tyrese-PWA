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
        Schema::table('storages', function (Blueprint $table) {
            if (!Schema::hasColumn('storages', 'client_name')) {
                $table->string('client_name')->nullable();
            }
            if (!Schema::hasColumn('storages', 'phone_number')) {
                $table->string('phone_number')->nullable();
            }
            if (!Schema::hasColumn('storages', 'product_name')) {
                $table->string('product_name')->nullable();
            }
            if (!Schema::hasColumn('storages', 'quantity')) {
                $table->integer('quantity')->nullable();
            }
            if (!Schema::hasColumn('storages', 'date')) {
                $table->date('date')->nullable();
            }
            if (!Schema::hasColumn('storages', 'fee')) {
                $table->decimal('fee', 8, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('storages', function (Blueprint $table) {
            $table->dropColumn(['client_name', 'phone_number', 'product_name', 'quantity', 'date', 'fee']);
        });
    }
};
