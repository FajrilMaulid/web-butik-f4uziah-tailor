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

        $gamis  = $categories->where('slug', 'gamis')->first();
        $casual = $categories->where('slug', 'casual')->first();
        $formal = $categories->where('slug', 'formal')->first();

        $products = [
            // Gamis (10 produk)
            [
                'category_id' => $gamis->id ?? 1,
                'name'        => 'Gamis Ayana Premium',
                'price'       => 300000,
                'description' => 'Gamis berpotongan A-line dari Premium Linen yang sejuk, jatuh alami, dan memancarkan keanggunan minimalis.',
            ],
            [
                'category_id' => $gamis->id ?? 1,
                'name'        => 'Gamis Syari Elegan',
                'price'       => 350000,
                'description' => 'Gamis syari dengan bahan ceruti babydoll yang lembut dan flowy, cocok untuk segala acara.',
            ],
            [
                'category_id' => $gamis->id ?? 1,
                'name'        => 'Gamis Batik Pesisir',
                'price'       => 420000,
                'description' => 'Gamis berbahan batik tulis pesisir dengan motif khas Jawa yang kaya makna dan filosofi.',
            ],
            [
                'category_id' => $gamis->id ?? 1,
                'name'        => 'Gamis Bordir Cantik',
                'price'       => 475000,
                'description' => 'Gamis dengan detail bordir bunga halus di bagian dada dan lengan, tampil anggun di setiap kesempatan.',
            ],
            [
                'category_id' => $gamis->id ?? 1,
                'name'        => 'Gamis Velvet Mewah',
                'price'       => 580000,
                'description' => 'Gamis berbahan velvet berkualitas tinggi yang memberikan kesan mewah dan elegan saat dikenakan.',
            ],
            [
                'category_id' => $gamis->id ?? 1,
                'name'        => 'Gamis Organza Ruffles',
                'price'       => 390000,
                'description' => 'Gamis dengan aksen ruffles organza yang memberikan kesan feminin dan anggun, sempurna untuk pesta.',
            ],
            [
                'category_id' => $gamis->id ?? 1,
                'name'        => 'Gamis Kaftan Arab',
                'price'       => 450000,
                'description' => 'Gamis gaya kaftan dengan inspirasi Timur Tengah, bahan rayon sutra yang jatuh dan nyaman.',
            ],
            [
                'category_id' => $gamis->id ?? 1,
                'name'        => 'Gamis Midi Knit',
                'price'       => 320000,
                'description' => 'Gamis midi berbahan rajut (knit) yang elastis, hangat, dan tetap tampil modis di segala cuaca.',
            ],
            [
                'category_id' => $gamis->id ?? 1,
                'name'        => 'Gamis Pleats Modern',
                'price'       => 365000,
                'description' => 'Gamis dengan detail pleat di rok yang memberikan volume dan keindahan gerak saat melangkah.',
            ],
            [
                'category_id' => $gamis->id ?? 1,
                'name'        => 'Gamis Satin Duchess',
                'price'       => 520000,
                'description' => 'Gamis bahan satin duchess premium dengan kilap lembut, ideal untuk acara pernikahan dan pesta resmi.',
            ],

            // Casual (10 produk)
            [
                'category_id' => $casual->id ?? 2,
                'name'        => 'Kemeja Casual Pria Katun',
                'price'       => 200000,
                'description' => 'Kemeja lengan pendek bahan katun rayon yang nyaman dan breathable untuk aktivitas sehari-hari.',
            ],
            [
                'category_id' => $casual->id ?? 2,
                'name'        => 'Tunik Santai Wanita',
                'price'       => 220000,
                'description' => 'Tunik wanita dengan desain modern dan bahan katun adem, cocok untuk jalan-jalan maupun kerja santai.',
            ],
            [
                'category_id' => $casual->id ?? 2,
                'name'        => 'Kaos Polo Premium',
                'price'       => 185000,
                'description' => 'Kaos polo berbahan pique cotton berkualitas, nyaman dipakai seharian dengan tampilan tetap rapi.',
            ],
            [
                'category_id' => $casual->id ?? 2,
                'name'        => 'Dress Casual Floral',
                'price'       => 270000,
                'description' => 'Dress kasual bermotif bunga dengan bahan viscose lembut, ringan, dan cocok untuk acara santai outdoor.',
            ],
            [
                'category_id' => $casual->id ?? 2,
                'name'        => 'Celana Chino Slim',
                'price'       => 195000,
                'description' => 'Celana chino slim fit berbahan cotton stretch yang nyaman bergerak dengan berbagai pilihan warna netral.',
            ],
            [
                'category_id' => $casual->id ?? 2,
                'name'        => 'Blouse Tie-Dye Unik',
                'price'       => 215000,
                'description' => 'Blouse tie-dye dengan warna-warna cerah yang unik, tampil beda dan ekspresif untuk gaya sehari-hari.',
            ],
            [
                'category_id' => $casual->id ?? 2,
                'name'        => 'Jaket Denim Casual',
                'price'       => 340000,
                'description' => 'Jaket denim ringan dengan potongan boxy yang trendi, mudah dipadukan dengan berbagai outfit.',
            ],
            [
                'category_id' => $casual->id ?? 2,
                'name'        => 'Rok Midi A-Line',
                'price'       => 230000,
                'description' => 'Rok midi berpotongan A-line yang flattering di semua bentuk tubuh, bahan katun linen yang sejuk.',
            ],
            [
                'category_id' => $casual->id ?? 2,
                'name'        => 'Kemeja Flannel Kotak',
                'price'       => 210000,
                'description' => 'Kemeja flannel bermotif kotak dengan bahan tebal yang hangat, sempurna untuk musim hujan.',
            ],
            [
                'category_id' => $casual->id ?? 2,
                'name'        => 'Cardigan Rajut Tipis',
                'price'       => 255000,
                'description' => 'Cardigan rajut tipis yang versatile, mudah dilayer dengan berbagai pakaian dalam dan luar.',
            ],

            // Formal (10 produk)
            [
                'category_id' => $formal->id ?? 3,
                'name'        => 'Setelan Jas Pria Classic',
                'price'       => 850000,
                'description' => 'Setelan jas pria bahan wool premium untuk acara resmi, pernikahan, dan pertemuan bisnis penting.',
            ],
            [
                'category_id' => $formal->id ?? 3,
                'name'        => 'Blazer Kantor Wanita',
                'price'       => 450000,
                'description' => 'Blazer kerja elegan dengan potongan pas badan (slim fit), bahan stretch crepe yang nyaman seharian.',
            ],
            [
                'category_id' => $formal->id ?? 3,
                'name'        => 'Gaun Pesta Malam',
                'price'       => 750000,
                'description' => 'Gaun pesta malam berbahan sifon dengan detail payet yang berkilau, tampil memukau di setiap acara.',
            ],
            [
                'category_id' => $formal->id ?? 3,
                'name'        => 'Kemeja Formal Putih',
                'price'       => 280000,
                'description' => 'Kemeja formal putih berbahan poplin premium dengan potongan slim fit, wajib ada di lemari pakaian.',
            ],
            [
                'category_id' => $formal->id ?? 3,
                'name'        => 'Rok Pensil Formal',
                'price'       => 320000,
                'description' => 'Rok pensil berbahan tetoron berkualitas dengan belahan belakang, tampil profesional dan elegan di kantor.',
            ],
            [
                'category_id' => $formal->id ?? 3,
                'name'        => 'Jas Tuxedo Mewah',
                'price'       => 1200000,
                'description' => 'Jas tuxedo mewah berbahan wool-polyester premium dengan kerah lapel mengkilap untuk acara gala dan malam.',
            ],
            [
                'category_id' => $formal->id ?? 3,
                'name'        => 'Dress Cocktail Elegan',
                'price'       => 620000,
                'description' => 'Dress cocktail midi berbahan scuba yang mempertahankan bentuk, cocok untuk pesta semi-formal.',
            ],
            [
                'category_id' => $formal->id ?? 3,
                'name'        => 'Celana Bahan Pria Formal',
                'price'       => 350000,
                'description' => 'Celana bahan formal pria berbahan polyester-viscose yang tidak mudah kusut dan tetap rapi sepanjang hari.',
            ],
            [
                'category_id' => $formal->id ?? 3,
                'name'        => 'Blouse Sutra Kantor',
                'price'       => 480000,
                'description' => 'Blouse berbahan sutra imitasi berkualitas dengan draping yang elegan, ideal untuk presentasi dan meeting.',
            ],
            [
                'category_id' => $formal->id ?? 3,
                'name'        => 'Setelan Wanita Formal',
                'price'       => 680000,
                'description' => 'Setelan blazer dan rok formal wanita berbahan crepe premium, tampil profesional dan berkelas di kantor.',
            ],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, [
                'slug'  => Str::slug($product['name']) . '-' . rand(100, 999),
                'image' => null, // Placeholder image akan digunakan di view
            ]));
        }
    }
}
