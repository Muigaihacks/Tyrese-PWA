<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Visit;

class VisitPolicy
{
    public function viewAny(User $user)    { return true; } // Temporarily allow all access
    public function view(User $user, Visit $visit) { return true; } // Temporarily allow all access
    public function create(User $user)     { return true; } // Temporarily allow all access
    public function update(User $user, Visit $visit) { return true; } // Temporarily allow all access
    public function delete(User $user, Visit $visit) { return true; } // Temporarily allow all access
    public function schedule(User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $user->can('visit.schedule');
    }
} 