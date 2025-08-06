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
    ];

    protected $casts = [
        'work_date' => 'date',
        'time_in' => 'datetime:H:i:s',
        'time_out' => 'datetime:H:i:s',
    ];

    // Relationships
    public function casualLabourer()
    {
        return $this->belongsTo(CasualLabourer::class);
    }
}
