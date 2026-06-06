<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'role',
        'phone',
        'avatar',
        'is_active',
        'is_delete',
        'created_user',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'data'       => 'array',
            'is_active'  => 'boolean',
            'is_delete'  => 'boolean',
            'deleted_at' => 'datetime',  
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($user) {
            $user->data = self::buildData($user);
        });

        static::updating(function ($user) {
            $user->data = self::buildData($user);
        });
    }

    private static function buildData($user): array
    {
        return [
            'name'         => $user->name,
            'email'        => $user->email,
            'role'         => $user->role,
            'phone'        => $user->phone,
            'avatar'       => $user->avatar,
            'is_active'    => $user->is_active,
            'is_delete'    => $user->is_delete,
            'created_user' => $user->created_user,
            'updated_by'   => $user->updated_by,
        ];
    }

    // Role Checks

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }
}
