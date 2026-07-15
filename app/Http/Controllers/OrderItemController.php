<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class OrderItemController extends Controller implements HasMiddleware
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
            // Gate::authorize('viewAny', OrderItem::class);

            // $orderItems = OrderItem::where('company_id', $request->company_id)
            //     ->where('is_delete', false)
            //     ->with(['product', 'booking', 'customer'])
            //     ->get();
            $orderItems = OrderItem::all();

            return response()->json([
                'message'     => 'Order items retrieved successfully',
                'order_items' => $orderItems,
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to view order items',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving order items',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Gate::authorize('create', OrderItem::class);

            $fields = $request->validate([
                'company_id' => 'required|exists:businesses,id',
                'booking_id'  => 'required|exists:bookings,id',
                'product_id'  => 'required|exists:products,id',
                'customer_id' => 'nullable|exists:users,id',
                'quantity'    => 'required|integer|min:1',
                'notes'       => 'nullable|string|max:500',
            ]);

            // Get product details
            $product = Product::findOrFail($fields['product_id']);

            // Check if product is active
            if (!$product->is_active) {
                return response()->json([
                    'message' => 'Product is not available',
                ], 409);
            }

            // Check stock availability
            if ($product->track_stock && $product->stock_quantity < $fields['quantity']) {
                return response()->json([
                    'message' => 'Insufficient stock',
                    'available_stock' => $product->stock_quantity,
                ], 409);
            }

            // Check booking exists and is not cancelled/completed
            $booking = Booking::findOrFail($fields['booking_id']);

            if (in_array($booking->status, ['cancelled', 'completed'])) {
                return response()->json([
                    'message' => 'Cannot add products to a ' . $booking->status . ' booking',
                ], 409);
            }

            // Calculate prices
            $unitPrice  = $product->price;
            $taxAmount  = $unitPrice * $fields['quantity'] * ($product->tax_rate / 100);
            $totalPrice = ($unitPrice * $fields['quantity']) + $taxAmount;

            $fields['unit_price']  = $unitPrice;
            $fields['tax_amount']  = $taxAmount;
            $fields['total_price'] = $totalPrice;
            $fields['created_user'] = $request->user()?->id;

            $orderItem = OrderItem::create($fields);

            return response()->json([
                'message'    => 'Order item added successfully',
                'order_item' => $orderItem->load(['product', 'booking']),
            ], 201);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to create order item',
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
                'message' => 'An error occurred while adding order item',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(OrderItem $orderItem)
    {
        try {
            // Gate::authorize('view', $orderItem);

            return response()->json([
                'message'    => 'Order item retrieved successfully',
                'order_item' => $orderItem->load(['product', 'booking', 'customer']),
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to view order item',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Order item not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving order item',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        try {
            // Gate::authorize('update', $orderItem);

            // Cannot update cancelled or delivered items
            if (in_array($orderItem->status, ['cancelled', 'delivered'])) {
                return response()->json([
                    'message' => 'Cannot update a ' . $orderItem->status . ' order item',
                ], 409);
            }

            $fields = $request->validate([
                'quantity' => 'sometimes|required|integer|min:1',
                'notes'    => 'sometimes|nullable|string|max:500',
                'status'   => 'sometimes|required|in:pending,confirmed,delivered,cancelled',
            ]);

            // If quantity changed recalculate prices
            if (isset($fields['quantity'])) {
                $product = $orderItem->product;

                // Check stock
                if ($product->track_stock) {
                    $quantityDiff = $fields['quantity'] - $orderItem->quantity;
                    if ($quantityDiff > 0 && $product->stock_quantity < $quantityDiff) {
                        return response()->json([
                            'message'         => 'Insufficient stock',
                            'available_stock' => $product->stock_quantity,
                        ], 409);
                    }

                    // Adjust stock
                    $product->decrement('stock_quantity', $quantityDiff);
                }

                $fields['unit_price']  = $orderItem->unit_price;
                $fields['tax_amount']  = $orderItem->unit_price * $fields['quantity'] * ($product->tax_rate / 100);
                $fields['total_price'] = ($orderItem->unit_price * $fields['quantity']) + $fields['tax_amount'];
            }

            $fields['updated_by'] = $request->user()?->id;

            $orderItem->update($fields);

            return response()->json([
                'message'    => 'Order item updated successfully',
                'order_item' => $orderItem->fresh()->load(['product', 'booking']),
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to update order item',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Order item not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating order item',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(OrderItem $orderItem)
    {
        try {
            // Gate::authorize('delete', $orderItem);

            // Restore stock if cancelled
            $product = $orderItem->product;
            if ($product && $product->track_stock) {
                $product->increment('stock_quantity', $orderItem->quantity);
            }

            $orderItem->update(['is_delete' => true]);
            $orderItem->delete();

            return response()->json([
                'message' => 'Order item deleted successfully',
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Unauthorized to delete order item',
                'error'   => $e->getMessage(),
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Order item not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting order item',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function byBooking(Booking $booking)
    {
        try {
            $orderItems = OrderItem::where('booking_id', $booking->id)
                ->where('is_delete', false)
                ->with('product')
                ->get();

            $productsTotal = $orderItems->sum('total_price');
            $servicePrice  = $booking->base_price ?? 0;
            $grandTotal    = $servicePrice + $productsTotal;

            return response()->json([
                'message'        => 'Order items retrieved successfully',
                'order_items'    => $orderItems,
                'service_price'  => $servicePrice,
                'products_total' => $productsTotal,
                'grand_total'    => $grandTotal,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Booking not found',
                'error'   => $e->getMessage(),
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving order items',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function updateStatus(Request $request, OrderItem $orderItem)
    {
        try {
            $fields = $request->validate([
                'status' => 'required|in:pending,confirmed,delivered,cancelled',
            ]);

            // Restore stock if cancelled
            if ($fields['status'] === 'cancelled' && $orderItem->status !== 'cancelled') {
                $product = $orderItem->product;
                if ($product && $product->track_stock) {
                    $product->increment('stock_quantity', $orderItem->quantity);
                }
            }

            $orderItem->update([
                'status'     => $fields['status'],
                'updated_by' => $request->user()?->id,
            ]);

            return response()->json([
                'message'    => 'Order item status updated successfully',
                'order_item' => $orderItem->fresh(),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error'   => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating order item status',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}