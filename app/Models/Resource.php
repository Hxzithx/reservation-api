<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_id',
        'resource_type',
        'resource_name',
        'short_code',
        'capacity',
        'description',
        'photos',
        'job_title',
        'qualification',
        'years_experience',
        'languages',
        'gender',
        'bio',
        'display_order',
        'data',
        'is_active',
        'is_delete',
        'created_user',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'photos'           => 'array',
            'languages'        => 'array',
            'data'             => 'array',
            'is_active'        => 'boolean',
            'is_delete'        => 'boolean',
            'deleted_at'       => 'datetime',
        ];
    }

    //Relationships 

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_user');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function linkedUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // // Get services this resource provides (stored as service_ids in data JSON)
    // public function services()
    // {
    //     return Service::whereIn('id', $this->data['service_ids'] ?? [])->get();
    // }

    //Type Checks

    public function isPerson(): bool
    {
        return $this->resource_type === 'person';
    }

    public function isRoom(): bool
    {
        return $this->resource_type === 'room';
    }

    public function isEquipment(): bool
    {
        return $this->resource_type === 'equipment';
    }

    public function isTimeslot(): bool
    {
        return $this->resource_type === 'timeslot';
    }

    //Helpers 

    public function getResourceTypeLabelAttribute(): string
    {
        return match($this->resource_type) {
            'person'    => 'Person (staff / provider)',
            'room'      => 'Room / space / venue',
            'equipment' => 'Equipment / asset',
            'timeslot'  => 'Time slot (virtual)',
            default     => $this->resource_type,
        };
    }

    public function getData(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function isAvailable(): bool
    {
        return $this->is_active && !$this->is_delete;
    }
}