<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Battery;
use App\Models\BatteryMovement;
use App\Models\LeasedUnit;
use Illuminate\Support\Facades\Auth;

class BatteryController extends Controller
{
    public function index()
    {
        $batteries = Battery::with('coldStorageUnit')->get();
        return response()->json($batteries);
    }

    public function swap(Request $request)
    {
        $request->validate([
            'from_unit_id' => 'required|exists:leased_units,id',
            'to_unit_id' => 'required|exists:leased_units,id',
            'batteries_data' => 'required|array|min:1',
            'batteries_data.*.battery_id' => 'required|exists:batteries,id',
            'batteries_data.*.condition_before' => 'required|in:excellent,good,fair,poor,defective',
            'notes' => 'nullable|string',
        ]);

        try {
            foreach ($request->batteries_data as $batteryData) {
                $battery = Battery::find($batteryData['battery_id']);
                
                // Create battery movement record
                BatteryMovement::create([
                    'battery_id' => $battery->id,
                    'user_id' => Auth::id(),
                    'from_unit_id' => $request->from_unit_id,
                    'to_unit_id' => $request->to_unit_id,
                    'movement_type' => 'swap',
                    'condition_before' => $batteryData['condition_before'],
                    'condition_after' => null, // We don't track condition after
                    'notes' => $request->notes,
                ]);

                // Update battery's unit assignment
                $battery->update([
                    'cold_storage_unit_id' => $request->to_unit_id,
                    // Keep the original condition since we don't track after
                ]);
            }

            return response()->json(['message' => 'Battery swap completed successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to complete battery swap'], 500);
        }
    }
}
