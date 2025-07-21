<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(\App\Models\Visit::class, 'visit', [
            'except' => ['dropdown'],
        ]);
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
        $this->authorize('create', Visit::class);

        $validated = $request->validate([
            'unit_id' => 'required|exists:leased_units,id',
            'location' => 'required|string',
            'scheduled_for' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['scheduled_by'] = auth()->id();

        $visit = Visit::create($validated);

        return response()->json($visit, 201);
    }

    public function dropdown()
    {
        $visits = Visit::with('scheduler')->get();
        $formattedVisits = $visits->map(function ($visit) {
            $schedulerName = $visit->scheduler ? $visit->scheduler->name : 'N/A';
            return [
                'id' => $visit->id,
                'name' => 'Visit to ' . $visit->location . ' on ' . $visit->scheduled_for->format('M d, Y') . ' by ' . $schedulerName,
            ];
        });
        return response()->json($formattedVisits);
    }
}
