<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $pendingOrders = Order::where('status', 'menunggu')->count();

        // Pesanan selesai atau diambil yang sudah lunas/diterima
        $monthlyIncome = Order::whereMonth('created_at', date('m'))
                            ->whereYear('created_at', date('Y'))
                            ->whereIn('status', ['selesai', 'diambil'])
                            ->sum('total_price');

        $recentOrders = Order::with(['user', 'product'])->latest()->take(5)->get();

        // Produk Terpopuler
        $popularProducts = Order::select('product_id', \Illuminate\Support\Facades\DB::raw('count(*) as total_sold'))
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->take(5)
            ->get();

        // Statistik Penjualan Bulanan
        $salesThisMonth = Order::select(
            \Illuminate\Support\Facades\DB::raw('DAY(created_at) as day'),
            \Illuminate\Support\Facades\DB::raw('COUNT(*) as total_orders')
        )
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->whereIn('status', ['selesai', 'diambil'])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $daysInMonth = (int)date('t');
        $chartData = array_fill(0, $daysInMonth, 0);
        foreach ($salesThisMonth as $sale) {
            $chartData[$sale->day - 1] = $sale->total_orders;
        }
        $chartLabels = range(1, $daysInMonth);

        return view('pages.admin.dashboard', compact('totalProducts', 'pendingOrders', 'monthlyIncome', 'recentOrders', 'popularProducts', 'chartData', 'chartLabels'));
    }

    public function settings()
    {
        $adminWhatsapp = Setting::get('admin_whatsapp', '6289601767100');
        $heroImage = Setting::get('hero_image');
        $aboutImage = Setting::get('about_image');

        $heroTitle = Setting::get('hero_title', 'Temukan Pesona<br>Elegan Anda');
        $heroSubtitle = Setting::get('hero_subtitle', 'Koleksi busana eksklusif yang dirancang dengan presisi untuk menonjolkan keindahan dan kepercayaan diri Anda di setiap momen berharga.');
        $aboutTitle = Setting::get('about_title', 'Tentang Butik');
        $aboutSubtitle = Setting::get('about_subtitle', 'Kisah Keanggunan Anda Dimulai di Sini');
        $aboutDescription = Setting::get('about_description', 'Kami percaya bahwa busana bukan sekadar pakaian, melainkan cerminan karakter dan keanggunan sejati. Berdiri dengan dedikasi untuk menghadirkan kesempurnaan, setiap helai koleksi kami dirancang secara eksklusif menggunakan material premium pilihan.');

        // Kontak Butik
        $contactAddress = Setting::get('contact_address', 'Jl. Braga No. 123, Pusat Kota Bandung, Jawa Barat');
        $contactHours = Setting::get('contact_hours', 'Senin - Minggu | 10:00 - 20:00 WIB');
        $contactEmail = Setting::get('contact_email', 'f4uziahtailor@gmail.com');
        $contactMapsUrl = Setting::get('contact_maps_url', 'https://maps.google.com/maps?q=Bandung&t=&z=13&ie=UTF8&iwloc=&output=embed');

        // Kustomisasi Footer
        $footerDescription = Setting::get('footer_description', 'Harmoni sempurna antara keindahan desain dan kenyamanan premium. Temukan koleksi busana yang memancarkan karakter autentik Anda sepanjang hari.');
        $contactInstagram = Setting::get('contact_instagram', '@f4uziah_tailor');
        $contactPhone = Setting::get('contact_phone', '+62 8234567891');
        $footerCopyright = Setting::get('footer_copyright', 'Copyright © F4uziah Tailor 2026');

        return view('pages.admin.settings', compact(
            'adminWhatsapp', 'heroImage', 'aboutImage',
            'heroTitle', 'heroSubtitle', 'aboutTitle', 'aboutSubtitle', 'aboutDescription',
            'contactAddress', 'contactHours', 'contactEmail', 'contactMapsUrl',
            'footerDescription', 'contactInstagram', 'contactPhone', 'footerCopyright'
        ));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'admin_whatsapp' => 'required|string|max:30',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string|max:1000',
            'about_title' => 'required|string|max:255',
            'about_subtitle' => 'required|string|max:255',
            'about_description' => 'required|string|max:2000',
            'contact_address' => 'nullable|string|max:500',
            'contact_hours' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_maps_url' => 'nullable|url|max:2000',
            'footer_description' => 'nullable|string|max:1000',
            'contact_instagram' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'footer_copyright' => 'nullable|string|max:255',
        ]);

        // Update Nomor WhatsApp
        $phoneClean = preg_replace('/[^0-9]/', '', $request->admin_whatsapp);
        if (strpos($phoneClean, '0') === 0) {
            $phoneClean = '62' . substr($phoneClean, 1);
        }
        Setting::set('admin_whatsapp', $phoneClean);

        // Update Gambar Hero
        if ($request->hasFile('hero_image')) {
            $oldHero = Setting::get('hero_image');
            if ($oldHero) {
                Storage::disk('public')->delete($oldHero);
            }
            $path = $request->file('hero_image')->store('settings', 'public');
            Setting::set('hero_image', $path);
        }

        // Update Gambar Tentang Butik
        if ($request->hasFile('about_image')) {
            $oldAbout = Setting::get('about_image');
            if ($oldAbout) {
                Storage::disk('public')->delete($oldAbout);
            }
            $path = $request->file('about_image')->store('settings', 'public');
            Setting::set('about_image', $path);
        }

        // Update Teks Halaman Depan
        Setting::set('hero_title', $request->hero_title);
        Setting::set('hero_subtitle', $request->hero_subtitle);
        Setting::set('about_title', $request->about_title);
        Setting::set('about_subtitle', $request->about_subtitle);
        Setting::set('about_description', $request->about_description);

        // Update Info Kontak Butik
        Setting::set('contact_address', $request->contact_address ?? 'Jl. Braga No. 123, Pusat Kota Bandung, Jawa Barat');
        Setting::set('contact_hours', $request->contact_hours ?? 'Senin - Minggu | 10:00 - 20:00 WIB');
        Setting::set('contact_email', $request->contact_email ?? 'f4uziahtailor@gmail.com');
        Setting::set('contact_maps_url', $request->contact_maps_url ?? 'https://maps.google.com/maps?q=Bandung&t=&z=13&ie=UTF8&iwloc=&output=embed');

        // Update Footer
        Setting::set('footer_description', $request->footer_description ?? 'Harmoni sempurna antara keindahan desain dan kenyamanan premium. Temukan koleksi busana yang memancarkan karakter autentik Anda sepanjang hari.');
        Setting::set('contact_instagram', $request->contact_instagram ?? '@f4uziah_tailor');
        Setting::set('contact_phone', $request->contact_phone ?? '+62 8234567891');
        Setting::set('footer_copyright', $request->footer_copyright ?? 'Copyright © F4uziah Tailor 2026');

        return back()->with('success', 'Pengaturan Butik berhasil diperbarui!');
    }
}
