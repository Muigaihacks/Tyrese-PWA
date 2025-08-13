<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ColdStorageUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'hub_id',
        'unit_id',
        'crate_count',
        'description',
    ];

    public function hub()
    {
        return $this->belongsTo(Hub::class);
    }
}
