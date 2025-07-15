<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Insurance;

class InsurancePolicy
{
    public function viewAny(User $user)    { return $user->hasRole('admin'); }
    public function view(User $user, Insurance $insurance) { return $user->hasRole('admin'); }
    public function create(User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $user->can('insurance.create');
    }
    public function update(User $user, Insurance $insurance) { return $user->hasRole('admin'); }
    public function delete(User $user, Insurance $insurance) { return $user->hasRole('admin'); }
} 