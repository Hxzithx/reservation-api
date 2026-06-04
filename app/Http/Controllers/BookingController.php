<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Auth\Access\AuthorizationException;
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

    public function index(Request $request)
    {
        try{
            Gate::authorize('viewAny', Booking::class);

            $bookings = Booking::all();

            return response()->json([
               'message' => 'Booking retrieved successfully',
               'bookings' => $bookings,
            ], 200);
        }catch (AuthorizationException $e) {
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
}