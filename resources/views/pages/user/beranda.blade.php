@extends('layouts.app')

@section('title', 'F4UZIAHTAILOR - Beranda')

@section('content')
    <!-- Hero Section -->
    <section id="hero" class="hero">
        <div class="hero-content">
            @php
                $dbHeroTitle = \App\Models\Setting::get('hero_title', 'Temukan Pesona<br>Elegan Anda');
                $dbHeroSubtitle = \App\Models\Setting::get('hero_subtitle', 'Koleksi busana eksklusif yang dirancang dengan presisi untuk menonjolkan keindahan dan kepercayaan diri Anda di setiap momen berharga.');
            @endphp
            <h1>{!! $dbHeroTitle !!}</h1>
            <p>{{ $dbHeroSubtitle }}</p>
            <a href="#search" class="btn-primary" style="text-decoration: none; text-align: center;">Jelajahi Koleksi</a>
        </div>
        <div class="hero-image">
            @php
                $dbHeroImg = \App\Models\Setting::get('hero_image');
            @endphp
            <img src="{{ $dbHeroImg ? asset('storage/' . $dbHeroImg) : 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=800&auto=format&fit=crop' }}" alt="Butik Interior">
        </div>
    </section>

    <!-- Search & Filter -->
    <section id="search" class="search-filter">
        <form action="{{ route('home') }}#search" method="GET" class="search-wrapper">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="search" class="search-bar" placeholder="Jelajahi koleksi eksklusif..." value="{{ request('search') }}">
        </form>
        <div class="filters">
            <a href="{{ route('home') }}#search" class="filter-btn {{ !request('category') ? 'active' : '' }}" style="text-decoration: none; display: inline-block;">Semua</a>
            @foreach($categories as $cat)
                <a href="{{ route('home', ['category' => $cat->id]) }}#search" class="filter-btn {{ request('category') == $cat->id ? 'active' : '' }}" style="text-decoration: none; display: inline-block;">{{ $cat->name }}</a>
            @endforeach
        </div>
    </section>

    <!-- Katalog Grid -->
    <section id="katalog" class="catalog-container">
        <!-- Tombol Arrow Kiri -->
        <button class="catalog-arrow catalog-arrow-left" id="catalog-prev" aria-label="Sebelumnya">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>

        <!-- Tombol Arrow Kanan -->
        <button class="catalog-arrow catalog-arrow-right" id="catalog-next" aria-label="Selanjutnya">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>

        <div class="catalog-fade-left"></div>
        <div class="catalog-fade-right"></div>
        
        <div class="catalog" id="catalog-scroll">
            <div class="catalog-track" id="catalog-track">
                @forelse($products as $product)
                <div class="card" data-index="{{ $loop->index }}">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=400&auto=format&fit=crop' }}" alt="{{ $product->name }}" style="width: 100%; height: 350px; object-fit: cover;">
                    <div class="card-body">
                        <h3>{{ $product->name }}</h3>
                        <p>Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <span class="btn-admin-view">Produk Aktif</span>
                        @else
                        <button class="btn-detail" 
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}" 
                                data-price="Rp {{ number_format($product->price, 0, ',', '.') }}" 
                                data-desc="{{ $product->description }}" 
                                data-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=400&auto=format&fit=crop' }}">
                            Detail Baju
                        </button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="catalog-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: #c4a882; margin-bottom: 12px;">
                        <path d="M20.38 3.46L16 2a4 4 0 01-8 0L3.62 3.46a2 2 0 00-1.34 2.23l.58 3.57a1 1 0 00.99.84H5v10a2 2 0 002 2h10a2 2 0 002-2V10h1.15a1 1 0 00.99-.84l.58-3.57a2 2 0 00-1.34-2.23z"></path>
                    </svg>
                    <p>Tidak ada produk yang ditemukan.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Dot Indicators -->
        <div class="catalog-dots" id="catalog-dots"></div>
    </section>

    <!-- Tentang Kami -->
    <section id="tentang" class="about">
        <div class="about-content">
            @php
                $dbAboutTitle = \App\Models\Setting::get('about_title', 'Tentang Butik');
                $dbAboutSubtitle = \App\Models\Setting::get('about_subtitle', 'Kisah Keanggunan Anda Dimulai di Sini');
                $dbAboutDesc = \App\Models\Setting::get('about_description', 'Kami percaya bahwa busana bukan sekadar pakaian, melainkan cerminan karakter dan keanggunan sejati. Berdiri dengan dedikasi untuk menghadirkan kesempurnaan, setiap helai koleksi kami dirancang secara eksklusif menggunakan material premium pilihan.');
            @endphp
            <h2>{{ $dbAboutTitle }}</h2>
            <h3>{{ $dbAboutSubtitle }}</h3>
            <p>{{ $dbAboutDesc }}</p>
        </div>
        <div class="about-image">
            @php
                $dbAboutImg = \App\Models\Setting::get('about_image');
            @endphp
            <img src="{{ $dbAboutImg ? asset('storage/' . $dbAboutImg) : 'https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?q=80&w=800&auto=format&fit=crop' }}" alt="Tentang Butik">
        </div>
    </section>

    <!-- Kontak & Maps -->
    <section id="kontak" class="contact-maps">
        <div class="contact-info">
            <h2>Kunjungi Butik Kami</h2>
            <p class="desc">Kami menanti kedatangan Anda untuk merasakan langsung kualitas premium dari setiap mahakarya kami.</p>

            @php
                $contactAddress = \App\Models\Setting::get('contact_address', 'Jl. Braga No. 123, Pusat Kota Bandung, Jawa Barat');
                $contactHours   = \App\Models\Setting::get('contact_hours', 'Senin - Minggu | 10:00 - 20:00 WIB');
                $contactEmail   = \App\Models\Setting::get('contact_email', 'f4uziahtailor@gmail.com');
                $contactMapsUrl = \App\Models\Setting::get('contact_maps_url', 'https://maps.google.com/maps?q=Bandung&t=&z=13&ie=UTF8&iwloc=&output=embed');

                $adminWa = \App\Models\Setting::get('admin_whatsapp', '6289601767100');
                $adminWaClean = preg_replace('/[^0-9]/', '', $adminWa);
                if (strpos($adminWaClean, '0') === 0) {
                    $adminWaClean = '62' . substr($adminWaClean, 1);
                }
                $homepageWaMessage = "Halo Butik F4UZIAHTAILOR, saya ingin berkonsultasi mengenai pemesanan busana...";
                $homepageWaUrl = "https://wa.me/" . $adminWaClean . "?text=" . urlencode($homepageWaMessage);
            @endphp

            <div class="info-item">
                <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <div>
                    <strong>Alamat:</strong><br>
                    {{ $contactAddress }}
                </div>
            </div>

            <div class="info-item">
                <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <div>
                    <strong>Jam Operasi:</strong><br>
                    {{ $contactHours }}
                </div>
            </div>

            <div class="info-item">
                <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <div>
                    <strong>Email:</strong><br>
                    {{ $contactEmail }}
                </div>
            </div>

            <a href="{{ $homepageWaUrl }}" target="_blank" class="btn-wa" style="text-decoration: none; text-align: center; display: inline-block; box-sizing: border-box;">Hubungi Via WhatsApp</a>
        </div>
        <div class="map-container">
            <iframe src="{{ $contactMapsUrl }}" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </section>


    <div id="modal-detail" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detail Baju</h3>
                <button class="close-modal" id="close-modal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <div class="modal-body">
                <div class="modal-image">
                    <img id="modal-image-display" src="https://images.unsplash.com/photo-1618932260643-eee4a2f652a6?q=80&w=400&auto=format&fit=crop" alt="Foto Baju Gamis">
                </div>

                <div class="modal-info">
                    <h2 class="modal-title" id="modal-title-display">Baju Gamis</h2>
                    <p class="modal-price" id="modal-price-display">Rp.300.000</p>
                    <p class="modal-desc" id="modal-desc-display">"Gamis berpotongan A-line dari Premium Linen yang sejuk, jatuh alami, dan memancarkan keanggunan minimalis."</p>

                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <div class="admin-order-notice">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <p>Anda login sebagai <strong>Admin</strong>. Pemesanan hanya dapat dilakukan oleh pelanggan.</p>
                        <a href="{{ route('admin.dashboard') }}" class="btn-go-admin">Ke Dashboard Admin</a>
                    </div>
                    @else
                    <form action="{{ route('cart.add') }}" method="POST" id="form-add-cart">
                        @csrf
                        <input type="hidden" name="product_id" id="modal-product-id">
                        <input type="hidden" name="size" id="modal-selected-size" value="Custom">
                        <input type="hidden" name="quantity" id="modal-selected-qty" value="1">

                        <div class="size-selector">
                            <p class="section-label">Pilih Ukuran:</p>
                            <div class="size-options">
                                <button type="button" class="size-btn active" data-size="Custom">Custom</button>
                                <button type="button" class="size-btn" data-size="S">S</button>
                                <button type="button" class="size-btn" data-size="M">M</button>
                                <button type="button" class="size-btn" data-size="L">L</button>
                                <button type="button" class="size-btn" data-size="XL">XL</button>
                                <button type="button" class="size-btn" data-size="2XL">2XL</button>
                                <button type="button" class="size-btn" data-size="3XL">3XL</button>
                            </div>
                            <p class="size-note">*Ukuran custom dapat diukur oleh sendiri yang akan di bantu oleh admin, atau bisa datang ke butik untuk diukur langsung</p>
                        </div>

                        <div class="notes-selector" style="margin-top: 15px; margin-bottom: 20px;">
                            <p class="section-label" style="margin-bottom: 6px; font-weight: 600; color: #85644c; font-size: 14px;">Catatan Tambahan / Detail Ukuran Custom (Opsional):</p>
                            <textarea name="notes" id="modal-notes-input" placeholder="Contoh: Lingkar dada 105cm, Panjang dress 135cm, atau warna kain khusus..." style="width: 100%; min-height: 80px; padding: 12px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 13px; color: #333; background-color: #fdf8f0; outline: none; transition: 0.3s; resize: vertical; box-sizing: border-box;"></textarea>
                        </div>

                        <div class="modal-action-row">
                            <div class="quantity-selector">
                                <p class="section-label">Jumlah:</p>
                                <div class="qty-controls">
                                    <button type="button" class="qty-btn" id="qty-minus">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                    </button>
                                    <span class="qty-number" id="qty-value">1</span>
                                    <button type="button" class="qty-btn" id="qty-plus">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn-add-cart">Tambah Ke Keranjang</button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
