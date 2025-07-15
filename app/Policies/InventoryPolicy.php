<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Inventory;

class InventoryPolicy
{
    public function viewAny(User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $user->can('inventory.view');
    }

    public function checkout(User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $user->can('inventory.checkout');
    }

    public function return(User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $user->can('inventory.return');
    }

    // Optionally, add other actions as needed
}
