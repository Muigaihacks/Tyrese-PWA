<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    public function dropdown()
    {
        // Return all locations as an array of {id, name}
        return response()->json(Location::select('id', 'name')->get());
    }
}
