<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'location_id',
        'user_id',
        'visit_id',
        'action_type',
        'quantity',
        'condition_before',
        'condition_after',
        'notes',
        'items_data',
        'battery_condition_before',
        'battery_condition_after',
        'from_unit_id',
        'to_unit_id',
    ];

    protected $casts = [
        'items_data' => 'array',
        'battery_condition_before' => 'string',
        'battery_condition_after' => 'string',
    ];

    // Relationships
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function fromUnit()
    {
        return $this->belongsTo(LeasedUnit::class, 'from_unit_id');
    }

    public function toUnit()
    {
        return $this->belongsTo(LeasedUnit::class, 'to_unit_id');
    }

    // Helper methods
    public function getActionTypeOptions()
    {
        return [
            'checkout' => 'Checkout',
            'return' => 'Return',
            'tools' => 'Tools',
            'batteries' => 'Batteries'
        ];
    }

    public function getBatteryConditionOptions()
    {
        return [
            'excellent' => 'Excellent',
            'good' => 'Good',
            'fair' => 'Fair',
            'poor' => 'Poor',
            'defective' => 'Defective'
        ];
    }

    // Scopes
    public function scopeByActionType($query, $type)
    {
        return $query->where('action_type', $type);
    }

    public function scopeTools($query)
    {
        return $query->where('action_type', 'tools');
    }

    public function scopeBatteries($query)
    {
        return $query->where('action_type', 'batteries');
    }
}
