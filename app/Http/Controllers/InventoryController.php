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
        // Exclude assets from checkout/return functionality - they don't move
        return response()->json(Inventory::with('inventoryLocations.location')
            ->whereIn('item_type', ['tool', 'spare_part'])
            ->get());
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

        // Debug: Log the incoming request data
        \Log::info('Checkout request data:', $request->all());

        // Handle both single item (legacy) and multiple items (new)
        if ($request->has('items_data')) {
            // New multiple items checkout
            try {
                $validated = $request->validate([
                    'items_data' => 'required|array|min:1',
                    'items_data.*.inventory_id' => 'required|exists:inventories,id',
                    'items_data.*.quantity' => 'required|integer|min:1',
                    'items_data.*.condition' => 'required|string',
                    'visit_id' => 'required|exists:visits,id',
                    'action_type' => 'required|in:tools,batteries',
                    'notes' => 'nullable|string',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                \Log::error('New format checkout validation failed:', $e->errors());
                return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
            }

            try {
                // Create separate action record for each item
                foreach ($validated['items_data'] as $itemData) {
                    $inventory = \App\Models\Inventory::find($itemData['inventory_id']);

                    if (!$inventory) {
                        \Log::error('Inventory not found:', [
                            'inventory_id' => $itemData['inventory_id']
                        ]);
                        return response()->json(['error' => 'Inventory item not found.'], 422);
                    }

                    if ($inventory->quantity < $itemData['quantity']) {
                        return response()->json(['error' => 'Not enough stock for item.'], 422);
                    }

                    $inventory->decrement('quantity', $itemData['quantity']);

                    // Create individual action record for this item
                    $actionData = [
                        'user_id' => Auth::id(),
                        'visit_id' => $validated['visit_id'],
                        'action_type' => 'checkout',
                        'inventory_id' => $itemData['inventory_id'],
                        'quantity' => $itemData['quantity'],
                        'condition_before' => $itemData['condition'],
                        'notes' => $validated['notes'],
                    ];

                    InventoryAction::create($actionData);
                }
                
                // After successful checkout, update associated visit status
                if ($validated['visit_id']) {
                    $visit = Visit::find($validated['visit_id']);
                    if ($visit) {
                        $visit->update(['status' => Visit::STATUS_IN_PROGRESS]);
                    }
                }
                
                return response()->json(['message' => 'Items checked out successfully']);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to checkout items.'], 500);
            }
        } else {
            // Legacy single item checkout
            try {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'visit_id' => 'required|exists:visits,id',
            'quantity' => 'required|integer|min:1',
            'condition_before' => 'required|string',
            'notes' => 'nullable|string',
                    'action_type' => 'required|string',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                \Log::error('Checkout validation failed:', $e->errors());
                return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
            }

            $inventory = \App\Models\Inventory::find($validated['inventory_id']);

            if (!$inventory) {
                return response()->json(['error' => 'Inventory item not found.'], 422);
            }

            if ($inventory->quantity < $validated['quantity']) {
            return response()->json(['error' => 'Not enough stock at this location.'], 422);
        }

            $inventory->decrement('quantity', $validated['quantity']);

            $validated['user_id'] = Auth::id();
            $validated['action_type'] = 'checkout'; // Changed from 'checkout' to be explicit
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
                'visit_id' => 'required|exists:visits,id',
                'notes' => 'nullable|string',
            ]);

            try {
                // Create separate action record for each item
                foreach ($validated['items_data'] as $itemData) {
                    $inventory = \App\Models\Inventory::find($itemData['inventory_id']);

                    if (!$inventory) {
                        return response()->json(['error' => 'Inventory item not found.'], 422);
                    }

                    $inventory->increment('quantity', $itemData['quantity']);

                    // Create individual action record for this item
                    $actionData = [
                        'user_id' => Auth::id(),
                        'visit_id' => $validated['visit_id'],
                        'action_type' => 'return',
                        'inventory_id' => $itemData['inventory_id'],
                        'quantity' => $itemData['quantity'],
                        'condition_after' => $itemData['condition'],
                        'notes' => $validated['notes'],
                    ];

                    InventoryAction::create($actionData);
                }
                
                // After successful return, update associated visit status
                if ($validated['visit_id']) {
                    $visit = Visit::find($validated['visit_id']);
                    if ($visit) {
                        $visit->update(['status' => Visit::STATUS_COMPLETED]);
                    }
                }
                
                return response()->json(['message' => 'Items returned successfully']);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to return items.'], 500);
            }
        } else {
            // Legacy single item return
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'visit_id' => 'required|exists:visits,id',
            'quantity' => 'required|integer|min:1',
            'condition_after' => 'required|string',
            'notes' => 'nullable|string',
        ]);

            $inventory = \App\Models\Inventory::find($validated['inventory_id']);

            if (!$inventory) {
                return response()->json(['error' => 'Inventory item not found.'], 422);
            }

            $inventory->increment('quantity', $validated['quantity']);

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
        $locations = \App\Models\LeasedUnit::orderBy('name')->get(['id', 'name']);
        return response()->json($locations);
    }

    public function listDropdown(Request $request)
    {
        // Exclude assets from checkout/return dropdowns - they don't move
        $inventories = \App\Models\Inventory::whereIn('item_type', ['tool', 'spare_part'])
            ->orderBy('product')->get(['id', 'product as name']);
        return response()->json($inventories);
    }
}
