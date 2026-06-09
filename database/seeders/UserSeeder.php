<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'         => 'Admin User',
                'email'        => 'admin@example.com',
                'role'         => 'admin',
                'phone'        => '+94771234567',
                'is_active'    => true,
                'is_delete'    => false,
            ],
            [
                'name'         => 'John Silva',
                'email'        => 'john@example.com',
                'role'         => 'receptionist',
                'phone'        => '+94772345678',
                'is_active'    => true,
                'is_delete'    => false,
            ],
            [
                'name'         => 'Jane Perera',
                'email'        => 'jane@example.com',
                'role'         => 'receptionist',
                'phone'        => '+94773456789',
                'is_active'    => true,
                'is_delete'    => false,
            ],
            [
                'name'         => 'Kasun Fernando',
                'email'        => 'kasun@example.com',
                'role'         => 'receptionist',
                'phone'        => '+94774567890',
                'is_active'    => true,
                'is_delete'    => false,
            ],
            [
                'name'         => 'Nimali Dias',
                'email'        => 'nimali@example.com',
                'role'         => 'receptionist',
                'phone'        => '+94775678901',
                'is_active'    => true,
                'is_delete'    => false,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
