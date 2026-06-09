<?php

namespace App\Observers;

use App\Models\Service;

class ServiceObserver
{
    public function created(Service $service): void
    {
        Service::withoutEvents(function() use ($service) {
            Service::where('id', $service->id)
                ->update(['data' => self::buildData($service)]);
        });
    }

    public function updated(Service $service): void
    {
        Service::withoutEvents(function() use ($service) {
            Service::where('id', $service->id)
                ->update(['data' => self::buildData($service)]);
        });
    }

    private static function buildData(Service $service): array
    {
        return [
            'company_id'            => $service->company_id,
            'name'                  => $service->name,
            'description'           => $service->description,
            'duration'              => $service->duration,
            'buffer_after'          => $service->buffer_after,
            'status'                => $service->status,
            'category'              => $service->category,
            'location_notes'        => $service->location_notes,
            'base_price'            => $service->base_price,
            'member_price'          => $service->member_price,
            'peak_price'            => $service->peak_price,
            'deposit'               => $service->deposit,
            'group_price'           => $service->group_price,
            'currency'              => $service->currency,
            'available_days'        => $service->available_days,
            'available_from'        => $service->available_from,
            'available_until'       => $service->available_until,
            'seasonal_availability' => $service->seasonal_availability,
            'max_advance_booking'   => $service->max_advance_booking,
            'min_notice'            => $service->min_notice,
            'cancellation_window'   => $service->cancellation_window,
            'max_bookings_per_day'  => $service->max_bookings_per_day,
            'confirmation'          => $service->confirmation,
            'enable_waitlist'       => $service->enable_waitlist,
            'min_group_size'        => $service->min_group_size,
            'max_group_size'        => $service->max_group_size,
            'remind_before_minutes' => $service->remind_before_minutes,
            'booking_requirements'  => $service->booking_requirements,
            'is_active'             => $service->is_active,
        ];
    }
}