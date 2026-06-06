<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class ResourcesController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum'),
        ];
    }

    public function index(Request $request)
    {
        try {
            Gate::authorize('viewAny', Resource::class);

            // $resources = Resource::where('business_id', $request->business_id)
            //     ->where('is_delete', false)
            //     ->get();
            $resources = Resource::all();

            return response()->json([
                'message'   => 'Resources retrieved successfully',
                'resources' => $resources,
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to view resources',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving resources',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Gate::authorize('create', Resource::class);

            $fields = $request->validate([
                // 'business_id'               => 'required|exists:businesses,id',

                'resource_type'             => 'required|in:person,room,equipment,timeslot',
                'resource_name'             => 'required|string|max:80',
                'short_code'                => 'nullable|string|max:10|unique:resources,short_code',
                'capacity'                  => 'nullable|integer|min:1',
                'photos'                    => 'nullable|array',
                'photos.*'                  => 'image|mimes:jpg,jpeg,png|max:2048',
                'display_order'             => 'nullable|integer|min:1',
                'description'               => 'nullable|string|max:500',

                'job_title'                 => 'nullable|string|max:255',
                'qualification'             => 'nullable|string|max:255',
                'years_experience'          => 'nullable|integer|min:0',
                'languages'                 => 'nullable|array',
                'gender'                    => 'nullable|in:any,male,female',
                'user_id'                   => 'nullable|exists:users,id',
                'bio'                       => 'nullable|string|max:300',

                'room_type'                 => 'nullable|string|max:255',
                'floor'                     => 'nullable|string|max:255',
                'area'                      => 'nullable|numeric|min:0',
                'pricing_model'             => 'nullable|in:Per night,Per hour,Per day,Per event',
                'min_stay'                  => 'nullable|integer|min:1',
                'amenities'                 => 'nullable|string|max:255',

                'equipment_category'        => 'nullable|in:Audio / PA,Lighting,Medical device,Kitchen equipment,Sport gear,Other',
                'brand_model'               => 'nullable|string|max:255',
                'serial_number'             => 'nullable|string|max:255',
                'condition'                 => 'nullable|in:Excellent,Good,Fair,Needs to service',
                'hire_rate'                 => 'nullable|numeric|min:0',
                'deposit_required'          => 'nullable|numeric|min:0',

                'slot_category'             => 'nullable|in:Appointment,Class / Group session,Consultation,Delivery / pickup',
                'virtual_type'              => 'nullable|in:Virtual only,In-person only,Both available',
                'video_link'                => 'nullable|url|max:255',
                'max_group_size'            => 'nullable|integer|min:1',

                'custom_attributes'         => 'nullable|array',

                'base_price'                => 'nullable|numeric|min:0',
                'pricing_unit'              => 'nullable|in:Fixed price,Per hour,Per day/Overnight,Per stay',
                'buffer_after'              => 'nullable|integer|min:0',
                'cancellation_window'       => 'nullable|integer|min:0',
                'max_bookings_per_day'      => 'nullable|integer|min:1',
                'min_notice'                => 'nullable|integer|min:0',

                'is_active'                 => 'nullable|boolean',
                'require_approval'          => 'nullable|boolean',
                'allow_reschedule'          => 'nullable|boolean',
                'allow_online_payment'      => 'nullable|boolean',
                'allow_pay_on_arrival'      => 'nullable|boolean',
                'send_auto_reminders'       => 'nullable|boolean',
                'request_review_after'      => 'nullable|boolean',

                'data'                      => 'nullable|array',
            ]);

            // Handle photo uploads
            if ($request->hasFile('photos')) {
                $paths = [];
                foreach ($request->file('photos') as $photo) {
                    $paths[] = $photo->store('resources', 'public');
                }
                $fields['photos'] = $paths;
            }

            $fields['created_user'] = $request->user()->id;

            $resource = Resource::create($fields);

            return response()->json([
                'message'  => 'Resource created successfully',
                'resource' => $resource,
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to create resource',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating resource',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Resource $resource)
    {
        try {
            Gate::authorize('view', $resource);

            return response()->json([
                'message'  => 'Resource retrieved successfully',
                'resource' => $resource,
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to view resource',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Resource not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving resource',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // PUT /resources/{resource}
    public function update(Request $request, Resource $resource)
    {
        try {
            Gate::authorize('update', $resource);

            $fields = $request->validate([
                'resource_type'             => 'sometimes|required|in:person,room,equipment,timeslot',
                'resource_name'             => 'sometimes|required|string|max:80',
                'short_code'                => 'sometimes|nullable|string|max:10|unique:resources,short_code,' . $resource->id,
                'capacity'                  => 'sometimes|nullable|integer|min:1',
                'photos'                    => 'sometimes|nullable|array',
                'photos.*'                  => 'image|mimes:jpg,jpeg,png|max:2048',
                'display_order'             => 'sometimes|nullable|integer|min:1',
                'description'               => 'sometimes|nullable|string|max:500',

                'job_title'                 => 'sometimes|nullable|string|max:255',
                'qualification'             => 'sometimes|nullable|string|max:255',
                'years_experience'          => 'sometimes|nullable|integer|min:0',
                'languages'                 => 'sometimes|nullable|array',
                'gender'                    => 'sometimes|nullable|in:any,male,female',
                'user_id'                   => 'sometimes|nullable|exists:users,id',
                'bio'                       => 'sometimes|nullable|string|max:300',

                'room_type'                 => 'sometimes|nullable|string|max:255',
                'floor'                     => 'sometimes|nullable|string|max:255',
                'area'                      => 'sometimes|nullable|numeric|min:0',
                'pricing_model'             => 'sometimes|nullable|in:Per night,Per hour,Per day,Per event',
                'min_stay'                  => 'sometimes|nullable|integer|min:1',
                'amenities'                 => 'sometimes|nullable|string|max:255',

                'equipment_category'        => 'sometimes|nullable|in:Audio / PA,Lighting,Medical device,Kitchen equipment,Sport gear,Other',
                'brand_model'               => 'sometimes|nullable|string|max:255',
                'serial_number'             => 'sometimes|nullable|string|max:255',
                'condition'                 => 'sometimes|nullable|in:Excellent,Good,Fair,Needs to service',
                'hire_rate'                 => 'sometimes|nullable|numeric|min:0',
                'deposit_required'          => 'sometimes|nullable|numeric|min:0',

                'slot_category'             => 'sometimes|nullable|in:Appointment,Class / Group session,Consultation,Delivery / pickup',
                'virtual_type'              => 'sometimes|nullable|in:Virtual only,In-person only,Both available',
                'video_link'                => 'sometimes|nullable|url|max:255',
                'max_group_size'            => 'sometimes|nullable|integer|min:1',

                'custom_attributes'         => 'sometimes|nullable|array',

                'base_price'                => 'sometimes|nullable|numeric|min:0',
                'pricing_unit'              => 'sometimes|nullable|in:Fixed price,Per hour,Per day/Overnight,Per stay',
                'buffer_after'              => 'sometimes|nullable|integer|min:0',
                'cancellation_window'       => 'sometimes|nullable|integer|min:0',
                'max_bookings_per_day'      => 'sometimes|nullable|integer|min:1',
                'min_notice'                => 'sometimes|nullable|integer|min:0',

                'is_active'                 => 'sometimes|nullable|boolean',
                'require_approval'          => 'sometimes|nullable|boolean',
                'allow_reschedule'          => 'sometimes|nullable|boolean',
                'allow_online_payment'      => 'sometimes|nullable|boolean',
                'allow_pay_on_arrival'      => 'sometimes|nullable|boolean',
                'send_auto_reminders'       => 'sometimes|nullable|boolean',
                'request_review_after'      => 'sometimes|nullable|boolean',

                'data'                      => 'sometimes|nullable|array',
            ]);

            // Handle photo uploads
            if ($request->hasFile('photos')) {
                $paths = [];
                foreach ($request->file('photos') as $photo) {
                    $paths[] = $photo->store('resources', 'public');
                }
                $fields['photos'] = $paths;
            }

            $fields['updated_by'] = $request->user()->id;

            $resource->update($fields);

            return response()->json([
                'message'  => 'Resource updated successfully',
                'resource' => $resource->fresh(),
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to update resource',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Resource not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating resource',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Resource $resource)
    {
        try {
            Gate::authorize('delete', $resource);

            $resource->update(['is_delete' => true]);
            $resource->delete();

            return response()->json([
                'message' => 'Resource deleted successfully',
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to delete resource',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Resource not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                    'message' => 'Cannot delete resource as it is currently in use',
                    'error'   => 'This resource is assigned to one or more bookings',
                ], 409);
            }
            return response()->json([
                'message' => 'An error occurred while deleting resource',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}