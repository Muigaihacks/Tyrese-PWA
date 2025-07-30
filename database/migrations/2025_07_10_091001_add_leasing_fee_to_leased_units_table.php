<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('leased_units', function (Blueprint $table) {
            $table->decimal('leasing_fee', 12, 2)->default(0.00)->after('id'); // Adjust 'after' as needed
        });
    }

    public function down()
    {
        Schema::table('leased_units', function (Blueprint $table) {
            $table->dropColumn('leasing_fee');
        });
    }
}; 