<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Visit;

class VisitPolicy
{
    public function viewAny(User $user)    { return $user->hasRole('admin'); }
    public function view(User $user, Visit $visit) { return $user->hasRole('admin'); }
    public function create(User $user)     { return $user->hasRole('admin'); }
    public function update(User $user, Visit $visit) { return $user->hasRole('admin'); }
    public function delete(User $user, Visit $visit) { return $user->hasRole('admin'); }
    public function schedule(User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $user->can('visit.schedule');
    }
} 