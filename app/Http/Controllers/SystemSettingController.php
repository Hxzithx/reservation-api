<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class SystemSettingController extends Controller implements HasMiddleware
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
            Gate::authorize('viewAny', SystemSetting::class);

            // $setting = SystemSetting::where('is_delete', false)->first();
            $setting = SystemSetting::all();

            if (!$setting) {
                return response()->json([
                    'message' => 'System settings not found',
                ], 404);
            }

            return response()->json([
                'message' => 'System settings retrieved successfully',
                'setting' => $setting,
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to view system settings',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving system settings',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Gate::authorize('create', SystemSetting::class);

            // Only one system setting record allowed
            if (SystemSetting::where('is_delete', false)->exists()) {
                return response()->json([
                    'message' => 'System settings already exist. Use update instead.',
                ], 409);
            }

            $fields = $request->validate([
                'company_name'          => 'required|string|max:255',
                'email'                 => 'nullable|email|max:255',
                'mobile_number'         => 'nullable|string|max:20',
                'telephone_number'      => 'nullable|string|max:20',
                'website'               => 'nullable|url|max:255',
                'address_line_1'        => 'nullable|string|max:255',
                'address_line_2'        => 'nullable|string|max:255',
                'address_line_3'        => 'nullable|string|max:255',
                'city'                  => 'nullable|string|max:100',
                'district'              => 'nullable|string|max:100',
                'province_state'        => 'nullable|string|max:100',
                'postal_code'           => 'nullable|string|max:20',
                'country'               => 'nullable|string|max:100',
                'system_currency'       => 'nullable|in:LKR,USD,EUR,AED',
                'header_display_type'   => 'nullable|in:company_logo,custom_text',
                'company_logo'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'company_slogan'        => 'nullable|string|max:255',
                'slogan_font_size'      => 'nullable|integer|min:8|max:72',
                'document_header_text'  => 'nullable|string|max:255',
                'header_font_size'      => 'nullable|integer|min:8|max:72',
                'theme_color'           => 'nullable|string|max:7',
                'bank_details'          => 'nullable|string',
            ]);

            // Handle logo upload
            if ($request->hasFile('company_logo')) {
                $fields['company_logo'] = $request->file('company_logo')->store('logos', 'public');
            }

            $fields['created_user'] = $request->user()->id;

            $setting = SystemSetting::create($fields);

            return response()->json([
                'message' => 'System settings created successfully',
                'setting' => $setting,
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to create system settings',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating system settings',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, SystemSetting $systemSetting)
    {
        try {
            Gate::authorize('update', $systemSetting);

            $fields = $request->validate([
                'company_name'          => 'sometimes|required|string|max:255',
                'email'                 => 'sometimes|nullable|email|max:255',
                'mobile_number'         => 'sometimes|nullable|string|max:20',
                'telephone_number'      => 'sometimes|nullable|string|max:20',
                'website'               => 'sometimes|nullable|url|max:255',
                'address_line_1'        => 'sometimes|nullable|string|max:255',
                'address_line_2'        => 'sometimes|nullable|string|max:255',
                'address_line_3'        => 'sometimes|nullable|string|max:255',
                'city'                  => 'sometimes|nullable|string|max:100',
                'district'              => 'sometimes|nullable|string|max:100',
                'province_state'        => 'sometimes|nullable|string|max:100',
                'postal_code'           => 'sometimes|nullable|string|max:20',
                'country'               => 'sometimes|nullable|string|max:100',
                'system_currency'       => 'sometimes|nullable|in:LKR,USD,EUR,AED',
                'header_display_type'   => 'sometimes|nullable|in:company_logo,custom_text',
                'company_logo'          => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
                'company_slogan'        => 'sometimes|nullable|string|max:255',
                'slogan_font_size'      => 'sometimes|nullable|integer|min:8|max:72',
                'document_header_text'  => 'sometimes|nullable|string|max:255',
                'header_font_size'      => 'sometimes|nullable|integer|min:8|max:72',
                'theme_color'           => 'sometimes|nullable|string|max:7',
                'bank_details'          => 'sometimes|nullable|string',
            ]);

            // Handle logo upload
            if ($request->hasFile('company_logo')) {
                $fields['company_logo'] = $request->file('company_logo')->store('logos', 'public');
            }

            $fields['updated_by'] = $request->user()->id;

            $systemSetting->update($fields);

            return response()->json([
                'message' => 'System settings updated successfully',
                'setting' => $systemSetting->fresh(),
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to update system settings',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'System settings not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating system settings',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(SystemSetting $systemSetting)
    {
        try {
            Gate::authorize('delete', $systemSetting);

            $systemSetting->update(['is_delete' => true]);
            $systemSetting->delete();

            return response()->json([
                'message' => 'System settings deleted successfully',
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to delete system settings',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'System settings not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting system settings',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}