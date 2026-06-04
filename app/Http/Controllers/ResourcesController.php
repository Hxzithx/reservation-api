<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Auth\Access\AuthorizationException;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

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

            $resources = Resource::where('business_id', $request->business_id)
                ->where('is_delete', false)
                ->get();

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
                'business_id'           => 'required|exists:businesses,id',

                'resource_type'         => 'required|in:person,room,equipment,timeslot',
                'resource_name'         => 'required|string|max:80',
                'short_code'            => 'nullable|string|max:10|unique:resources,short_code',
                'capacity'              => 'nullable|integer|min:1',
                'photos'                => 'nullable|array',
                'photos.*'              => 'image|mimes:jpg,jpeg,png|max:2048',
                'display_order'         => 'nullable|integer|min:1',
                'description'           => 'nullable|string|max:500',

                'job_title'             => 'nullable|string|max:255',
                'qualification'         => 'nullable|string|max:255',
                'years_experience'      => 'nullable|integer|min:0',
                'languages'             => 'nullable|string',
                'gender'                => 'nullable|in:any,male,female',
                'user_id'               => 'nullable|exists:users,id',
                'bio'                   => 'nullable|string|max:300',

                'custom_attributes'     => 'nullable|array',

                'base_price'            => 'nullable|numeric|min:0',
                'pricing_unit'          => 'nullable|in:fixed,hourly,per_person',
                'buffer_after'          => 'nullable|integer|min:0',
                'cancel_window_hours'   => 'nullable|integer|min:0',
                'max_bookings_per_day'  => 'nullable|integer|min:1',
                'advance_notice_hours'  => 'nullable|integer|min:0',

                'is_active'             => 'nullable|boolean',
                'require_approval'      => 'nullable|boolean',
                'allow_reschedule'      => 'nullable|boolean',
                'allow_online_payment'  => 'nullable|boolean',
                'allow_pay_on_arrival'  => 'nullable|boolean',
                'send_auto_reminders'   => 'nullable|boolean',
                'request_review_after'  => 'nullable|boolean',

                'data'                  => 'nullable|array',
            ]);

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
}