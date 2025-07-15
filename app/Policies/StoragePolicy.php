<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Storage;

class StoragePolicy
{
    public function viewAny(User $user)    { return $user->hasRole('admin'); }
    public function view(User $user, Storage $storage) { return $user->hasRole('admin'); }
    public function create(User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $user->can('storage.create');
    }
    public function update(User $user, Storage $storage) { return $user->hasRole('admin'); }
    public function delete(User $user, Storage $storage) { return $user->hasRole('admin'); }
} 