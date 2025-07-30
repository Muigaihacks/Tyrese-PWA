<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\InventoryAction;
use Illuminate\Support\Facades\Auth;
use App\Models\InventoryLocation;
use App\Models\Visit; // Add this import

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

        // Handle both single item (legacy) and multiple items (new)
        if ($request->has('items_data')) {
            // New multiple items checkout
            $validated = $request->validate([
                'items_data' => 'required|array|min:1',
                'items_data.*.inventory_id' => 'required|exists:inventories,id',
                'items_data.*.quantity' => 'required|integer|min:1',
                'items_data.*.condition' => 'required|string',
                'location_id' => 'required|exists:locations,id',
                'visit_id' => 'required|exists:visits,id',
                'action_type' => 'required|in:tools,batteries',
                'notes' => 'nullable|string',
            ]);

            try {
                foreach ($validated['items_data'] as $itemData) {
                    $inventoryLocation = InventoryLocation::where('inventory_id', $itemData['inventory_id'])
                        ->where('location_id', $validated['location_id'])
                        ->firstOrFail();

                    if ($inventoryLocation->quantity < $itemData['quantity']) {
                        return response()->json(['error' => 'Not enough stock for item.'], 422);
                    }

                    $inventoryLocation->decrement('quantity', $itemData['quantity']);
                }

                // Create single action record with multiple items data
                $actionData = [
                    'user_id' => Auth::id(),
                    'location_id' => $validated['location_id'],
                    'visit_id' => $validated['visit_id'],
                    'action_type' => $validated['action_type'],
                    'items_data' => $validated['items_data'],
                    'notes' => $validated['notes'],
                    'inventory_id' => $validated['items_data'][0]['inventory_id'], // For compatibility
                    'quantity' => array_sum(array_column($validated['items_data'], 'quantity')),
                    'condition_before' => $validated['items_data'][0]['condition'], // For compatibility
                ];

                $action = InventoryAction::create($actionData);
                
                // After successful checkout, update associated visit status
                if ($action->visit) {
                    $action->visit->update(['status' => Visit::STATUS_IN_PROGRESS]);
                }
                
                return response()->json(['message' => 'Items checked out successfully']);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to checkout items.'], 500);
            }
        } else {
            // Legacy single item checkout
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
            
            // After successful checkout, update associated visit status
            if ($action->visit) {
                $action->visit->update(['status' => Visit::STATUS_IN_PROGRESS]);
            }
            
            return response()->json(['message' => 'Tool checked out successfully']);
        }
    }

    public function return(Request $request)
    {
        $this->authorize('return', Inventory::class);

        // Handle both single item (legacy) and multiple items (new)
        if ($request->has('items_data')) {
            // New multiple items return
            $validated = $request->validate([
                'items_data' => 'required|array|min:1',
                'items_data.*.inventory_id' => 'required|exists:inventories,id',
                'items_data.*.quantity' => 'required|integer|min:1',
                'items_data.*.condition' => 'required|string',
                'location_id' => 'required|exists:locations,id',
                'visit_id' => 'required|exists:visits,id',
                'action_type' => 'required|in:tools,batteries',
                'notes' => 'nullable|string',
            ]);

            try {
                foreach ($validated['items_data'] as $itemData) {
                    $inventoryLocation = InventoryLocation::where('inventory_id', $itemData['inventory_id'])
                        ->where('location_id', $validated['location_id'])
                        ->firstOrFail();

                    $inventoryLocation->increment('quantity', $itemData['quantity']);
                }

                // Create single action record with multiple items data
                $actionData = [
                    'user_id' => Auth::id(),
                    'location_id' => $validated['location_id'],
                    'visit_id' => $validated['visit_id'],
                    'action_type' => $validated['action_type'],
                    'items_data' => $validated['items_data'],
                    'notes' => $validated['notes'],
                    'inventory_id' => $validated['items_data'][0]['inventory_id'], // For compatibility
                    'quantity' => array_sum(array_column($validated['items_data'], 'quantity')),
                    'condition_after' => $validated['items_data'][0]['condition'], // For compatibility
                ];

                $action = InventoryAction::create($actionData);
                
                // After successful return, update associated visit status
                if ($action->visit) {
                    $action->visit->update(['status' => Visit::STATUS_COMPLETED]);
                }
                
                return response()->json(['message' => 'Items returned successfully']);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to return items.'], 500);
            }
        } else {
            // Legacy single item return
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
            
            // After successful return, update associated visit status
            if ($action->visit) {
                $action->visit->update(['status' => Visit::STATUS_COMPLETED]);
            }
            
            return response()->json(['message' => 'Tool returned successfully']);
        }
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
