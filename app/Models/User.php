<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPassword as CustomResetPassword;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'role', 'status', 'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    public function passwordChangeLogs()
    {
        return $this->hasMany(PasswordChangeLog::class);
    }

    public function canChangePassword()
    {
        $monthlyChanges = PasswordChangeLog::getMonthlyCount($this->id);
        return $monthlyChanges < 3; // Maximum 3 changes per month
    }

    public function getRemainingPasswordChanges()
    {
        $monthlyChanges = PasswordChangeLog::getMonthlyCount($this->id);
        return max(0, 3 - $monthlyChanges);
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($user) {
            if ($user->role) {
                // Remove all existing roles first
                $user->syncRoles([]);
                
                // Ensure the role exists for both web and sanctum guards
                $webRole = \Spatie\Permission\Models\Role::firstOrCreate([
                    'name' => $user->role,
                    'guard_name' => 'web'
                ]);
                
                $sanctumRole = \Spatie\Permission\Models\Role::firstOrCreate([
                    'name' => $user->role,
                    'guard_name' => 'sanctum'
                ]);
                
                // Assign both roles to ensure compatibility
                $user->assignRole($webRole);
                $user->assignRole($sanctumRole);
            }
        });
    }
}
