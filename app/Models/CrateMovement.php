<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CrateMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_hub_id',
        'to_hub_id',
        'crate_count',
        'scale_type',
        'notes',
        'user_id',
        'visit_id',
    ];

    public function fromHub()
    {
        return $this->belongsTo(Hub::class, 'from_hub_id');
    }

    public function toHub()
    {
        return $this->belongsTo(Hub::class, 'to_hub_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function getScaleTypeOptions()
    {
        return [
            'digital_scale' => 'Digital Scale',
            'analog_scale' => 'Analog Scale',
            'hanging_scale' => 'Hanging Scale',
            'platform_scale' => 'Platform Scale',
        ];
    }
}
