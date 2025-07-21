<?php

namespace App\Http\Controllers;

use App\Models\Storage;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(\App\Models\Storage::class, 'storage');
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
        $this->authorize('create', Storage::class);

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:20',
            'date' => 'required|date',
            'fee' => 'required|numeric|min:0',
        ]);

        $storage = Storage::create($validated);

        return response()->json($storage, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Storage $storage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Storage $storage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Storage $storage)
    {
        //
    }
}
