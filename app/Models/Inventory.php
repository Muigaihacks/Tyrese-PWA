<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'product',
        'item_type',
        'quantity',
        'date_added',
        'condition',
    ];

    protected $casts = [
        'date_added' => 'date',
        'item_type' => 'string',
    ];

    protected $appends = ['stock_level'];

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
        }

        if ($total > 0) {
            return 'Low Stock';
        }

        return 'Out of Stock';
    }

    public function getItemTypeOptions()
    {
        return [
            'tool' => 'Tool',
            'spare_part' => 'Spare Part'
        ];
    }

    public function getItemTypeColor()
    {
        return match($this->item_type) {
            'tool' => 'blue',
            'spare_part' => 'green',
            default => 'gray'
        };
    }
}
