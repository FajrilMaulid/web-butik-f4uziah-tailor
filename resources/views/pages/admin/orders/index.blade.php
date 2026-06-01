@extends('layouts.admin')

@section('page-title', 'Data Transaksi')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-container">
        <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <h2 style="margin: 0;">Daftar Pesanan & Transaksi</h2>
            <div style="display: flex; gap: 10px; align-items: center;">
                <form action="{{ route('orders.export') }}" method="GET" style="display: flex; gap: 8px; align-items: center; margin: 0;">
                    <select name="filter" style="padding: 8px 10px; border-radius: 6px; border: 1px solid #ddd; font-family: 'Nunito', sans-serif; font-size: 14px; outline: none; cursor: pointer;">
                        <option value="all">Semua Waktu</option>
                        <option value="week">Minggu Ini</option>
                        <option value="month">Bulan Ini</option>
                    </select>
                    <button type="submit" class="btn-tambah" style="background-color: #27ae60; border: none; border-radius: 6px; display: flex; align-items: center; padding: 8px 12px; margin: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-excel" viewBox="0 0 16 16" style="margin-right: 5px;">
                          <path d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z"/>
                          <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                        </svg>Export
                    </button>
                </form>
                <!-- Tombol Tambah Transaksi -->
                <a href="{{ route('orders.create') }}" class="btn-tambah" style="padding: 8px 12px; border-radius: 6px; text-decoration: none;">+ Tambah Transaksi</a>
            </div>
        </div>

        {{-- Search Bar --}}
        <form method="GET" action="{{ route('orders.index') }}" style="margin-bottom: 20px; display: flex; gap: 10px; align-items: center;">
            <div style="position: relative; flex: 1; max-width: 450px;">
                <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #aaa;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama pelanggan, produk, status, atau ID pesanan..." style="width: 100%; padding: 9px 12px 9px 38px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Nunito', sans-serif; font-size: 14px; outline: none; box-sizing: border-box;" onfocus="this.style.borderColor='var(--cokelat-utama)'" onblur="this.style.borderColor='#ddd'">
            </div>
            <button type="submit" class="btn-tambah" style="padding: 9px 20px;">Cari</button>
            @if($search)
                <a href="{{ route('orders.index') }}" style="padding: 9px 16px; border-radius: 8px; border: 1px solid #ddd; background: #f9f9f9; color: #666; font-size: 14px; text-decoration: none; font-family: 'Nunito', sans-serif;">✕ Reset</a>
            @endif
        </form>
        @if($search)
            <p style="font-size: 13px; color: #888; margin-bottom: 12px;">Menampilkan hasil untuk: <strong>"{{ $search }}"</strong> ({{ $orders->total() }} ditemukan)</p>
        @endif

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Catatan / Resi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                             <td>
                                 <strong>{{ $order->user->name ?? 'User Terhapus' }}</strong><br>
                                 <span style="font-size: 12px; color: #666; display: block; margin-top: 4px;">Alamat: {{ $order->user->address ?? 'Belum diisi' }}</span>
                                 @if($order->user && $order->user->phone_number)
                                     @php
                                         // Bersihkan nomor telepon: hilangkan spasi, tanda hubung, tanda plus
                                         $phoneClean = preg_replace('/[^0-9]/', '', $order->user->phone_number);
                                         // Jika berawalan '0', ubah menjadi '62'
                                         if (strpos($phoneClean, '0') === 0) {
                                             $phoneClean = '62' . substr($phoneClean, 1);
                                         }
                                         
                                         // Buat template pesan konfirmasi profesional (Bebas dari masalah emoticon encoding)
                                         $message = "*KONFIRMASI PESANAN - BUTIK F4UZIAHTAILOR*\n\n" .
                                                    "Kepada Yth. *" . ($order->user->name ?? 'Pelanggan') . "*,\n\n" .
                                                    "Terima kasih telah melakukan pemesanan pakaian di Butik F4UZIAHTAILOR. Kami mengonfirmasi bahwa pesanan Anda telah kami terima dengan detail sebagai berikut:\n\n" .
                                                    "*Detail Transaksi:*\n" .
                                                    "• ID Pesanan: *#ORD-" . str_pad($order->id, 5, '0', STR_PAD_LEFT) . "*\n" .
                                                    "• Produk/Layanan: *" . ($order->product->name ?? 'Pakaian') . "*\n" .
                                                    "• Detail Keterangan: *" . str_replace('. Catatan:', "\n• Catatan Tambahan:", $order->notes ?? '-') . "*\n" .
                                                    "• Total Pembayaran: *Rp " . number_format($order->total_price, 0, ',', '.') . "*\n\n" .
                                                    "Mohon lakukan pembayaran untuk memulai proses pembuatan/penjahitan pakaian Anda. Konfirmasi bukti pembayaran dapat langsung dikirimkan membalas chat ini.\n\n" .
                                                    "Jika ada pertanyaan lebih lanjut atau ingin berkonsultasi mengenai ukuran, jangan ragu untuk menghubungi kami kembali.\n\n" .
                                                    "Salam hangat,\n" .
                                                    "*F4UZIAHTAILOR*";
                                         
                                         $waUrl = "https://wa.me/" . $phoneClean . "?text=" . urlencode($message);
                                     @endphp
                                     <div style="margin-top: 8px;">
                                         <a href="{{ $waUrl }}" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; background-color: #25d366; color: white; padding: 6px 12px; border-radius: 6px; font-size: 12px; text-decoration: none; font-weight: bold; transition: 0.3s; box-shadow: 0 2px 5px rgba(37,211,102,0.2);" onmouseover="this.style.backgroundColor='#1ebd59'; this.style.boxShadow='0 4px 10px rgba(37,211,102,0.4)';" onmouseout="this.style.backgroundColor='#25d366'; this.style.boxShadow='0 2px 5px rgba(37,211,102,0.2)';">
                                             Hubungi ({{ $order->user->phone_number }})
                                         </a>
                                     </div>
                                 @else
                                     <span style="font-size: 11px; color: #aaa; display: block; margin-top: 6px;">WhatsApp: Belum diisi</span>
                                 @endif
                             </td>
                            <td>{{ $order->product->name ?? 'Produk Terhapus' }}</td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $statusColor = match($order->status) {
                                        'menunggu' => '#f59e0b',
                                        'proses' => '#3b82f6',
                                        'selesai' => '#10b981',
                                        'diambil' => '#6366f1',
                                        'batal' => '#ef4444',
                                        default => '#6b7280'
                                    };
                                @endphp
                                <span style="background-color: {{ $statusColor }}; color: white; padding: 4px 10px; border-radius: 20px; font-size: 12px; text-transform: capitalize;">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($order->notes, 30) ?: '-' }}</td>
                            <td>
                                <div class="btn-action-group" style="display: flex; gap: 8px; flex-direction: column;">
                                    <form action="{{ route('orders.update', $order->id) }}" method="POST" style="display: flex; gap: 5px;">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" style="padding: 5px; border-radius: 5px; border: 1px solid #ddd; font-family: 'Nunito', sans-serif;">
                                            <option value="menunggu" {{ $order->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="proses" {{ $order->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                            <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="diambil" {{ $order->status == 'diambil' ? 'selected' : '' }}>Diambil</option>
                                            <option value="batal" {{ $order->status == 'batal' ? 'selected' : '' }}>Batal</option>
                                        </select>
                                        <button type="submit" class="btn-edit" style="padding: 6px 12px;">Update</button>
                                    </form>
                                    
                                    <div style="display: flex; gap: 5px;">
                                        <a href="{{ route('orders.edit', $order->id) }}" class="btn-edit" style="background-color: #3b82f6; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: bold; color: white; display: inline-block;">Edit Detail</a>
                                        
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesanan ini secara permanen?');" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" style="padding: 6px 12px;">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center;">Belum ada data pesanan/transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 25px;">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
