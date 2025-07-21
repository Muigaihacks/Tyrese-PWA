<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $fillable = [
        'name',
        'id_number',
        'phone_number',
        'start_date',
        'site',
        'insurance_copy',
        'cover_expiry',
    ];

    protected $casts = [
        'cover_expiry' => 'date',
    ];

    public function getActiveAttribute()
    {
        return $this->cover_expiry && \Carbon\Carbon::parse($this->cover_expiry)->isFuture();
    }
}
