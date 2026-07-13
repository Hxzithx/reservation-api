<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'package_type',
        'badge',
        'photos',
        'original_price',
        'package_price',
        'discount_amount',
        'discount_percentage',
        'currency',
        'services',
        'resources',
        'products',
        'valid_from',
        'valid_until',
        'min_quantity',
        'max_quantity',
        'is_active',
        'is_delete',
        'created_user',
        'updated_by',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'photos'              => 'array',
            'services'            => 'array',
            'resources'           => 'array',
            'products'            => 'array',
            'data'                => 'array',
            'is_active'           => 'boolean',
            'is_delete'           => 'boolean',
            'original_price'      => 'decimal:2',
            'package_price'       => 'decimal:2',
            'discount_amount'     => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'valid_from'          => 'date',
            'valid_until'         => 'date',
            'deleted_at'          => 'datetime',
        ];
    }

    // ─── Auto-generate ────────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function ($package) {
            $package->package_code = self::generatePackageCode($package);
        });

        static::created(function ($package) {
            Package::withoutEvents(function() use ($package) {
                Package::where('id', $package->id)
                    ->update([
                        'discount_amount'     => self::calculateDiscount($package),
                        'discount_percentage' => self::calculateDiscountPercentage($package),
                        'data'                => self::buildData($package),
                    ]);
            });
        });

        static::updated(function ($package) {
            Package::withoutEvents(function() use ($package) {
                Package::where('id', $package->id)
                    ->update([
                        'discount_amount'     => self::calculateDiscount($package),
                        'discount_percentage' => self::calculateDiscountPercentage($package),
                        'data'                => self::buildData($package),
                    ]);
        });
        });
    }

    private static function generatePackageCode($package): string
    {
        $name   = preg_replace('/[^A-Za-z]/', '', $package->name);
        $prefix = strtoupper(substr($name, 0, 2));
        $year   = now()->format('Y');

        do {
            $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4));
            $code   = "PKG-{$prefix}-{$year}-{$random}";
        } while (self::where('package_code', $code)->exists());

        return $code;
    }

    private static function calculateDiscount($package): float
    {
        return max(0, $package->original_price - $package->package_price);
    }

    private static function calculateDiscountPercentage($package): float
    {
        if ($package->original_price <= 0) return 0;
        return round(
            ($package->original_price - $package->package_price) / $package->original_price * 100,
            2
        );
    }

    private static function buildData($package): array
    {
        return [
            'company_id'          => $package->company_id,
            'name'                => $package->name,
            'package_code'        => $package->package_code,
            'description'         => $package->description,
            'package_type'        => $package->package_type,
            'badge'               => $package->badge,
            'original_price'      => $package->original_price,
            'package_price'       => $package->package_price,
            'discount_amount'     => $package->discount_amount,
            'discount_percentage' => $package->discount_percentage,
            'currency'            => $package->currency,
            'services'            => $package->services,
            'resources'           => $package->resources,
            'products'            => $package->products,
            'valid_from'          => $package->valid_from,
            'valid_until'         => $package->valid_until,
            'min_quantity'        => $package->min_quantity,
            'max_quantity'        => $package->max_quantity,
            'is_active'           => $package->is_active,
        ];
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_user');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function isValid(): bool
    {
        $now = now()->toDateString();
        if ($this->valid_from && $this->valid_from > $now) return false;
        if ($this->valid_until && $this->valid_until < $now) return false;
        return $this->is_active;
    }

    public function savings(): float
    {
        return $this->original_price - $this->package_price;
    }
}