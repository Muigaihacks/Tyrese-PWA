<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(\App\Models\Visit::class, 'visit');
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
        $validated = $request->validate([
            'scheduled_for' => 'required|date',
            'scheduled_by' => 'required|string',
            'location' => 'required|string',
            // add other fields as needed
        ]);

        $visit = Visit::create($validated);
        return response()->json($visit, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Visit $visit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visit $visit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visit $visit)
    {
        //
    }

    public function schedule(Request $request)
    {
        $this->authorize('schedule', \App\Models\Visit::class);
        $validated = $request->validate([
            'scheduled_for' => 'required|date',
            'scheduled_by' => 'required|string',
            'location' => 'required|string',
            // add other fields as needed
        ]);

        $visit = Visit::create($validated);
        return response()->json($visit, 201);
    }

    public function dropdownList(Request $request)
    {
        $visits = \App\Models\Visit::with('scheduler')
            ->orderBy('scheduled_for')
            ->get()
            ->map(function ($visit) {
                return [
                    'id' => $visit->id,
                    'location' => $visit->location,
                    'scheduled_by' => $visit->scheduler ? $visit->scheduler->name : '',
                    'notes' => $visit->notes,
                ];
            });

        return response()->json($visits);
    }
}
