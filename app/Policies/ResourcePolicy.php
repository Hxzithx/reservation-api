<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;

class ResourcePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'customer']);
    }

    public function view(User $user, Resource $resource): bool
    {
        return in_array($user->role, ['admin', 'staff', 'customer']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function update(User $user, Resource $resource): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function delete(User $user, Resource $resource): bool
    {
        return $user->role === 'admin';
    }
}