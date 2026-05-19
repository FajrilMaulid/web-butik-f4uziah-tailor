<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->info('Please run CategorySeeder first.');
            return;
        }

        $gamis = $categories->where('slug', 'gamis')->first();
        $casual = $categories->where('slug', 'casual')->first();
        $formal = $categories->where('slug', 'formal')->first();

        $products = [
            [
                'category_id' => $gamis->id ?? 1,
                'name' => 'Baju Gamis Ayana',
                'price' => 300000,
                'description' => 'Gamis berpotongan A-line dari Premium Linen yang sejuk, jatuh alami, dan memancarkan keanggunan minimalis.',
                'image' => null, // Placeholder image will be used in view
            ],
            [
                'category_id' => $gamis->id ?? 1,
                'name' => 'Gamis Syari Elegan',
                'price' => 350000,
                'description' => 'Gamis syari dengan bahan ceruti babydoll yang lembut dan flowy.',
                'image' => null,
            ],
            [
                'category_id' => $casual->id ?? 2,
                'name' => 'Kemeja Casual Pria',
                'price' => 200000,
                'description' => 'Kemeja lengan pendek bahan katun rayon yang nyaman dipakai sehari-hari.',
                'image' => null,
            ],
            [
                'category_id' => $casual->id ?? 2,
                'name' => 'Tunik Santai Wanita',
                'price' => 220000,
                'description' => 'Tunik wanita dengan desain modern yang cocok untuk acara santai.',
                'image' => null,
            ],
            [
                'category_id' => $formal->id ?? 3,
                'name' => 'Setelan Jas Pria',
                'price' => 850000,
                'description' => 'Setelan jas pria bahan wool premium untuk acara resmi dan pernikahan.',
                'image' => null,
            ],
            [
                'category_id' => $formal->id ?? 3,
                'name' => 'Blazer Kantor Wanita',
                'price' => 450000,
                'description' => 'Blazer kerja elegan dengan potongan pas badan (slim fit).',
                'image' => null,
            ],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, [
                'slug' => Str::slug($product['name']) . '-' . rand(100, 999)
            ]));
        }
    }
}
