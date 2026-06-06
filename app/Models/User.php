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
            'is_active'  => 'boolean',
            'is_delete'  => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    // ─── Role Checks ──────────────────────────────────────────────────

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