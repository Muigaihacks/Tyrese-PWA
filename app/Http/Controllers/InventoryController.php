<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\InventoryAction;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Inventory::class, 'inventory');
    }

    // Standard resource methods

    public function index()
    {
        // List all inventories (policy: viewAny)
    }

    public function store(Request $request)
    {
        // Create new inventory (policy: create)
    }

    public function show(Inventory $inventory)
    {
        // Show a single inventory (policy: view)
    }

    public function update(Request $request, Inventory $inventory)
    {
        // Update inventory (policy: update)
    }

    public function destroy(Inventory $inventory)
    {
        // Delete inventory (policy: delete)
    }

    // --- Custom actions below ---

    public function checkout(Request $request)
    {
        $this->authorize('checkout', Inventory::class);
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'location_id' => 'required|exists:locations,id',
            'visit_id' => 'required|exists:visits,id',
            'quantity' => 'required|integer|min:1',
            'condition_before' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        $validated['user_id'] = Auth::id();
        $validated['action_type'] = 'checkout';
        $validated['condition_after'] = null;
        $action = InventoryAction::create($validated);
        return response()->json($action, 201);
    }

    public function return(Request $request)
    {
        $this->authorize('return', Inventory::class);
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'location_id' => 'required|exists:locations,id',
            'visit_id' => 'required|exists:visits,id',
            'quantity' => 'required|integer|min:1',
            'condition_before' => 'nullable|string',
            'condition_after' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        $validated['user_id'] = Auth::id();
        $validated['action_type'] = 'return';
        $action = InventoryAction::create($validated);
        return response()->json($action, 201);
    }

    public function locationsDropdown(Request $request)
    {
        $locations = Location::orderBy('name')->get(['id', 'name']);
        return response()->json($locations);
    }

    public function listDropdown(Request $request)
    {
        die('REACHED');
        $inventories = \App\Models\Inventory::orderBy('name')->get(['id', 'name']);
        return response()->json($inventories);
    }
}
