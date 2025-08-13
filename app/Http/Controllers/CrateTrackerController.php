<?php

namespace App\Http\Controllers;

use App\Models\Hub;
use App\Models\CrateMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CrateTrackerController extends Controller
{
    public function getHubs()
    {
        $hubs = Hub::all();
        return response()->json($hubs);
    }

    public function createMovement(Request $request)
    {
        $validated = $request->validate([
            'from_hub_id' => 'required|exists:hubs,id',
            'to_hub_id' => 'required|exists:hubs,id',
            'crate_count' => 'required|integer|min:0',
            'scale_type' => 'nullable|in:platform_scale,field_scale,kitchen_scale,crane_scale',
            'scale_count' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        // Check if source hub has enough crates
        $fromHub = Hub::find($validated['from_hub_id']);
        if ($fromHub->crate_count < $validated['crate_count']) {
            return response()->json(['error' => 'Not enough crates at source hub.'], 422);
        }

        try {
            DB::beginTransaction();

            // Create the movement record
            $movement = CrateMovement::create([
                'from_hub_id' => $validated['from_hub_id'],
                'to_hub_id' => $validated['to_hub_id'],
                'crate_count' => $validated['crate_count'],
                'scale_type' => $validated['scale_type'],
                'notes' => $validated['notes'],
                'user_id' => Auth::id(),
            ]);

            // Update hub crate counts
            $fromHub->decrement('crate_count', $validated['crate_count']);
            $toHub = Hub::find($validated['to_hub_id']);
            $toHub->increment('crate_count', $validated['crate_count']);

            // Update scale count if scale is being moved
            if ($validated['scale_type'] && $validated['scale_count']) {
                $fromHub->decrement('scale_count', $validated['scale_count']);
                $toHub->increment('scale_count', $validated['scale_count']);
            }

            DB::commit();

            return response()->json([
                'message' => 'Movement recorded successfully',
                'movement' => $movement->load(['fromHub', 'toHub', 'user'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to record movement.'], 500);
        }
    }

    public function getMovements()
    {
        $movements = CrateMovement::with(['fromHub', 'toHub', 'user', 'visit'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($movements);
    }


}
