<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                // 'company_id'     => 1,
                'name'            => 'Arrack',
                'product_code'    => 'ARR-001',
                'barcode'         => '6901234567001',
                'description'     => 'Local arrack bottle 750ml',
                'category'        => 'Beverages',
                'price'           => 1500.00,
                'cost_price'      => 1000.00,
                'currency'        => 'LKR',
                'tax_rate'        => 10.00,
                'stock_quantity'  => 50,
                'low_stock_alert' => 10,
                'track_stock'     => true,
                'is_active'       => true,
                'is_delete'       => false,
            ],
            [
                // 'company_id'     => 1,
                'name'            => 'Water Bottle 500ml',
                'product_code'    => 'WTR-500',
                'barcode'         => '6901234567002',
                'description'     => 'Mineral water 500ml',
                'category'        => 'Beverages',
                'price'           => 150.00,
                'cost_price'      => 80.00,
                'currency'        => 'LKR',
                'tax_rate'        => 0.00,
                'stock_quantity'  => 200,
                'low_stock_alert' => 20,
                'track_stock'     => true,
                'is_active'       => true,
                'is_delete'       => false,
            ],
            [
                // 'company_id'     => 1,
                'name'            => 'Beer',
                'product_code'    => 'BEE-001',
                'barcode'         => '6901234567003',
                'description'     => 'Local beer bottle 330ml',
                'category'        => 'Beverages',
                'price'           => 650.00,
                'cost_price'      => 450.00,
                'currency'        => 'LKR',
                'tax_rate'        => 10.00,
                'stock_quantity'  => 100,
                'low_stock_alert' => 15,
                'track_stock'     => true,
                'is_active'       => true,
                'is_delete'       => false,
            ],
            [
                // 'company_id'     => 1,
                'name'            => 'Chocolate Bar',
                'product_code'    => 'CHO-001',
                'barcode'         => '6901234567004',
                'description'     => 'Milk chocolate bar 100g',
                'category'        => 'Snacks',
                'price'           => 350.00,
                'cost_price'      => 200.00,
                'currency'        => 'LKR',
                'tax_rate'        => 0.00,
                'stock_quantity'  => 75,
                'low_stock_alert' => 10,
                'track_stock'     => true,
                'is_active'       => true,
                'is_delete'       => false,
            ],
            [
                // 'company_id'     => 1,
                'name'            => 'Shampoo',
                'product_code'    => 'SHP-001',
                'barcode'         => '6901234567005',
                'description'     => 'Hotel shampoo 50ml',
                'category'        => 'Toiletries',
                'price'           => 250.00,
                'cost_price'      => 150.00,
                'currency'        => 'LKR',
                'tax_rate'        => 0.00,
                'stock_quantity'  => 150,
                'low_stock_alert' => 20,
                'track_stock'     => true,
                'is_active'       => true,
                'is_delete'       => false,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}