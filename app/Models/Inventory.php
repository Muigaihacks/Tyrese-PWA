<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'product',
        'date_added',
        'condition',
    ];

    protected $casts = [
        'date_added' => 'date',
    ];

    public function inventoryLocations()
    {
        return $this->hasMany(InventoryLocation::class);
    }

    public function inventoryActions()
    {
        return $this->hasMany(\App\Models\InventoryAction::class);
    }

    public function getStockLevelAttribute()
    {
        $total = $this->inventoryLocations()->sum('quantity');

        if ($total > 20) {
            return 'In Stock';
        } elseif ($total > 0) {
            return 'Low Stock';
        } else {
            return 'Out of Stock';
        }
    }
}
