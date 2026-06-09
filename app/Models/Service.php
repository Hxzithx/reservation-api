<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'duration',
        'buffer_after',
        'status',
        'category',
        'location_notes',

        // Pricing
        'base_price',
        'member_price',
        'peak_price',
        'deposit',
        'group_price',
        'currency',

        // Availability
        'available_days',
        'available_from',
        'available_until',
        'seasonal_availability',

        // Booking Rules
        'max_advance_booking',
        'min_notice',
        'cancellation_window',
        'max_bookings_per_day',
        'confirmation',
        'enable_waitlist',
        'min_group_size',
        'max_group_size',


        // Notifications
        'remind_before_minutes',
        
        // Custom Requirements
        'booking_requirements',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'available_days'       => 'array',
            'booking_requirements' => 'array',
            'enable_waitlist'      => 'boolean',
            'base_price'           => 'decimal:2',
            'member_price'         => 'decimal:2',
            'peak_price'           => 'decimal:2',
            'deposit'              => 'decimal:2',
            'group_price'          => 'decimal:2',
            'available_from'       => 'datetime:H:i',
            'available_until'      => 'datetime:H:i',
            'deleted_at'           => 'datetime',
            'data'                 => 'array',
        ];
    }

    #[Override]
    protected static function booted(): void
    {
        static::creating(function ($service) {
            $service->data = self::buildData($service);
        });

        static::updating(function ($service) {
            $service->data = self::buildData($service);
        });
    }

    private static function buildData($service): array
    {
        return [
            'company_id' => $service->company_id,
            'name' => $service->name,
            'description' => $service->description,
            'duration' => $service->duration,
            'buffer_after' => $service->buffer_after,
            'status' => $service->status,
            'category' => $service->category,
            'location_notes' => $service->location_notes,
            'base_price' => $service->base_price,
            'member_price' => $service->member_price,
            'peak_price' => $service->peak_price,
            'deposit' => $service->deposit,
            'group_price' => $service->group_price,
            'currency' => $service->currency,
            'available_days' => $service->available_days,
            'available_from' => $service->available_from,
            'available_until' => $service->available_until,
            'seasonal_availability' => $service->seasonal_availability,
            'max_advance_booking' => $service->max_advance_booking,
            'min_notice' => $service->min_notice,
            'cancellation_window' => $service->cancellation_window,
            'max_bookings_per_day' => $service->max_bookings_per_day,
            'confirmation' => $service->confirmation,
            'enable_waitlist' => $service->enable_waitlist,
            'min_group_size' => $service->min_group_size,
            'max_group_size' => $service->max_group_size,
            'remind_before_minutes' => $service->remind_before_minutes,
            'booking_requirements' => $service->booking_requirements,
        ];
    }

    // Relationships 
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function staff()
    {
        return $this->belongsToMany(Resource::class, 'staff_services');
    }

    public function resources()
    {
        return $this->belongsToMany(Resource::class);
    }

    //Helpers 

    public function isAvailableOn(string $day): bool
    {
        return in_array($day, $this->available_days ?? []);
    }

    public function remindBeforeHours(): float
    {
        return $this->remind_before_minutes / 60;
    }
}
