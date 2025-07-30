<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Battery extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_code',
        'cold_storage_unit_id',
        'condition',
        'status',
        'notes'
    ];

    protected $casts = [
        'condition' => 'string',
        'status' => 'string',
    ];

    // Relationships
    public function coldStorageUnit()
    {
        return $this->belongsTo(LeasedUnit::class, 'cold_storage_unit_id');
    }

    public function movements()
    {
        return $this->hasMany(BatteryMovement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }

    // Helper methods
    public function getConditionOptions()
    {
        return [
            'excellent' => 'Excellent',
            'good' => 'Good',
            'fair' => 'Fair',
            'poor' => 'Poor',
            'defective' => 'Defective'
        ];
    }

    public function getStatusOptions()
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'maintenance' => 'Maintenance',
            'retired' => 'Retired'
        ];
    }
}
