<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

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
        // ... your checkout logic here ...
    }

    public function return(Request $request)
    {
        $this->authorize('return', Inventory::class);
        // ... your return logic here ...
    }
}
