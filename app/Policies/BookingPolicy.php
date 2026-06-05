<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'customer']);
    }

    public function view(User $user, Booking $booking): bool
    {
        // Admin and staff can view all bookings
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }

        // Customer can only view their own bookings
        return $user->id === $booking->customer_id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'customer']);
    }

    public function update(User $user, Booking $booking): bool
    {
        // Admin and staff can update any booking
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }

        // Customer can only update their own bookings
        return $user->id === $booking->customer_id;
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->role === 'admin';
    }
}