<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'business_id',
        'name',
        'product_code',
        'barcode',
        'description',
        'category',
        'photos',
        'price',
        'cost_price',
        'currency',
        'tax_rate',
        'stock_quantity',
        'low_stock_alert',
        'track_stock',
        'stock_status',
        'is_active',
        'is_delete',
        'created_user',
        'updated_by',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'photos'       => 'array',
            'data'         => 'array',
            'track_stock'  => 'boolean',
            'is_active'    => 'boolean',
            'is_delete'    => 'boolean',
            'price'        => 'decimal:2',
            'cost_price'   => 'decimal:2',
            'tax_rate'     => 'decimal:2',
            'deleted_at'   => 'datetime',
        ];
    }

    //Auto-generate data JSON 

    protected static function booted(): void
    {
        static::creating(function ($product) {
            $product->data         = self::buildData($product);
            $product->stock_status = self::calculateStockStatus($product);
        });

        static::updating(function ($product) {
            $product->data         = self::buildData($product);
            $product->stock_status = self::calculateStockStatus($product);
        });
    }

    private static function buildData($product): array
    {
        return [
            'business_id'     => $product->business_id,
            'name'            => $product->name,
            'sku'             => $product->sku,
            'barcode'         => $product->barcode,
            'description'     => $product->description,
            'category'        => $product->category,
            'price'           => $product->price,
            'cost_price'      => $product->cost_price,
            'currency'        => $product->currency,
            'tax_rate'        => $product->tax_rate,
            'stock_quantity'  => $product->stock_quantity,
            'low_stock_alert' => $product->low_stock_alert,
            'track_stock'     => $product->track_stock,
            'stock_status'    => $product->stock_status,
            'is_active'       => $product->is_active,
        ];
    }

    private static function calculateStockStatus($product): string
    {
        if ($product->stock_quantity <= 0) {
            return 'out_of_stock';
        }
        if ($product->stock_quantity <= $product->low_stock_alert) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    //Relationships 
    // public function orderItems()
    // {
    //     return $this->hasMany(OrderItem::class);
    // }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_user');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    //Helpers 

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->low_stock_alert;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_quantity <= 0;
    }

    public function priceWithTax(): float
    {
        return $this->price + ($this->price * $this->tax_rate / 100);
    }
}