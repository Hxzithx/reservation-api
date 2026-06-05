<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'customer']);
    }

    public function view(User $user, Service $service): bool
    {
        return in_array($user->role, ['admin', 'staff', 'customer']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function update(User $user, Service $service): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->role === 'admin';
    }
}