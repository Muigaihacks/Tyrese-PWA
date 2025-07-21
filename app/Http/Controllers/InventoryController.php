<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\InventoryAction;
use Illuminate\Support\Facades\Auth;
use App\Models\InventoryLocation;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Inventory::class, 'inventory', [
            'except' => ['index', 'listDropdown', 'locationsDropdown'],
        ]);
    }

    // Standard resource methods

    public function index()
    {
        return response()->json(Inventory::with('inventoryLocations.location')->get());
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

        $inventoryLocation = InventoryLocation::where('inventory_id', $validated['inventory_id'])
            ->where('location_id', $validated['location_id'])
            ->firstOrFail();

        if ($inventoryLocation->quantity < $validated['quantity']) {
            return response()->json(['error' => 'Not enough stock at this location.'], 422);
        }

        $inventoryLocation->decrement('quantity', $validated['quantity']);

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
            'condition_after' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $inventoryLocation = InventoryLocation::where('inventory_id', $validated['inventory_id'])
            ->where('location_id', $validated['location_id'])
            ->firstOrFail();

        $inventoryLocation->increment('quantity', $validated['quantity']);

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
        $inventories = \App\Models\Inventory::orderBy('product')->get(['id', 'product as name']);
        return response()->json($inventories);
    }
}
