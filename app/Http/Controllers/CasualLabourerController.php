<?php

namespace App\Http\Controllers;

use App\Models\CasualLabourer;
use App\Models\CasualLabourerAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CasualLabourerController extends Controller
{
    public function timeIn(Request $request)
    {
        $request->validate([
            'job_description' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $labourer = CasualLabourer::where('user_id', $user->id)->first();

        if (!$labourer) {
            return response()->json(['error' => 'Labourer profile not found'], 404);
        }

        if ($labourer->status !== 'active') {
            return response()->json(['error' => 'Labourer account is not active'], 400);
        }

        // Check if already clocked in today
        $todayAttendance = $labourer->attendance()->where('work_date', today())->first();
        
        if ($todayAttendance && $todayAttendance->time_in) {
            return response()->json(['error' => 'Already clocked in today'], 400);
        }

        // Create or update attendance record
        $attendance = $labourer->attendance()->updateOrCreate(
            ['work_date' => today()],
            [
                'time_in' => now(),
                'job_description' => $request->job_description,
                'notes' => $request->notes,
            ]
        );

        return response()->json([
            'message' => 'Successfully clocked in',
            'time_in' => $attendance->time_in,
            'job_description' => $attendance->job_description
        ]);
    }

    public function timeOut(Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $labourer = CasualLabourer::where('user_id', $user->id)->first();

        if (!$labourer) {
            return response()->json(['error' => 'Labourer profile not found'], 404);
        }

        $todayAttendance = $labourer->attendance()->where('work_date', today())->first();
        
        if (!$todayAttendance || !$todayAttendance->time_in) {
            return response()->json(['error' => 'Not clocked in today'], 400);
        }

        if ($todayAttendance->time_out) {
            return response()->json(['error' => 'Already clocked out today'], 400);
        }

        $todayAttendance->update([
            'time_out' => now(),
            'notes' => $request->notes ? $todayAttendance->notes . "\n" . $request->notes : $todayAttendance->notes,
        ]);

        return response()->json([
            'message' => 'Successfully clocked out',
            'time_out' => $todayAttendance->time_out,
        ]);
    }

    public function getProfile()
    {
        $user = Auth::user();
        $labourer = CasualLabourer::where('user_id', $user->id)->first();

        if (!$labourer) {
            return response()->json(['error' => 'Labourer profile not found'], 404);
        }

        $todayAttendance = $labourer->getTodayAttendance();
        $totalHoursThisMonth = $labourer->getTotalHoursThisMonth();

        return response()->json([
            'labourer' => $labourer,
            'today_attendance' => $todayAttendance,
            'total_hours_this_month' => $totalHoursThisMonth,
            'is_fully_compliant' => $labourer->isFullyCompliant(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'next_of_kin_name' => 'required|string|max:255',
            'next_of_kin_phone' => 'required|string|max:255',
            'health_declaration' => 'required|boolean',
            'skills_confirmation' => 'required|boolean',
            'ppe_provided' => 'required|boolean',
            'safety_briefing' => 'required|boolean',
            'tool_safety_agreement' => 'required|boolean',
            'accident_cover_enrolled' => 'required|boolean',
            'data_consent' => 'required|boolean',
        ]);

        $user = Auth::user();
        $labourer = CasualLabourer::where('user_id', $user->id)->first();

        if (!$labourer) {
            return response()->json(['error' => 'Labourer profile not found'], 404);
        }

        $labourer->update($request->all());

        return response()->json([
            'message' => 'Profile updated successfully',
            'labourer' => $labourer,
            'is_fully_compliant' => $labourer->isFullyCompliant(),
        ]);
    }

    public function getAttendanceHistory(Request $request)
    {
        $user = Auth::user();
        $labourer = CasualLabourer::where('user_id', $user->id)->first();

        if (!$labourer) {
            return response()->json(['error' => 'Labourer profile not found'], 404);
        }

        $attendance = $labourer->attendance()
            ->orderBy('work_date', 'desc')
            ->limit(30)
            ->get();

        return response()->json([
            'attendance' => $attendance,
            'total_records' => $attendance->count(),
        ]);
    }
}
