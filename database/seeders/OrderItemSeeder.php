<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use App\Models\Product;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orderItems = [
            [
                'company_id' => 1,
                'booking_id'  => 1,
                'product_id'  => 1,  
                'customer_id' => 4,
                'quantity'    => 2,
                'unit_price'  => 1500.00,
                'tax_amount'  => 300.00,  // 10% tax on 2 bottles
                'total_price' => 3300.00,
                'status'      => 'delivered',
                'notes'       => 'Delivered to room 101',
                'is_delete'   => false,
            ],
            [
                'company_id' => 1,
                'booking_id'  => 1,
                'product_id'  => 2,  // Water
                'customer_id' => 4,
                'quantity'    => 5,
                'unit_price'  => 150.00,
                'tax_amount'  => 0.00,
                'total_price' => 750.00,
                'status'      => 'delivered',
                'notes'       => null,
                'is_delete'   => false,
            ],
            [
                'company_id' => 1,
                'booking_id'  => 1,
                'product_id'  => 3,  // Beer
                'customer_id' => 4,
                'quantity'    => 3,
                'unit_price'  => 650.00,
                'tax_amount'  => 195.00, // 10% tax on 3 bottles
                'total_price' => 2145.00,
                'status'      => 'pending',
                'notes'       => 'Customer requested cold',
                'is_delete'   => false,
            ],
            [
                'company_id' => 1,
                'booking_id'  => 2,
                'product_id'  => 4,  // Chocolate
                'customer_id' => 5,
                'quantity'    => 2,
                'unit_price'  => 350.00,
                'tax_amount'  => 0.00,
                'total_price' => 700.00,
                'status'      => 'delivered',
                'notes'       => null,
                'is_delete'   => false,
            ],
        ];

        foreach ($orderItems as $item) {
            OrderItem::create($item);
        }
    }
}