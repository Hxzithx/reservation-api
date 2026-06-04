<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Exception;
use Illuminate\Support\Facades\Gate;

class BookingController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum'),
        ];
    }

    public function index()
    {
        try {
            Gate::authorize('viewAny', Booking::class);

            $bookings = Booking::all();

            return response()->json([
                'message' => 'Booking retrieved successfully',
                'bookings' => $bookings,
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to view bookings',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving bookings',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Gate::authorize('create', Booking::class);

            $fields = $request->validate([
                // 'business_id'              => 'required|exists:businesses,id',
                'customer_id'              => 'required_if:guest_booking,No , Linked to account|nullable|exists:users,id',
                'service_id'               => 'required|exists:services,id',
                'resource_id'              => 'required|exists:resources,id',

                'guest_booking'            => 'required|in:No , Linked to account,Yes , Guest only',
                'guest_name'               => 'required_if:guest_booking,Yes , Guest only|nullable|string|max:255',
                'guest_email'              => 'required_if:guest_booking,Yes , Guest only|nullable|email|max:255',
                'booking_channel'          => 'required|in:online,walk_in,phone,api,whatsapp',

                'date'                     => 'required|date',
                'start_time'               => 'required|date_format:Y-m-d H:i:s',
                'end_time'                 => 'required|date_format:Y-m-d H:i:s|after:start_time',
                'number_of_guests'         => 'nullable|integer|min:1',
                'preferred_resource_type'  => 'nullable|string|max:255',
                'recurring'                => 'required|in:one_time,daily,weekly,monthly',

                'special_notes'            => 'nullable|string|max:500',
                'internal_staff_notes'     => 'nullable|string',

                'payment_type'             => 'required|in:full_payment,deposit_only,pay_on_arrival,invoice',
                'payment_method'           => 'required|in:Credit / Debit card,Cash,Bank transfer,Online gateway,wallet,Contactless NFC',
                'discount_code'            => 'nullable|string|max:255',
                'loyalty_points'           => 'nullable|integer|min:0',
                'currency'                 => 'required|in:LKR - Sri Lankan Rupee,USD - US Dollar,EUR - Euro,AUD - Australian Dollar',
                'total_amount'             => 'nullable|numeric|min:0',
                'special_instructions'     => 'nullable|string|max:500',
            ]);
            $fields['created_user'] = $request->user()->id;

            $booking = Booking::create($fields);

            return response()->json([
                'message' => 'Booking created successfully',
                'booking' => $booking,
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to create booking',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating booking',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Booking $booking)
    {
        try {
            Gate::authorize('view', $booking);

            return response()->json([
                'message' => 'Booking retrieved successfully',
                'booking' => $booking,
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to view booking',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Booking not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving booking',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Booking $booking)
    {
        try {
            Gate::authorize('update', $booking);

            $fields = $request->validate([
                'customer_id'              => 'sometimes|nullable|exists:users,id',
                'service_id'               => 'sometimes|required|exists:services,id',
                'resource_id'              => 'sometimes|required|exists:resources,id',

                'guest_booking'            => 'sometimes|required|in:No , Linked to account,Yes , Guest only',
                'guest_name'               => 'sometimes|nullable|string|max:255',
                'guest_email'              => 'sometimes|nullable|email|max:255',
                'booking_channel'          => 'sometimes|required|in:online,walk_in,phone,api,whatsapp',

                'date'                     => 'sometimes|required|date',
                'start_time'               => 'sometimes|required|date_format:Y-m-d H:i:s',
                'end_time'                 => 'sometimes|required|date_format:Y-m-d H:i:s|after:start_time',
                'number_of_guests'         => 'sometimes|nullable|integer|min:1',
                'preferred_resource_type'  => 'sometimes|nullable|string|max:255',
                'recurring'                => 'sometimes|required|in:one_time,daily,weekly,monthly',

                'status'                   => 'sometimes|required|in:pending,confirmed,cancelled,completed',

                'special_notes'            => 'sometimes|nullable|string|max:500',
                'internal_staff_notes'     => 'sometimes|nullable|string',

                'payment_type'             => 'sometimes|required|in:full_payment,deposit_only,pay_on_arrival,invoice',
                'payment_method'           => 'sometimes|required|in:Credit / Debit card,Cash,Bank transfer,Online gateway,wallet,Contactless NFC',
                'discount_code'            => 'sometimes|nullable|string|max:255',
                'loyalty_points'           => 'sometimes|nullable|integer|min:0',
                'currency'                 => 'sometimes|required|in:LKR - Sri Lankan Rupee,USD - US Dollar,EUR - Euro,AUD - Australian Dollar',
                'total_amount'             => 'sometimes|nullable|numeric|min:0',
                'special_instructions'     => 'sometimes|nullable|string|max:500',
            ]);

            $fields['updated_by'] = $request->user()->id;

            $booking = Booking::update($fields);

            return response()->json([
                'message' => 'Booking updated successfully',
                'booking' => $booking->fresh(),
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to update booking',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Booking not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating booking',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Booking $booking)
    {
        try {
            Gate::authorize('delete', $booking);

            $booking->update(['is_delete' => true]);
            $booking->delete();

            return response()->json([
                'message' => 'Booking deleted successfully',
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to delete booking',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Booking not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                    'message' => 'Cannot delete booking as it is currently in use',
                    'error'   => 'This booking is linked to other records',
                ], 409);
            }
            return response()->json([
                'message' => 'An error occurred while deleting booking',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        try {
            Gate::authorize('update', $booking);

            $fields = $request->validate([
                'status' => 'required|in:pending,confirmed,cancelled,completed',
            ]);

            $booking->update($fields);

            return response()->json([
                'message' => 'Booking status updated successfully',
                'booking' => $booking->fresh(),
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to update booking status',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating booking status',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
