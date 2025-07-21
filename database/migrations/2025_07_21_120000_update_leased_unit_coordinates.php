<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Update coordinates for mock leased units
        \DB::table('leased_units')->where('id', 1)->update([
            'latitude' => -1.286389,
            'longitude' => 36.817223,
        ]);
        \DB::table('leased_units')->where('id', 2)->update([
            'latitude' => 0.514277,
            'longitude' => 35.269779,
        ]);
        \DB::table('leased_units')->where('id', 3)->update([
            'latitude' => -4.043477,
            'longitude' => 39.668206,
        ]);
    }

    public function down()
    {
        // Optionally revert to 0,0 or null
        \DB::table('leased_units')->whereIn('id', [1,2,3])->update([
            'latitude' => 0,
            'longitude' => 0,
        ]);
    }
}; 