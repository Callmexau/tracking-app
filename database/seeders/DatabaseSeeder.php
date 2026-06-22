<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $user = User::create([
        'first_name' => 'Claude',
        'last_name' => 'Yoka',
        'email' => 'admin@test.com',
        'email_verified_at' => now(),
        'password' => bcrypt('password123'),
        'is_active' => true,
    ]);

        $user->assignRole('Super Admin');
    }
}