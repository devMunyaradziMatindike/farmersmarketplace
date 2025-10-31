<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed categories first
        $this->call(CategorySeeder::class);

        // Create test users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@farmersmarket.zw',
            'phone_number' => '+263771234567',
            'password' => bcrypt('password'),
            'auth_method' => 'phone',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Test Seller',
            'email' => 'seller@farmersmarket.zw',
            'phone_number' => '+263772345678',
            'password' => bcrypt('password'),
            'auth_method' => 'phone',
            'role' => 'seller',
        ]);

        User::create([
            'name' => 'Test Buyer',
            'email' => 'buyer@farmersmarket.zw',
            'phone_number' => '+263773456789',
            'password' => bcrypt('password'),
            'auth_method' => 'phone',
            'role' => 'buyer',
        ]);

        // Seed demo products
        $this->call(ProductSeeder::class);

        // Seed demo events
        $this->call(EventSeeder::class);
    }
}
