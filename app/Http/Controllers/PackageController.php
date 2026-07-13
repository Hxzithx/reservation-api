<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Exception;

class PackageController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            // new Middleware('auth:sanctum'),
        ];
    }

    public function index(Request $request)
    {
        try {
            // Gate::authorize('viewAny', Package::class);

            $packages = Package::where('business_id', $request->business_id)
                ->where('is_delete', false)
                ->get();

            return response()->json([
                'message'  => 'Packages retrieved successfully',
                'packages' => $packages,
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to view packages',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving packages',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            // Gate::authorize('create', Package::class);

            $fields = $request->validate([
                'company_id'                        => 'required|exists:businesses,id',
                'name'                              => 'required|string|max:255',
                'description'                       => 'nullable|string|max:500',
                'package_type'                      => 'nullable|string|max:255',
                'badge'                             => 'nullable|string|max:100',
                'photos'                            => 'nullable|array',
                'original_price'                    => 'required|numeric|min:0',
                'package_price'                     => 'required|numeric|min:0',
                'currency'                          => 'nullable|in:LKR,USD,EUR,AED',

                // Services
                'services'                          => 'nullable|array',
                'services.*.service_id'             => 'required_with:services|exists:services,id',
                'services.*.quantity'               => 'required_with:services|integer|min:1',
                'services.*.original_price'         => 'required_with:services|numeric|min:0',
                'services.*.package_price'          => 'required_with:services|numeric|min:0',
                'services.*.discount_type'          => 'nullable|string|max:50',

                // Resources
                'resources'                         => 'nullable|array',
                'resources.*.resource_id'           => 'required_with:resources|exists:resources,id',
                'resources.*.quantity'              => 'required_with:resources|integer|min:1',
                'resources.*.original_price'        => 'required_with:resources|numeric|min:0',
                'resources.*.package_price'         => 'required_with:resources|numeric|min:0',
                'resources.*.discount_type'         => 'nullable|string|max:50',

                // Products
                'products'                          => 'nullable|array',
                'products.*.product_id'             => 'required_with:products|exists:products,id',
                'products.*.quantity'               => 'required_with:products|integer|min:1',
                'products.*.original_price'         => 'required_with:products|numeric|min:0',
                'products.*.package_price'          => 'required_with:products|numeric|min:0',
                'products.*.discount_type'          => 'nullable|string|max:50',

                'valid_from'                        => 'nullable|date',
                'valid_until'                       => 'nullable|date|after_or_equal:valid_from',
                'min_quantity'                      => 'nullable|integer|min:1',
                'max_quantity'                      => 'nullable|integer|min:1',
                'is_active'                         => 'nullable|boolean',
            ]);

            $fields['created_user'] = $request->user()?->id;

            $package = Package::create($fields);

            return response()->json([
                'message' => 'Package created successfully',
                'package' => $package->fresh(),
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to create package',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating package',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Package $package)
    {
        try {
            // Gate::authorize('view', $package);

            return response()->json([
                'message' => 'Package retrieved successfully',
                'package' => $package,
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to view package',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Package not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving package',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Package $package)
    {
        try {
            // Gate::authorize('update', $package);

            $fields = $request->validate([
                'name'                              => 'sometimes|required|string|max:255',
                'description'                       => 'sometimes|nullable|string|max:500',
                'package_type'                      => 'sometimes|nullable|string|max:255',
                'badge'                             => 'sometimes|nullable|string|max:100',
                'photos'                            => 'sometimes|nullable|array',
                'original_price'                    => 'sometimes|required|numeric|min:0',
                'package_price'                     => 'sometimes|required|numeric|min:0',
                'currency'                          => 'sometimes|nullable|in:LKR,USD,EUR,AED',

                // Services
                'services'                          => 'sometimes|nullable|array',
                'services.*.service_id'             => 'required_with:services|exists:services,id',
                'services.*.quantity'               => 'required_with:services|integer|min:1',
                'services.*.original_price'         => 'required_with:services|numeric|min:0',
                'services.*.package_price'          => 'required_with:services|numeric|min:0',
                'services.*.discount_type'          => 'nullable|string|max:50',

                // Resources
                'resources'                         => 'sometimes|nullable|array',
                'resources.*.resource_id'           => 'required_with:resources|exists:resources,id',
                'resources.*.quantity'              => 'required_with:resources|integer|min:1',
                'resources.*.original_price'        => 'required_with:resources|numeric|min:0',
                'resources.*.package_price'         => 'required_with:resources|numeric|min:0',
                'resources.*.discount_type'         => 'nullable|string|max:50',

                // Products
                'products'                          => 'sometimes|nullable|array',
                'products.*.product_id'             => 'required_with:products|exists:products,id',
                'products.*.quantity'               => 'required_with:products|integer|min:1',
                'products.*.original_price'         => 'required_with:products|numeric|min:0',
                'products.*.package_price'          => 'required_with:products|numeric|min:0',
                'products.*.discount_type'          => 'nullable|string|max:50',

                'valid_from'                        => 'sometimes|nullable|date',
                'valid_until'                       => 'sometimes|nullable|date|after_or_equal:valid_from',
                'min_quantity'                      => 'sometimes|nullable|integer|min:1',
                'max_quantity'                      => 'sometimes|nullable|integer|min:1',
                'is_active'                         => 'sometimes|nullable|boolean',
            ]);

            $fields['updated_by'] = $request->user()?->id;

            $package->update($fields);

            return response()->json([
                'message' => 'Package updated successfully',
                'package' => $package->fresh(),
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to update package',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Package not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating package',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Package $package)
    {
        try {
            // Gate::authorize('delete', $package);

            $package->update(['is_delete' => true]);
            $package->delete();

            return response()->json([
                'message' => 'Package deleted successfully',
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to delete package',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Package not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (QueryException $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                    'message' => 'Cannot delete package as it is currently in use',
                    'error'   => 'This package is referenced by other records',
                ], 409);
            }

            return response()->json([
                'message' => 'A database error occurred while deleting package',
                'error'   => $e->getMessage(),
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting package',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function active(Request $request)
    {
        try {
            $now = now()->toDateString();

            $packages = Package::where('business_id', $request->business_id)
                ->where('is_delete', false)
                ->where('is_active', true)
                ->where(function ($query) use ($now) {
                    $query->whereNull('valid_from')
                        ->orWhere('valid_from', '<=', $now);
                })
                ->where(function ($query) use ($now) {
                    $query->whereNull('valid_until')
                        ->orWhere('valid_until', '>=', $now);
                })
                ->get();

            return response()->json([
                'message'  => 'Active packages retrieved successfully',
                'packages' => $packages,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving active packages',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
