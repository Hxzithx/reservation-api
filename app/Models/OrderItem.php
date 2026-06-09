<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'booking_id',
        'product_id',
        'customer_id',
        'quantity',
        'unit_price',
        'tax_amount',
        'total_price',
        'status',
        'notes',
        'is_delete',
        'created_user',
        'updated_by',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'data'        => 'array',
            'unit_price'  => 'decimal:2',
            'tax_amount'  => 'decimal:2',
            'total_price' => 'decimal:2',
            'is_delete'   => 'boolean',
            'deleted_at'  => 'datetime',
        ];
    }

    //Auto calculate total 

    protected static function booted(): void
{
    static::creating(function ($item) {
        $item->tax_amount  = $item->unit_price * $item->quantity * (optional(Product::find($item->product_id))->tax_rate / 100 ?? 0);
        $item->total_price = ($item->unit_price * $item->quantity) + $item->tax_amount;

        $item->data = self::buildData($item);

        $product = Product::find($item->product_id);
        if ($product && $product->track_stock) {
            $product->decrement('stock_quantity', $item->quantity);
        }
    });

    static::updating(function ($item) {
        $item->total_price = ($item->unit_price * $item->quantity) + $item->tax_amount;
        $item->data = self::buildData($item);
    });
}

private static function buildData($item): array
{
    return [
        'company_id' => $item->company_id,
        'booking_id'  => $item->booking_id,
        'product_id'  => $item->product_id,
        'customer_id' => $item->customer_id,
        'quantity'    => $item->quantity,
        'unit_price'  => $item->unit_price,
        'tax_amount'  => $item->tax_amount,
        'total_price' => $item->total_price, 
        'status'      => $item->status,
        'notes'       => $item->notes,
    ];
}
}