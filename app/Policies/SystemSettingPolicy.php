<?php

namespace App\Policies;

use App\Models\SystemSetting;
use App\Models\User;

class SystemSettingPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function view(User $user, SystemSetting $systemSetting): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, SystemSetting $systemSetting): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, SystemSetting $systemSetting): bool
    {
        return $user->role === 'admin';
    }
}