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
        'scale_count',
        'notes',
        'user_id',
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
            'platform_scale' => 'Platform Scale',
            'field_scale' => 'Field Scale',
            'kitchen_scale' => 'Kitchen Scale',
            'crane_scale' => 'Crane Scale',
        ];
    }
}
