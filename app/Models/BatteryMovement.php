<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatteryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'battery_id',
        'user_id',
        'from_unit_id',
        'to_unit_id',
        'movement_type',
        'condition_before',
        'condition_after',
        'notes'
    ];

    protected $casts = [
        'movement_type' => 'string',
        'condition_before' => 'string',
        'condition_after' => 'string',
    ];

    // Relationships
    public function battery()
    {
        return $this->belongsTo(Battery::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromUnit()
    {
        return $this->belongsTo(LeasedUnit::class, 'from_unit_id');
    }

    public function toUnit()
    {
        return $this->belongsTo(LeasedUnit::class, 'to_unit_id');
    }

    // Scopes
    public function scopeByMovementType($query, $type)
    {
        return $query->where('movement_type', $type);
    }

    public function scopeByBattery($query, $batteryId)
    {
        return $query->where('battery_id', $batteryId);
    }

    // Helper methods
    public function getMovementTypeOptions()
    {
        return [
            'checkout' => 'Checkout',
            'return' => 'Return',
            'swap' => 'Swap'
        ];
    }

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
}
