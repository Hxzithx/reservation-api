<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // 'business_id',
        'customer_id',
        'service_id',
        'resource_id',
        'booking_reference',
        'guest_booking',
        'booking_channel',
        'date',
        'start_time',
        'end_time',
        'number_of_guests',
        'preferred_resource_type',
        'recurring',
        'status',
        'special_notes',
        'internal_staff_notes',
        'payment_type',
        'payment_method',
        'discount_code',
        'loyalty_points',
        'currency',
        'total_amount',
        'special_instructions',
        'is_delete',
        'created_user',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'guest_booking' => 'boolean',
            'is_delete'     => 'boolean',
            'date'          => 'date',
            'start_time'    => 'datetime',
            'end_time'      => 'datetime',
            'total_amount'  => 'decimal:2',
            'deleted_at'    => 'datetime',
        ];
    }

    // Auto-generate booking reference 

    private static function generateReference(int $serviceId): string
    {
        $service = \App\Models\Service::find($serviceId);

        if ($service) {
            $name   = preg_replace('/[^A-Za-z]/', '', $service->name);
            $first  = strtoupper($name[0]);                            
            $last   = strtoupper($name[strlen($name) - 1]);           
            $prefix = $first . $last;                                  
        } else {
            $prefix = 'BK';
        }

        $year     = now()->format('Y');
        $count    = self::where('booking_reference', 'like', "{$prefix}-{$year}-%")->count() + 1;
        $sequence = str_pad($count, 5, '0', STR_PAD_LEFT);

        return "{$prefix}-{$year}-{$sequence}"; 
    }

    protected static function booted(): void
    {
        static::creating(function ($booking) {
            $booking->booking_reference = self::generateReference($booking->service_id);
        });
    }
}
