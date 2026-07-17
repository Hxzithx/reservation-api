<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'company_id'     => 1,
                'name'           => 'Honeymoon Special',
                'description'    => 'A romantic package for couples including deluxe room, spa and complimentary drinks',
                'package_type'   => 'Romantic Package',
                'badge'          => 'Most Popular',
                'original_price' => 21500.00,
                'package_price'  => 15000.00,
                'currency'       => 'LKR',
                'services'       => [
                    [
                        'service_id'     => 1,
                        'name'           => 'Couples Massage',
                        'quantity'       => 1,
                        'original_price' => 7000.00,
                        'package_price'  => 5000.00,
                        'discount_type'  => 'fixed',
                    ],
                ],
                'resources'      => [
                    [
                        'resource_id'    => 1,
                        'name'           => 'Deluxe Suite',
                        'quantity'       => 1,
                        'original_price' => 12000.00,
                        'package_price'  => 9000.00,
                        'nights'         => 2,
                        'discount_type'  => 'bundle',
                    ],
                ],
                'products'       => [
                    [
                        'product_id'     => 1,
                        'name'           => 'Arrack',
                        'quantity'       => 1,
                        'original_price' => 1500.00,
                        'package_price'  => 0.00,
                        'discount_type'  => 'free',
                    ],
                    [
                        'product_id'     => 4,
                        'name'           => 'Chocolate Bar',
                        'quantity'       => 2,
                        'original_price' => 700.00,
                        'package_price'  => 0.00,
                        'discount_type'  => 'free',
                    ],
                    [
                        'product_id'     => 2,
                        'name'           => 'Water Bottle',
                        'quantity'       => 4,
                        'original_price' => 600.00,
                        'package_price'  => 0.00,
                        'discount_type'  => 'free',
                    ],
                ],
                'valid_from'     => '2026-01-01',
                'valid_until'    => '2026-12-31',
                'min_quantity'   => 1,
                'max_quantity'   => null,
                'is_active'      => true,
                'is_delete'      => false,
            ],
            [
                'company_id'    => 1,
                'name'           => 'Weekend Room Deal',
                'description'    => 'Book 2 rooms for the weekend and save big',
                'package_type'   => 'Room Bundle',
                'badge'          => 'Best Value',
                'original_price' => 10000.00,
                'package_price'  => 8000.00,
                'currency'       => 'LKR',
                'services'       => [],
                'resources'      => [
                    [
                        'resource_id'    => 1,
                        'name'           => 'Deluxe Suite',
                        'quantity'       => 1,
                        'original_price' => 5000.00,
                        'package_price'  => 4000.00,
                        'nights'         => 1,
                        'discount_type'  => 'bundle',
                    ],
                    [
                        'resource_id'    => 2,
                        'name'           => 'Standard Room',
                        'quantity'       => 1,
                        'original_price' => 5000.00,
                        'package_price'  => 4000.00,
                        'nights'         => 1,
                        'discount_type'  => 'bundle',
                    ],
                ],
                'products'       => [
                    [
                        'product_id'     => 2,
                        'name'           => 'Water Bottle',
                        'quantity'       => 4,
                        'original_price' => 600.00,
                        'package_price'  => 0.00,
                        'discount_type'  => 'free',
                    ],
                ],
                'valid_from'     => '2026-01-01',
                'valid_until'    => '2026-12-31',
                'min_quantity'   => 2,
                'max_quantity'   => null,
                'is_active'      => true,
                'is_delete'      => false,
            ],
            [
                'company_id'    => 1,
                'name'           => 'Spa Day Package',
                'description'    => 'Full day spa experience with haircut massage and manicure',
                'package_type'   => 'Service Bundle',
                'badge'          => 'New',
                'original_price' => 7000.00,
                'package_price'  => 5500.00,
                'currency'       => 'LKR',
                'services'       => [
                    [
                        'service_id'     => 1,
                        'name'           => 'Haircut',
                        'quantity'       => 1,
                        'original_price' => 1500.00,
                        'package_price'  => 1200.00,
                        'discount_type'  => 'fixed',
                    ],
                    [
                        'service_id'     => 2,
                        'name'           => 'Massage',
                        'quantity'       => 1,
                        'original_price' => 3500.00,
                        'package_price'  => 2800.00,
                        'discount_type'  => 'fixed',
                    ],
                    [
                        'service_id'     => 3,
                        'name'           => 'Manicure',
                        'quantity'       => 1,
                        'original_price' => 2000.00,
                        'package_price'  => 1500.00,
                        'discount_type'  => 'fixed',
                    ],
                ],
                'resources'      => [],
                'products'       => [
                    [
                        'product_id'     => 2,
                        'name'           => 'Water Bottle',
                        'quantity'       => 2,
                        'original_price' => 300.00,
                        'package_price'  => 0.00,
                        'discount_type'  => 'free',
                    ],
                ],
                'valid_from'     => null,
                'valid_until'    => null,
                'min_quantity'   => 1,
                'max_quantity'   => null,
                'is_active'      => true,
                'is_delete'      => false,
            ],
            [
                'company_id'    => 1,
                'name'           => 'Mini Bar Bundle',
                'description'    => 'Buy 3 drinks and get 1 snack free',
                'package_type'   => 'Product Bundle',
                'badge'          => 'Limited Time',
                'original_price' => 3050.00,
                'package_price'  => 2800.00,
                'currency'       => 'LKR',
                'services'       => [],
                'resources'      => [],
                'products'       => [
                    [
                        'product_id'     => 1,
                        'name'           => 'Arrack',
                        'quantity'       => 1,
                        'original_price' => 1500.00,
                        'package_price'  => 1500.00,
                        'discount_type'  => 'none',
                    ],
                    [
                        'product_id'     => 3,
                        'name'           => 'Beer',
                        'quantity'       => 2,
                        'original_price' => 1300.00,
                        'package_price'  => 1300.00,
                        'discount_type'  => 'none',
                    ],
                    [
                        'product_id'     => 4,
                        'name'           => 'Chocolate Bar',
                        'quantity'       => 1,
                        'original_price' => 350.00,
                        'package_price'  => 0.00,
                        'discount_type'  => 'free',
                    ],
                ],
                'valid_from'     => '2026-06-01',
                'valid_until'    => '2026-08-31',
                'min_quantity'   => 1,
                'max_quantity'   => 5,
                'is_active'      => true,
                'is_delete'      => false,
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
    
}
