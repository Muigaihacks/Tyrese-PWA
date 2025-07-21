<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    protected $fillable = [
        'client_name',
        'phone_number',
        'product_name',
        'quantity',
        'unit',
        'date',
        'fee',
    ];      
}
