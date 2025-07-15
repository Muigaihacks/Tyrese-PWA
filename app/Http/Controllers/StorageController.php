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
        //
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
