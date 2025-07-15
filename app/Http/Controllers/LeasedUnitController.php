<?php

namespace App\Http\Controllers;

use App\Models\LeasedUnit;
use Illuminate\Http\Request;

class LeasedUnitController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(\App\Models\LeasedUnit::class, 'leased_unit');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LeasedUnit $leasedUnit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeasedUnit $leasedUnit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeasedUnit $leasedUnit)
    {
        //
    }
}
