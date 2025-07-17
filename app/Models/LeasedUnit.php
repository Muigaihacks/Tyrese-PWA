<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeasedUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'lessee_name',
        'lessee_contact',
        'leasing_fee',
        'notes',
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class, 'unit_id');
    }
}
