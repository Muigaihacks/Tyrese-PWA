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
        'stock_level',
        'notes',
    ];

    protected $casts = [
        'date_added' => 'date',
        'item_type' => 'string',
    ];

    protected $appends = ['stock_level'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inventory) {
            $inventory->stock_level = $inventory->getStockLevelAttribute();
        });

        static::updating(function ($inventory) {
            $inventory->stock_level = $inventory->getStockLevelAttribute();
        });
    }

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
        $quantity = $this->quantity ?? 0;

        if ($quantity > 20) {
            return 'In Stock';
        }

        if ($quantity > 0) {
            return 'Low Stock';
        }

        return 'Out of Stock';
    }

    public function getItemTypeOptions()
    {
        return [
            'tool' => 'Tool',
            'spare_part' => 'Spare Part',
            'asset' => 'Asset'
        ];
    }

    public function getItemTypeColor()
    {
        return match($this->item_type) {
            'tool' => 'blue',
            'spare_part' => 'green',
            'asset' => 'purple',
            default => 'gray'
        };
    }
}
