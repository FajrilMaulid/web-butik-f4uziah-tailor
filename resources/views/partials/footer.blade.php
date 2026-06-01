@php
    $footerDesc = \App\Models\Setting::get('footer_description', 'Harmoni sempurna antara keindahan desain dan kenyamanan premium. Temukan koleksi busana yang memancarkan karakter autentik Anda sepanjang hari.');
    $contactEmail = \App\Models\Setting::get('contact_email', 'f4uziahtailor@gmail.com');
    $contactPhone = \App\Models\Setting::get('contact_phone', '+62 8234567891');
    $contactAddress = \App\Models\Setting::get('contact_address', 'Jl. Braga No. 123, Pusat Kota Bandung, Jawa Barat');
    $contactInstagram = \App\Models\Setting::get('contact_instagram', '@f4uziah_tailor');
    $footerCopyright = \App\Models\Setting::get('footer_copyright', 'Copyright © F4uziah Tailor 2026');

    $instagramClean = ltrim($contactInstagram, '@');
    $instagramUrl = 'https://instagram.com/' . $instagramClean;
@endphp

<footer class="footer">
    <div class="footer-grid">
        <div class="footer-col">
            <h3>F4UZIAHTAILOR</h3>
            <p>{{ $footerDesc }}</p>
        </div>
        <div class="footer-col">
            <h3>Kontak Kami</h3>
            <ul class="footer-links">
                <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> {{ $contactEmail }}</li>
                <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> {{ $contactPhone }}</li>
            </ul>
        </div>
        <div class="footer-col">
            <h3>Alamat</h3>
            <p class="footer-alamat"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> {{ $contactAddress }}</p>
        </div>
        <div class="footer-col">
            <h3>Media Sosial</h3>
            <ul class="footer-links">
                <li>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    <a href="{{ $instagramUrl }}" target="_blank" style="color: inherit; text-decoration: none; transition: 0.3s;" onmouseover="this.style.color='var(--cokelat-utama)'" onmouseout="this.style.color='inherit'">{{ $contactInstagram }}</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="copyright">
        {{ $footerCopyright }}
    </div>
</footer>
