<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $fillable = [
        'name',
        'id_number',
        'phone_number',
        'insurance_copy',
        'cover_expiry',
        'active',
    ];
}
