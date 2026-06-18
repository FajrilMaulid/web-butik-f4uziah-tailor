<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // WhatsApp Admin
            'admin_whatsapp' => '6289601767100',

            // Hero Banner Section
            'hero_title' => 'Temukan Pesona<br>Elegan Anda',
            'hero_subtitle' => 'Koleksi busana eksklusif yang dirancang dengan presisi untuk menonjolkan keindahan dan kepercayaan diri Anda di setiap momen berharga.',
            'hero_image' => null,

            // About Us Section
            'about_title' => 'Tentang Butik',
            'about_subtitle' => 'Kisah Keanggunan Anda Dimulai di Sini',
            'about_description' => 'Kami percaya bahwa busana bukan sekadar pakaian, melainkan cerminan karakter dan keanggunan sejati. Berdiri dengan dedikasi untuk menghadirkan kesempurnaan, setiap helai koleksi kami dirancang secara eksklusif menggunakan material premium pilihan.',
            'about_image' => null,

            // Contact Info
            'contact_address' => 'Jl. Braga No. 123, Pusat Kota Bandung, Jawa Barat',
            'contact_hours' => 'Senin - Minggu | 10:00 - 20:00 WIB',
            'contact_email' => 'f4uziahtailor@gmail.com',
            'contact_maps_url' => 'https://maps.google.com/maps?q=Bandung&t=&z=13&ie=UTF8&iwloc=&output=embed',

            // Footer Customization
            'footer_description' => 'Harmoni sempurna antara keindahan desain dan kenyamanan premium. Temukan koleksi busana yang memancarkan karakter autentik Anda sepanjang hari.',
            'contact_instagram' => '@f4uziah_tailor',
            'contact_phone' => '+62 8234567891',
            'footer_copyright' => 'Copyright © F4uziah Tailor 2026',

            // Rekening Bank Pembayaran
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'F4UZIAH TAILOR',
            'bank_name_2' => 'GoPay',
            'bank_account_number_2' => '089601767100',
            'bank_account_name_2' => 'F4UZIAH TAILOR',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
