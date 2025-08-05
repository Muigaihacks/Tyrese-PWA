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
        return $user->can('view inventory');
    }

    public function checkout(User $user)
    {
        \Log::info('InventoryPolicy checkout check:', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'has_admin_role' => $user->hasRole('admin'),
            'can_checkout_tool' => $user->can('checkout tool'),
            'all_permissions' => $user->getAllPermissions()->pluck('name')->toArray()
        ]);

        // Allow all authenticated users to access core functionalities
        return true;
    }

    public function return(User $user)
    {
        // Allow all authenticated users to access core functionalities
        return true;
    }

    // Optionally, add other actions as needed
}
