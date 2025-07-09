<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function inventoryLocations()
    {
        return $this->hasMany(InventoryLocation::class);
    }

    // Optional: If you want to get all inventories at this location
    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'inventory_locations')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
