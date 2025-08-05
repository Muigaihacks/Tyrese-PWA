<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Storage;

class StoragePolicy
{
    public function viewAny(User $user)    { return true; } // Allow all users
    public function view(User $user, Storage $storage) { return true; } // Allow all users
    public function create(User $user)
    {
        // Allow all authenticated users to access core functionalities
        return true;
    }
    public function update(User $user, Storage $storage) { return true; } // Allow all users
    public function delete(User $user, Storage $storage) { return true; } // Allow all users
} 