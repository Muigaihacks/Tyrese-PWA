<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'changed_at',
        'ip_address',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getMonthlyCount($userId)
    {
        return static::where('user_id', $userId)
            ->whereMonth('changed_at', now()->month)
            ->whereYear('changed_at', now()->year)
            ->count();
    }
}
