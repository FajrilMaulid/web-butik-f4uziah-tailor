@extends('layouts.app')

@section('title', 'Keranjang Belanja - F4UZIAHTAILOR')

@section('content')
<section class="profile-section">
    <div class="profile-wrapper" style="max-width: 1000px;">
        <div class="profile-body" style="border-radius: 15px;">
            <div class="profile-inner-card">
                
                <h2 style="margin-bottom: 30px; font-size: 28px; border-bottom: 2px solid var(--cokelat-utama); padding-bottom: 10px; display: inline-block; font-family: 'Lora', serif; color: var(--teks-gelap);">Keranjang Belanja</h2>

                @if(session('success'))
                    <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
                        {{ session('success') }}
                    </div>
                @endif

                @if(count($cart) > 0)
                    <div class="cart-table-container">
                        <table class="cart-table" style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
                            <thead>
                                <tr style="border-bottom: 1px solid #eae0d5; color: var(--teks-paragraf);">
                                    <th style="padding: 15px 10px; text-align: left;">Produk</th>
                                    <th style="padding: 15px 10px; text-align: center;">Ukuran</th>
                                    <th style="padding: 15px 10px; text-align: center;">Harga</th>
                                    <th style="padding: 15px 10px; text-align: center;">Jumlah</th>
                                    <th style="padding: 15px 10px; text-align: right;">Total</th>
                                    <th style="padding: 15px 10px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalSemua = 0; @endphp
                                @foreach($cart as $cartId => $item)
                                    @php 
                                        $total = $item['price'] * $item['quantity'];
                                        $totalSemua += $total;
                                    @endphp
                                    <tr class="cart-row" style="border-bottom: 1px solid #eae0d5;">
                                        <td class="cart-td cart-product" style="padding: 15px 10px;">
                                            <div style="display: flex; align-items: center; gap: 15px;">
                                                <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=100&auto=format&fit=crop' }}" alt="{{ $item['name'] }}" style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover;">
                                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                                    <span style="font-weight: 600; color: var(--teks-gelap);">{{ $item['name'] }}</span>
                                                    @if(!empty($item['notes']))
                                                        <span style="font-size: 12px; color: #888; background-color: #fdf8f0; padding: 4px 8px; border-radius: 4px; border: 1px dashed #eae0d5; display: inline-block; max-width: 300px;">
                                                            <strong>Catatan:</strong> {{ $item['notes'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="cart-td cart-size" data-label="Ukuran" style="padding: 15px 10px; text-align: center; font-weight: bold; color: var(--teks-gelap);">{{ $item['size'] }}</td>
                                        <td class="cart-td cart-price" data-label="Harga" style="padding: 15px 10px; text-align: center; color: var(--teks-gelap);">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td class="cart-td cart-qty" data-label="Jumlah" style="padding: 15px 10px; text-align: center; color: var(--teks-gelap);">{{ $item['quantity'] }}</td>
                                        <td class="cart-td cart-total-col" data-label="Total" style="padding: 15px 10px; text-align: right; font-weight: 600; color: var(--cokelat-utama);">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                        <td class="cart-td cart-action" style="padding: 15px 10px; text-align: center;">
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="cart_id" value="{{ $cartId }}">
                                                <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer; padding: 5px;" title="Hapus Produk">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="cart-summary" style="display: flex; justify-content: flex-end; align-items: center; gap: 30px;">
                        <div class="cart-total-summary" style="font-size: 20px; color: var(--teks-gelap);">
                            Total Pembayaran: <strong style="color: var(--cokelat-utama); font-size: 24px;">Rp {{ number_format($totalSemua, 0, ',', '.') }}</strong>
                        </div>
                        <form class="cart-checkout-form" action="{{ route('cart.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary" style="font-size: 16px; padding: 15px 40px; cursor: pointer;">Checkout Sekarang</button>
                        </form>
                    </div>
                @else
                    <div style="text-align: center; padding: 60px 20px;">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 20px;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        <h3 style="color: var(--teks-paragraf); margin-bottom: 20px; font-family: 'Nunito', sans-serif;">Keranjang Anda masih kosong</h3>
                        <a href="{{ route('home') }}#search" class="btn-primary">Mulai Belanja</a>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</section>
@endsection
