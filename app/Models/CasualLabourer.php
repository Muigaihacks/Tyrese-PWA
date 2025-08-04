<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasualLabourer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'age_group',
        'phone_number',
        'id_number',
        'next_of_kin_name',
        'next_of_kin_phone',
        'health_declaration',
        'skills_confirmation',
        'ppe_provided',
        'safety_briefing',
        'tool_safety_agreement',
        'accident_cover_enrolled',
        'data_consent',
        'status',
        'contract_start_date',
        'contract_end_date',
        'user_id',
    ];

    protected $casts = [
        'health_declaration' => 'boolean',
        'skills_confirmation' => 'boolean',
        'ppe_provided' => 'boolean',
        'safety_briefing' => 'boolean',
        'tool_safety_agreement' => 'boolean',
        'accident_cover_enrolled' => 'boolean',
        'data_consent' => 'boolean',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->hasMany(CasualLabourerAttendance::class);
    }

    // Helper methods
    public function getGenderOptions()
    {
        return [
            'M' => 'Male',
            'F' => 'Female'
        ];
    }

    public function getAgeGroupOptions()
    {
        return [
            '18-35' => '18-35',
            '36+ YEARS' => '36+ YEARS'
        ];
    }

    public function getStatusOptions()
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'terminated' => 'Terminated'
        ];
    }

    public function isFullyCompliant()
    {
        return $this->health_declaration &&
               $this->skills_confirmation &&
               $this->ppe_provided &&
               $this->safety_briefing &&
               $this->tool_safety_agreement &&
               $this->accident_cover_enrolled &&
               $this->data_consent;
    }

    public function getTodayAttendance()
    {
        return $this->attendance()->where('work_date', today())->first();
    }

    public function getTotalHoursThisMonth()
    {
        return $this->attendance()
            ->whereMonth('work_date', now()->month)
            ->whereYear('work_date', now()->year)
            ->sum('total_hours_decimal');
    }
}
