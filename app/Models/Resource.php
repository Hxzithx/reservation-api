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
        'display_order',
        'data',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'photos'    => 'array',
            'data'      => 'array',
            'is_active' => 'boolean',
            'deleted_at'=> 'datetime',
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

    public function services()
    {
        return $this->belongsToMany(Service::class);
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

    public function isAvailable(): bool
    {
        return $this->is_active;
    }

    // Type Checks

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

    // Data Accessors

    public function getData(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }
}