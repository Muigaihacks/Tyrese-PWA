<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeasedUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'lessee_name',
        'lessee_contact',
        'leasing_fee',
        'notes',
        'ownership_status',
        'unit_status',
        'unit_type',
        'battery_count',
        'unit_notes',
    ];

    protected $casts = [
        'ownership_status' => 'string',
        'unit_status' => 'string',
        'unit_type' => 'string',
        'battery_count' => 'integer',
    ];

    // Relationships
    public function visits()
    {
        return $this->hasMany(Visit::class, 'unit_id');
    }

    public function batteries()
    {
        return $this->hasMany(Battery::class, 'cold_storage_unit_id');
    }

    public function batteryMovementsFrom()
    {
        return $this->hasMany(BatteryMovement::class, 'from_unit_id');
    }

    public function batteryMovementsTo()
    {
        return $this->hasMany(BatteryMovement::class, 'to_unit_id');
    }

    // Helper methods
    public function getOwnershipStatusOptions()
    {
        return [
            'SokoFresh' => 'SokoFresh',
            'SokoFresh LLP' => 'SokoFresh LLP'
        ];
    }

    public function getUnitStatusOptions()
    {
        return [
            'leased' => 'Leased',
            'lease-to-own' => 'Lease-to-Own',
            'outright_purchase' => 'Outright Purchase'
        ];
    }

    public function getUnitTypeOptions()
    {
        return [
            'cold_storage' => 'Cold Storage Unit',
            'NTU' => 'NTU (Heavy Duty Freezer)'
        ];
    }

    // Scopes
    public function scopeByUnitType($query, $type)
    {
        return $query->where('unit_type', $type);
    }

    public function scopeByOwnership($query, $ownership)
    {
        return $query->where('ownership_status', $ownership);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('unit_status', $status);
    }
}
