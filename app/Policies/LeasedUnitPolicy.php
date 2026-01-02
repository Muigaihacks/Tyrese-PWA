<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LeasedUnit;

class LeasedUnitPolicy
{
    public function viewAny(User $user)
    {
        if ($user->hasRole(['admin', 'super_admin'])) {
            return true;
        }
        return $user->can('map.view');
    }
    public function view(User $user, LeasedUnit $leasedUnit) { return $user->hasRole(['admin', 'super_admin']); }
    public function create(User $user)     { return $user->hasRole(['admin', 'super_admin']); }
    public function update(User $user, LeasedUnit $leasedUnit) { return $user->hasRole(['admin', 'super_admin']); }
    public function delete(User $user, LeasedUnit $leasedUnit) { return $user->hasRole(['admin', 'super_admin']); }
} 