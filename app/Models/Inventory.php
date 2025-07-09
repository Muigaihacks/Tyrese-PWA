<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'product',
        'date_added',
        'condition',
        'stock_level',
    ];

    public function inventoryLocations()
    {
        return $this->hasMany(InventoryLocation::class);
    }
}
