<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)    { return true; } // Temporarily allow all access
    public function view(User $user, User $model) { return true; } // Temporarily allow all access
    public function create(User $user)     { return true; } // Temporarily allow all access
    public function update(User $user, User $model) { return true; } // Temporarily allow all access
    public function delete(User $user, User $model) { return true; } // Temporarily allow all access
} 