<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(\App\Models\Insurance::class, 'insurance');
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
    public function show(Insurance $insurance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Insurance $insurance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insurance $insurance)
    {
        //
    }
}
