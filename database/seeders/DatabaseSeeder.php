<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat Admin
        User::create([
            'name' => 'Admin F4uziahtailor',
            'email' => 'admin@tailor.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone_number' => '081234567890'
        ]);

        // Membuat User
        User::create([
            'name' => 'Fajril Maulid',
            'email' => 'fajril@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone_number' => '089876543210'
        ]);

        // Memanggil Seeder Kategori, Produk, Order, dan Settings
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
