@extends('layouts.app')

@section('title', 'F4UZIAHTAILOR - Profile')

@section('content')
    <section class="profile-section">
        <div class="profile-wrapper">

            <div class="profile-tabs-header">
                <button class="tab-btn active" id="btn-tab-akun" onclick="openProfileTab('akun')">Akun Saya</button>
                <button class="tab-btn" id="btn-tab-history" onclick="openProfileTab('history')">History Pemesanan</button>
            </div>

            <div class="profile-body">
                <div class="profile-inner-card">

                    <div id="tab-akun" class="profile-tab-content active">

                        <form action="{{ route('profile.update') }}" method="POST" class="profile-form" enctype="multipart/form-data">
                            @csrf

                            <div class="profile-header-user" style="margin-bottom: 30px;">
                                <div class="user-avatar-group">
                                    <div class="avatar-circle">
                                        @if(Auth::user()->avatar)
                                            <img id="avatar-preview-img" src="{{ asset('storage/' . Auth::user()->avatar) }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                        @else
                                            <div id="avatar-placeholder-svg" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="user-info">
                                        <h2>{{ Auth::user()->name ?? 'Siti Zuabidah' }}</h2>
                                        <p>{{ Auth::user()->email ?? 'sitizuabidah@gmail.com' }}</p>
                                    </div>
                                </div>
                                <label for="avatar-input" class="btn-outline-brown" style="cursor: pointer; display: inline-block;">Ubah Avatar</label>
                                <input type="file" id="avatar-input" name="avatar" style="display: none;" accept="image/*" onchange="previewAvatar(event)">
                            </div>

                            <div class="form-grid">
                                <div class="input-profile-group">
                                    <input type="text" name="name" value="{{ Auth::user()->name ?? 'Siti Zuabidah' }}" placeholder="Username">
                                </div>
                                <div class="input-profile-group">
                                    <input type="tel" name="phone_number" id="phone_number_profile" value="{{ Auth::user()->phone_number ?? '' }}" placeholder="Nomer Ponsel (cth: 08123456789)" maxlength="15" minlength="9" pattern="[0-9+\-\s]{9,15}" oninput="this.value=this.value.replace(/[^0-9+\-\s]/g,'').slice(0,15)">
                                </div>
                                <div class="input-profile-group">
                                    <input type="email" name="email" value="{{ Auth::user()->email ?? 'sitizuabidah@gmail.com' }}" placeholder="Email">
                                </div>
                                <div class="input-profile-group">
                                    <input type="password" name="password" placeholder="Password (Kosongkan jika tidak diubah)">
                                </div>
                                <div class="input-profile-group" style="grid-column: 1 / -1;">
                                    <textarea name="address" placeholder="Alamat Pengiriman (Kosongkan jika belum ada)" rows="3">{{ Auth::user()->address ?? '' }}</textarea>
                                </div>
                            </div>

                            <div class="profile-actions">
                                <a href="{{ route('logout') }}" class="btn-logout">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                    Logout Akun
                                </a>
                                <button type="submit" class="btn-save-profile">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>


                    <div id="tab-history" class="profile-tab-content">

                        <div class="history-filters">
                            <button class="filter-hist-btn active" onclick="filterHistory('semua', this)">Semua</button>
                            <button class="filter-hist-btn" onclick="filterHistory('menunggu_pembayaran', this)">Belum Bayar</button>
                            <button class="filter-hist-btn" onclick="filterHistory('menunggu', this)">Menunggu Konfirmasi</button>
                            <button class="filter-hist-btn" onclick="filterHistory('proses', this)">Dikerjakan</button>
                            <button class="filter-hist-btn" onclick="filterHistory('selesai', this)">Selesai</button>
                        </div>

                        <div class="history-list">
                            @if($orders->count() > 0)
                                <!-- Placeholder ketika tidak ada data di fase filter tertentu -->
                                <div id="no-orders-placeholder" style="display: none; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 60px 20px; color: #888; width: 100%;">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 15px; color: #888;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                    <h3 style="font-family: 'Nunito', sans-serif; margin-bottom: 10px; font-size: 18px; color: #666; text-align: center;">Tidak ada pesanan di fase ini</h3>
                                </div>
                            @endif

                            @forelse($orders as $order)
                                @php
                                    $statusText = match($order->status) {
                                        'menunggu_pembayaran' => 'Belum Bayar',
                                        'menunggu' => 'Menunggu Konfirmasi',
                                        'proses' => 'Dikerjakan',
                                        'selesai' => 'Selesai',
                                        'diambil' => 'Sudah Diambil',
                                        'batal' => 'Dibatalkan',
                                        default => 'Menunggu'
                                    };
                                    
                                    $statusColor = match($order->status) {
                                        'menunggu_pembayaran' => '#f97316',
                                        'menunggu' => '#f59e0b',
                                        'proses' => '#3b82f6',
                                        'selesai' => '#10b981',
                                        'diambil' => '#6366f1',
                                        'batal' => '#ef4444',
                                        default => '#6b7280'
                                    };
                                @endphp
                                <div class="history-card" data-status="{{ $order->status }}">
                                    <img src="{{ $order->product->image ? asset('storage/' . $order->product->image) : 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=200&auto=format&fit=crop' }}" alt="{{ $order->product->name ?? 'Produk' }}">
                                    <div class="hist-details">
                                        <h3>{{ $order->product->name ?? 'Produk Terhapus' }}</h3>
                                        <p class="hist-size">{{ $order->notes }}</p>
                                        <p class="hist-status">Status: <span style="color: {{ $statusColor }}; font-weight: bold;">{{ $statusText }}</span></p>

                                        {{-- Upload Bukti Transfer (jika belum bayar) --}}
                                        @if($order->status === 'menunggu_pembayaran')
                                        <div class="upload-proof-area">
                                            <a href="{{ route('payment.info', $order->id) }}" class="btn-payment-info">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                                Lihat Info Rekening
                                            </a>
                                            <form action="{{ route('payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data" class="upload-form">
                                                @csrf
                                                <label class="btn-upload-proof" for="proof-{{ $order->id }}">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 16 12 12 8 16"></polyline><line x1="12" y1="12" x2="12" y2="21"></line><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"></path></svg>
                                                    Upload Bukti Transfer
                                                </label>
                                                <input type="file" id="proof-{{ $order->id }}" name="payment_proof" accept="image/*" style="display:none;" onchange="this.form.submit()">
                                            </form>
                                        </div>
                                        @endif

                                        {{-- Preview bukti yang sudah diupload --}}
                                        @if($order->status === 'menunggu' && $order->reference_image)
                                        <div class="proof-uploaded-info">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            Bukti transfer sudah dikirim — menunggu verifikasi admin.
                                        </div>
                                        @endif
                                    </div>
                                    <div class="hist-price-info">
                                        <p class="hist-total">Total: <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></p>
                                        @if($order->status === 'menunggu_pembayaran')
                                        <span class="badge-unpaid">Segera Bayar</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 60px 20px; color: #888; width: 100%;">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 15px; color: #888;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                    <h3 style="font-family: 'Nunito', sans-serif; margin-bottom: 10px;">Riwayat Kosong</h3>
                                    <p>Anda belum memiliki riwayat pemesanan baju.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script>
        function filterHistory(status, btn) {
            // Update active class on filter buttons
            const buttons = document.querySelectorAll('.filter-hist-btn');
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Filter cards
            const cards = document.querySelectorAll('.history-card');
            
            // Jika tidak ada pesanan sama sekali di history, return early agar placeholder filter tidak ikut muncul
            if (cards.length === 0) {
                return;
            }

            let visibleCount = 0;
            cards.forEach(card => {
                const cardStatus = card.getAttribute('data-status');
                if (status === 'semua') {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    if (cardStatus === status) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                }
            });

            // Tampilkan placeholder jika tidak ada pesanan yang cocok
            const placeholder = document.getElementById('no-orders-placeholder');
            if (placeholder) {
                if (visibleCount === 0) {
                    placeholder.style.display = 'flex';
                } else {
                    placeholder.style.display = 'none';
                }
            }
        }

        function previewAvatar(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const avatarCircle = document.querySelector('.avatar-circle');
                    avatarCircle.innerHTML = `<img id="avatar-preview-img" src="${e.target.result}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">`;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <style>
        .upload-proof-area {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
        .btn-payment-info {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            border: 1.5px solid var(--cokelat-utama);
            color: var(--cokelat-utama);
            padding: 7px 13px;
            border-radius: 8px;
            font-size: 12px;
            font-family: 'Nunito', sans-serif;
            font-weight: 700;
            text-decoration: none;
            transition: 0.3s;
            cursor: pointer;
        }
        .btn-payment-info:hover {
            background: var(--cokelat-utama);
            color: white;
        }
        .btn-upload-proof {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--cokelat-utama);
            color: white;
            padding: 7px 13px;
            border-radius: 8px;
            font-size: 12px;
            font-family: 'Nunito', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-upload-proof:hover {
            background: var(--cokelat-gelap);
        }
        .proof-uploaded-info {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
            background: #ecfdf5;
            border: 1px solid #6ee7b7;
            color: #065f46;
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            font-family: 'Nunito', sans-serif;
        }
        .badge-unpaid {
            display: inline-block;
            background: #fff7ed;
            border: 1px solid #fdba74;
            color: #c2410c;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-top: 6px;
            font-family: 'Nunito', sans-serif;
        }
    </style>
@endsection

