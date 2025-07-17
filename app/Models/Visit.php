<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'location',
        'scheduled_by',
        'scheduled_for',
        'status',
        'notes',
    ];

    public function leasedUnit()
    {
        return $this->belongsTo(LeasedUnit::class, 'unit_id');
    }

    public function scheduler()
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    public function inventoryActions()
    {
        return $this->hasMany(InventoryAction::class);
    }
}
