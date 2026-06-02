<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ServiceController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum'),
        ];
    }

    public function index(Request $request): JsonResponse
    {
        try {
            Gate::authorize('viewAny', Service::class);

            $services = Service::where('business_id', $request->business_id)->get();

            return response()->json([
                'message' => 'Services retrieved successfully',
                'services' => $services,
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorize to view services',
                'error' => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occured while retrieving services',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request, Service $services)
    {
        try {
            Gate::authorize('create', Service::class);

            $fields = $request->validate([
                'business_id'                       => 'required|exists:businesses,id',
                'name'                              => 'required|string|max:255',
                'status'                            => 'required|in:active,inactive,draft',
                'category'                          => 'nullable|string|max:255',
                'location_notes'                    => 'nullable|string|max:500',
                'duration'                          => 'required|integer|min:1',
                'base_price'                        => 'required|numeric|min:0',
                'currancy'                          => 'required|in:USD,LKR,AED,EUR',
                'max_advance_booking'               => 'nullable|integer|min:1',
                'min_notice'                        => 'nullable|integer|min:0',
                'cancellation_window'               => 'nullable|integer|min:0',
                'buffer_after'                      => 'nullable|integer|min:0',
                'max_bookings_per_day'              => 'nullable|integer|min:1',
                'description'                       => 'nullable|string|max:255',
                'booking_requirements'              => 'nullable|array',
                'booking_requirements.*.label'      => 'required_with:booking_requirements|string',
                'booking_requirements.*.answerType' => 'required_with:booking_requirements|string',
                'booking_requirements.*.required'   => 'boolean',
                'booking_requirements.*.visible'    => 'boolean',
            ]);

            $services = Service::create($fields);

            return response()->json([
                'message' => 'Service created successfully',
                'service' => $services,
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorize to create service',
                'error'  => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating service',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Service $service)
    {
        try {
            Gate::authorize('view', $service);

            return response()->json([
                'message' => 'Service retrieved successfully',
                'service' => $service,
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to view service',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Service not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving service',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Service $service)
    {
        try {
            Gate::authorize('update', $service);

            $fields = $request->validate([
                'name'                 => 'sometimes|required|string|max:255',
                'status'               => 'sometimes|required|in:active,inactive',
                'category'             => 'sometimes|nullable|string|max:255',
                'location_notes'       => 'sometimes|nullable|string|max:500',
                'duration'             => 'sometimes|required|integer|min:1',
                'base_price'           => 'sometimes|required|numeric|min:0',
                'currency'             => 'sometimes|required|in:USD,LKR,AED,EUR',
                'deposit'              => 'sometimes|nullable|numeric|min:0',
                'max_advance_booking'  => 'sometimes|nullable|integer|min:1',
                'min_notice'           => 'sometimes|nullable|integer|min:0',
                'cancellation_window'  => 'sometimes|nullable|integer|min:0',
                'buffer_after'         => 'sometimes|nullable|integer|min:0',
                'max_bookings_per_day' => 'sometimes|nullable|integer|min:1',
                'description'          => 'sometimes|nullable|string|max:500',
                'booking_requirements' => 'sometimes|nullable|array',
            ]);

            $service->update($fields);

            return response()->json([
                'message' => 'Service updated successfully',
                'service' => $service->fresh(),
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to update service',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Service not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating service',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Service $service)
    {
        try {
            Gate::authorize('delete', $service);

            $service->delete();

            return response()->json([
                'message' => 'Service deleted successfully',
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to delete service',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Service not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                    'message' => 'Cannot delete service as it is currently in use',
                    'error'   => 'This service is assigned to one or more bookings',
                ], 409);
            }
            return response()->json([
                'message' => 'An error occurred while deleting service',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
