<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            // new Middleware('auth:sanctum'),
        ];
    }

    // GET /products
    public function index(Request $request)
    {
        try {
            // Gate::authorize('viewAny', Product::class);

            // $products = Product::where('business_id', $request->business_id)
            //     ->where('is_delete', false)
            //     ->get();
            $products = Product::all();

            return response()->json([
                'message'  => 'Products retrieved successfully',
                'products' => $products,
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to view products',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving products',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // POST /products
    public function store(Request $request)
    {
        try {
            // Gate::authorize('create', Product::class);

            $fields = $request->validate([
                'business_id'     => 'required|exists:businesses,id',
                'name'            => 'required|string|max:255',
                'product_code'    => 'nullable|string|max:50|unique:products,product_code',
                'barcode'         => 'nullable|string|max:50|unique:products,barcode',
                'description'     => 'nullable|string|max:500',
                'category'        => 'nullable|string|max:255',
                'photos'          => 'nullable|array',
                'photos.*'        => 'image|mimes:jpg,jpeg,png|max:2048',
                'price'           => 'required|numeric|min:0',
                'cost_price'      => 'nullable|numeric|min:0',
                'currency'        => 'nullable|in:LKR,USD,EUR,AED',
                'tax_rate'        => 'nullable|numeric|min:0|max:100',
                'stock_quantity'  => 'nullable|integer|min:0',
                'low_stock_alert' => 'nullable|integer|min:0',
                'track_stock'     => 'nullable|boolean',
                'is_active'       => 'nullable|boolean',
            ]);

            // Handle photo uploads
            if ($request->hasFile('photos')) {
                $paths = [];
                foreach ($request->file('photos') as $photo) {
                    $paths[] = $photo->store('products', 'public');
                }
                $fields['photos'] = $paths;
            }

            $fields['created_user'] = $request->user()?->id;

            $product = Product::create($fields);

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product,
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to create product',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating product',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // GET /products/{product}
    public function show(Product $product)
    {
        try {
            // Gate::authorize('view', $product);

            return response()->json([
                'message' => 'Product retrieved successfully',
                'product' => $product,
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to view product',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Product not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving product',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // PUT /products/{product}
    public function update(Request $request, Product $product)
    {
        try {
            // Gate::authorize('update', $product);

            $fields = $request->validate([
                'name'            => 'sometimes|required|string|max:255',
                'product_code'    => 'sometimes|nullable|string|max:50|unique:products,product_code,' . $product->id,
                'barcode'         => 'sometimes|nullable|string|max:50|unique:products,barcode,' . $product->id,
                'description'     => 'sometimes|nullable|string|max:500',
                'category'        => 'sometimes|nullable|string|max:255',
                'photos'          => 'sometimes|nullable|array',
                'photos.*'        => 'image|mimes:jpg,jpeg,png|max:2048',
                'price'           => 'sometimes|required|numeric|min:0',
                'cost_price'      => 'sometimes|nullable|numeric|min:0',
                'currency'        => 'sometimes|nullable|in:LKR,USD,EUR,AED',
                'tax_rate'        => 'sometimes|nullable|numeric|min:0|max:100',
                'stock_quantity'  => 'sometimes|nullable|integer|min:0',
                'low_stock_alert' => 'sometimes|nullable|integer|min:0',
                'track_stock'     => 'sometimes|nullable|boolean',
                'is_active'       => 'sometimes|nullable|boolean',
            ]);

            // Handle photo uploads
            if ($request->hasFile('photos')) {
                $paths = [];
                foreach ($request->file('photos') as $photo) {
                    $paths[] = $photo->store('products', 'public');
                }
                $fields['photos'] = $paths;
            }

            $fields['updated_by'] = $request->user()?->id;

            $product->update($fields);

            return response()->json([
                'message' => 'Product updated successfully',
                'product' => $product->fresh(),
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to update product',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Product not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating product',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // DELETE /products/{product}
    public function destroy(Product $product)
    {
        try {
            // Gate::authorize('delete', $product);

            $product->update(['is_delete' => true]);
            $product->delete();

            return response()->json([
                'message' => 'Product deleted successfully',
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to delete product',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Product not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            if (
                strpos($e->getMessage(), 'foreign key constraint') !== false ||
                strpos($e->getMessage(), 'SQLSTATE[23000]') !== false
            ) {
                return response()->json([
                    'message' => 'Cannot delete product as it is currently in use',
                    'error'   => 'This product is linked to one or more orders',
                ], 409);
            }
            return response()->json([
                'message' => 'An error occurred while deleting product',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // PATCH /products/{product}/stock
    public function updateStock(Request $request, Product $product)
    {
        try {
            $fields = $request->validate([
                'stock_quantity' => 'required|integer|min:0',
            ]);

            $product->update($fields);

            return response()->json([
                'message'        => 'Stock updated successfully',
                'product'        => $product->fresh(),
                'stock_status'   => $product->fresh()->stock_status,
                'stock_quantity' => $product->fresh()->stock_quantity,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating stock',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // GET /products/low-stock
    public function lowStock(Request $request)
    {
        try {
            $products = Product::where('business_id', $request->business_id)
                ->where('is_delete', false)
                ->where('track_stock', true)
                ->whereIn('stock_status', ['low_stock', 'out_of_stock'])
                ->get();

            return response()->json([
                'message'  => 'Low stock products retrieved successfully',
                'products' => $products,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving low stock products',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}