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

    protected $casts = [
        'scheduled_for' => 'datetime',
    ];

    protected $appends = ['computed_status'];

    public function getComputedStatusAttribute()
    {
        // If a status has been manually set to Completed or Cancelled, respect that.
        if (in_array($this->getOriginal('status'), ['Completed', 'Cancelled'])) {
            return $this->getOriginal('status');
        }

        // A visit is 'Completed' if a return action has been logged for it.
        if ($this->inventoryActions()->where('action_type', 'return')->exists()) {
            return 'Completed';
        }

        // If the visit date has passed and it's not completed, it's 'Missed'.
        if ($this->scheduled_for->isPast()) {
            return 'Missed';
        }

        // Otherwise, it's 'Upcoming'.
        return 'Upcoming';
    }

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

    const STATUS_UPCOMING = 'upcoming';
    const STATUS_IN_PROGRESS = 'in-progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_MISSED = 'missed';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_UPCOMING => 'Upcoming',
            self::STATUS_IN_PROGRESS => 'In-Progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_MISSED => 'Missed',
        ];
    }
}
