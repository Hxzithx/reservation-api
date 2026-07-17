<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            SystemSettingSeeder::class,
            ServiceSeeder::class,
            ResourceSeeder::class,
            ProductSeeder::class,
            PackageSeeder::class,
        ]);
    }
}
