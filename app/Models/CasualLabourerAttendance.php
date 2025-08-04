<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasualLabourerAttendance extends Model
{
    use HasFactory;

    protected $table = 'casual_labourer_attendance';

    protected $fillable = [
        'casual_labourer_id',
        'work_date',
        'time_in',
        'time_out',
        'job_description',
        'notes',
        'total_hours',
        'total_hours_decimal',
    ];

    protected $casts = [
        'work_date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];

    // Relationships
    public function casualLabourer()
    {
        return $this->belongsTo(CasualLabourer::class);
    }

    // Helper methods
    public function calculateTotalHours()
    {
        if ($this->time_in && $this->time_out) {
            $start = \Carbon\Carbon::parse($this->time_in);
            $end = \Carbon\Carbon::parse($this->time_out);
            $diff = $end->diffInMinutes($start);
            
            $this->total_hours = $diff;
            $this->total_hours_decimal = round($diff / 60, 2);
            $this->save();
        }
    }

    public function getFormattedTotalHours()
    {
        if ($this->total_hours_decimal) {
            $hours = intval($this->total_hours_decimal);
            $minutes = round(($this->total_hours_decimal - $hours) * 60);
            return "{$hours}h {$minutes}m";
        }
        return '0h 0m';
    }
}
