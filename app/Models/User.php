<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPassword as CustomResetPassword;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
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

    public function canAccessPanel(Panel $panel): bool
    {
        // Allow super_admin and admin roles to access the admin panel
        return $this->hasRole(['super_admin', 'admin']);
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
            // Prevent infinite recursion by checking if we're already processing roles
            if (isset($user->attributes['_processing_roles'])) {
                return;
            }

            if ($user->role) {
                // Mark that we're processing roles to prevent recursion
                $user->attributes['_processing_roles'] = true;
                
                try {
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
                } finally {
                    // Always remove the processing flag
                    unset($user->attributes['_processing_roles']);
                }
            }
        });
    }
}
