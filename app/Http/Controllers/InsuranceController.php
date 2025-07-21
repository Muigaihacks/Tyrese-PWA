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
        $this->authorize('create', Insurance::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_number' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'start_date' => 'required|date',
            'site' => 'required|string|max:255',
            'insurance_copy' => 'required|file|mimes:pdf,jpg,png',
            'cover_expiry' => 'required|date',
        ]);

        if ($request->hasFile('insurance_copy')) {
            $validated['insurance_copy'] = $request->file('insurance_copy')->store('insurance_copies', 'public');
        }

        $insurance = Insurance::create($validated);

        return response()->json($insurance, 201);
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
