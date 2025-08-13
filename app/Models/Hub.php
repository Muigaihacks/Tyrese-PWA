<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hub extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'crate_count',
        'scale_count',
        'is_kibiku',
    ];

    protected $casts = [
        'is_kibiku' => 'boolean',
    ];

    public function coldStorageUnits()
    {
        return $this->hasMany(ColdStorageUnit::class);
    }

    public function crateMovements()
    {
        return $this->hasMany(CrateMovement::class, 'from_hub_id');
    }

    public function crateMovementsTo()
    {
        return $this->hasMany(CrateMovement::class, 'to_hub_id');
    }
}
