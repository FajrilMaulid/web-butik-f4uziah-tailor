<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('role', 'user')->first();
        $products = Product::inRandomOrder()->take(3)->get();

        if (!$user || $products->isEmpty()) {
            $this->command->info('Tidak ada user atau produk untuk membuat order dummy.');
            return;
        }

        $orders = [
            [
                'user_id' => $user->id,
                'product_id' => $products->get(0)->id ?? 1,
                'total_price' => ($products->get(0)->price ?? 300000) * 1,
                'status' => 'menunggu_pembayaran',
                'payment_status' => 'unpaid',
                'notes' => 'Ukuran: M, Jumlah: 1',
            ],
            [
                'user_id' => $user->id,
                'product_id' => $products->get(1)->id ?? 2,
                'total_price' => ($products->get(1)->price ?? 200000) * 2,
                'status' => 'menunggu',
                'payment_status' => 'uploaded',
                'reference_image' => 'payment_proofs/dummy.jpg',
                'notes' => 'Ukuran: XL, Jumlah: 2. Mohon dipercepat kak.',
            ],
            [
                'user_id' => $user->id,
                'product_id' => $products->get(2)->id ?? 3,
                'total_price' => ($products->get(2)->price ?? 450000) * 1,
                'status' => 'proses',
                'payment_status' => 'confirmed',
                'notes' => 'Ukuran: Custom, Jumlah: 1',
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
    }
}
