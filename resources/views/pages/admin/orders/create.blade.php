@extends('layouts.admin')

@section('page-title', 'Tambah Transaksi Manual')

@section('content')
    <div class="form-container" style="max-width: 800px; margin: 0 auto; background-color: var(--putih); padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
            <a href="{{ route('orders.index') }}" style="color: var(--cokelat-utama); display: flex; align-items: center; text-decoration: none; font-weight: bold; font-size: 14px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16" style="margin-right: 5px;">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg> Kembali
            </a>
            <h2 style="margin: 0; font-family: 'Lora', serif; color: var(--cokelat-gelap);">Buat Transaksi / Pesanan Baru</h2>
        </div>
        
        @if($errors->any())
            <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="user_id" style="display: block; margin-bottom: 8px; font-weight: bold; color: var(--teks-gelap);">Pelanggan (User)</label>
                <select name="user_id" id="user_id" class="form-control" required style="width: 100%; padding: 12px 15px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 14px;">
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->phone_number ?? $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label for="product_id" style="display: block; margin-bottom: 8px; font-weight: bold; color: var(--teks-gelap);">Produk / Layanan</label>
                    <select name="product_id" id="product_id" class="form-control" required style="width: 100%; padding: 12px 15px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 14px;">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (Rp {{ number_format($product->price, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity" style="display: block; margin-bottom: 8px; font-weight: bold; color: var(--teks-gelap);">Jumlah (Kuantitas)</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', 1) }}" required min="1" style="width: 100%; padding: 12px 15px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 14px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label for="size" style="display: block; margin-bottom: 8px; font-weight: bold; color: var(--teks-gelap);">Ukuran Pakaian</label>
                    <select name="size" id="size" class="form-control" required style="width: 100%; padding: 12px 15px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 14px;">
                        <option value="S" {{ old('size') == 'S' ? 'selected' : '' }}>S (Small)</option>
                        <option value="M" {{ old('size') == 'M' ? 'selected' : '' }}>M (Medium)</option>
                        <option value="L" {{ old('size') == 'L' ? 'selected' : '' }}>L (Large)</option>
                        <option value="XL" {{ old('size') == 'XL' ? 'selected' : '' }}>XL (Extra Large)</option>
                        <option value="XXL" {{ old('size') == 'XXL' ? 'selected' : '' }}>XXL (Double Extra Large)</option>
                        <option value="Custom" {{ old('size') == 'Custom' ? 'selected' : '' }}>Custom (Tulis di Catatan)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status" style="display: block; margin-bottom: 8px; font-weight: bold; color: var(--teks-gelap);">Status Transaksi</label>
                    <select name="status" id="status" class="form-control" required style="width: 100%; padding: 12px 15px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 14px;">
                        <option value="menunggu" {{ old('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="proses" {{ old('status') == 'proses' ? 'selected' : '' }}>Dalam Proses Jahit</option>
                        <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai Dijahit</option>
                        <option value="diambil" {{ old('status') == 'diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                        <option value="batal" {{ old('status') == 'batal' ? 'selected' : '' }}>Batal / Cancel</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="additional_notes" style="display: block; margin-bottom: 8px; font-weight: bold; color: var(--teks-gelap);">Catatan Detail Ukuran / Keterangan Lain (Opsional)</label>
                <textarea name="additional_notes" id="additional_notes" class="form-control" placeholder="Contoh: Lingkar dada: 105cm, Panjang lengan: 58cm, Warna kain navy..." style="width: 100%; padding: 12px 15px; border: 1px solid #eae0d5; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 14px; min-height: 100px; resize: vertical;">{{ old('additional_notes') }}</textarea>
            </div>
            
            <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 10px;">
                <a href="{{ route('orders.index') }}" class="btn-cancel" style="padding: 12px 25px; border-radius: 8px; font-weight: bold; text-decoration: none; display: inline-block; text-align: center; line-height: 1;">Batal</a>
                <button type="submit" class="btn-submit" style="padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer; line-height: 1;">Simpan Transaksi</button>
            </div>
        </form>
    </div>
@endsection
