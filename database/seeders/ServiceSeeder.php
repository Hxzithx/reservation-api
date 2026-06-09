<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'company_id'           => 1,
                'name'                  => 'Haircut',
                'status'                => 'active',
                'duration'              => 30,
                'description'           => 'Professional haircut service',
                'category'              => 'Hair',
                'location_notes'        => 'Studio 1',
                'base_price'            => 1500.00,
                'member_price'          => 1200.00,
                'peak_price'            => 1800.00,
                'deposit'               => 0,
                'group_price'           => null,
                'currency'              => 'LKR',
                'available_days'        => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                'available_from'        => '09:00:00',
                'available_until'       => '18:00:00',
                'seasonal_availability' => 'all_year',
                'max_advance_booking'   => 60,
                'min_notice'            => 2,
                'buffer_after'          => 10,
                'cancellation_window'   => 24,
                'max_bookings_per_day'  => 10,
                'confirmation'          => 'auto',
                'enable_waitlist'       => false,
                'min_group_size'        => 1,
                'max_group_size'        => 1,
                'remind_before_minutes' => 1440,
                'booking_requirements'  => [],
            ],
            [
                'company_id'           => 1,
                'name'                  => 'Massage',
                'status'                => 'active',
                'duration'              => 60,
                'description'           => 'Full body relaxation massage',
                'category'              => 'Wellness',
                'location_notes'        => 'Spa Room 1',
                'base_price'            => 3500.00,
                'member_price'          => 3000.00,
                'peak_price'            => 4000.00,
                'deposit'               => 500,
                'group_price'           => null,
                'currency'              => 'LKR',
                'available_days'        => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                'available_from'        => '10:00:00',
                'available_until'       => '17:00:00',
                'seasonal_availability' => 'all_year',
                'max_advance_booking'   => 30,
                'min_notice'            => 4,
                'buffer_after'          => 15,
                'cancellation_window'   => 48,
                'max_bookings_per_day'  => 5,
                'confirmation'          => 'manual',
                'enable_waitlist'       => true,
                'min_group_size'        => 1,
                'max_group_size'        => 1,
                'remind_before_minutes' => 120,
                'booking_requirements'  => [
                    [
                        'label'      => 'Any medical conditions?',
                        'answerType' => 'Long text',
                        'required'   => false,
                        'visible'    => true,
                    ],
                ],
            ],
            [
                'company_id'           => 1,
                'name'                  => 'Manicure',
                'status'                => 'active',
                'duration'              => 45,
                'description'           => 'Professional nail care and manicure',
                'category'              => 'Nails',
                'location_notes'        => 'Nail Station',
                'base_price'            => 2000.00,
                'member_price'          => 1700.00,
                'peak_price'            => 2500.00,
                'deposit'               => 0,
                'group_price'           => null,
                'currency'              => 'LKR',
                'available_days'        => ['Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                'available_from'        => '09:00:00',
                'available_until'       => '17:00:00',
                'seasonal_availability' => 'all_year',
                'max_advance_booking'   => 30,
                'min_notice'            => 2,
                'buffer_after'          => 10,
                'cancellation_window'   => 24,
                'max_bookings_per_day'  => 8,
                'confirmation'          => 'auto',
                'enable_waitlist'       => false,
                'min_group_size'        => 1,
                'max_group_size'        => 1,
                'remind_before_minutes' => 1440,
                'booking_requirements'  => [],
            ],
        ];

    foreach ($services as $service) {
        $created = Service::create($service);
        $created->save();
    }
    }
}