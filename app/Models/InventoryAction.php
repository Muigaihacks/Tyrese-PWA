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
    ];

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
}
