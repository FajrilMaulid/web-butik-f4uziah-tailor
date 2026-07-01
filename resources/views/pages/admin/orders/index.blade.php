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
                            <td>
                                #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                @if($order->payment_code)
                                    <br>
                                    <span style="font-size: 11px; background-color: #eae0d5; color: #5c4436; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px; font-weight: bold; white-space: nowrap;">
                                        {{ $order->payment_code }}
                                    </span>
                                @endif
                            </td>
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
                                         
                                         // Tentukan detail pesanan berdasarkan grup transaksi
                                         if ($order->payment_code) {
                                             $orderIdText = $order->payment_code;
                                             $productDetails = "";
                                             $totalPrice = 0;
                                             
                                             // Loop melalui relatedOrders untuk menggabungkan nama produk dan catatan
                                             foreach ($order->relatedOrders as $idx => $relatedOrder) {
                                                 $prodName = $relatedOrder->product->name ?? 'Pakaian';
                                                 $cleanedNotes = str_replace('. Catatan:', ', Catatan:', $relatedOrder->notes ?? '-');
                                                 $productDetails .= "\n" . ($idx + 1) . ". *" . $prodName . "* (" . $cleanedNotes . ") - Rp " . number_format($relatedOrder->total_price, 0, ',', '.');
                                                 $totalPrice += $relatedOrder->total_price;
                                             }
                                             
                                             $productSection = "*Daftar Produk:*" . $productDetails;
                                         } else {
                                             $orderIdText = "#ORD-" . str_pad($order->id, 5, '0', STR_PAD_LEFT);
                                             $cleanedNotes = str_replace('. Catatan:', "\n• Catatan Tambahan:", $order->notes ?? '-');
                                             $productSection = "• Produk/Layanan: *" . ($order->product->name ?? 'Pakaian') . "*\n" .
                                                               "• Detail Keterangan: *" . $cleanedNotes . "*";
                                             $totalPrice = $order->total_price;
                                         }
                                         
                                         // Buat template pesan konfirmasi profesional (Bebas dari masalah emoticon encoding)
                                         $message = "*KONFIRMASI PESANAN - BUTIK F4UZIAHTAILOR*\n\n" .
                                                    "Kepada Yth. *" . ($order->user->name ?? 'Pelanggan') . "*,\n\n" .
                                                    "Terima kasih telah melakukan pemesanan pakaian di Butik F4UZIAHTAILOR. Kami mengonfirmasi bahwa pesanan Anda telah kami terima dengan detail sebagai berikut:\n\n" .
                                                    "*Detail Transaksi:*\n" .
                                                    "• ID Pesanan/Transaksi: *" . $orderIdText . "*\n" .
                                                    $productSection . "\n" .
                                                    "• Total Pembayaran: *Rp " . number_format($totalPrice, 0, ',', '.') . "*\n\n" .
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
                                        'menunggu_pembayaran' => '#f97316',
                                        'menunggu' => '#f59e0b',
                                        'proses' => '#3b82f6',
                                        'selesai' => '#10b981',
                                        'diambil' => '#6366f1',
                                        'batal' => '#ef4444',
                                        default => '#6b7280'
                                    };
                                    $statusLabel = match($order->status) {
                                        'menunggu_pembayaran' => 'Belum Bayar',
                                        'menunggu' => 'Menunggu Konfirmasi',
                                        'proses' => 'Proses',
                                        'selesai' => 'Selesai',
                                        'diambil' => 'Diambil',
                                        'batal' => 'Batal',
                                        default => ucfirst($order->status)
                                    };
                                @endphp
                                <span style="background-color: {{ $statusColor }}; color: white; padding: 4px 10px; border-radius: 20px; font-size: 12px; white-space: nowrap;">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($order->notes, 30) ?: '-' }}</td>
                            <td>
                                <div class="btn-action-group" style="display: flex; gap: 8px; flex-direction: column;">
                                    <form action="{{ route('orders.update', $order->id) }}" method="POST" style="display: flex; gap: 5px;">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" style="padding: 5px; border-radius: 5px; border: 1px solid #ddd; font-family: 'Nunito', sans-serif;">
                                            <option value="menunggu_pembayaran" {{ $order->status == 'menunggu_pembayaran' ? 'selected' : '' }}>Belum Bayar</option>
                                            <option value="menunggu" {{ $order->status == 'menunggu' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                            <option value="proses" {{ $order->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                            <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="diambil" {{ $order->status == 'diambil' ? 'selected' : '' }}>Diambil</option>
                                            <option value="batal" {{ $order->status == 'batal' ? 'selected' : '' }}>Batal</option>
                                        </select>
                                        <button type="submit" class="btn-edit" style="padding: 6px 12px;">Update</button>
                                    </form>

                                    {{-- Tombol Konfirmasi/Tolak Bukti Transfer --}}
                                    @if($order->reference_image)
                                    <div style="display: flex; gap: 5px; margin-top: 4px;">
                                        <button type="button"
                                            onclick="showProofModal('{{ asset('storage/' . $order->reference_image) }}', '{{ '#ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) }}', {{ $order->id }}, '{{ $order->payment_status }}')"
                                            style="background: #3b82f6; color: white; border: none; padding: 6px 10px; border-radius: 6px; font-size: 12px; font-weight: bold; cursor: pointer; font-family: 'Nunito', sans-serif;">
                                            Lihat Bukti
                                        </button>
                                    </div>
                                    @endif
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

{{-- Modal Preview Bukti Transfer --}}
<div id="proof-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:28px; max-width:520px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.3); position:relative;">
        <button onclick="closeProofModal()" style="position:absolute; top:14px; right:14px; background:none; border:none; cursor:pointer; font-size:20px; color:#666;">✕</button>
        <h3 style="font-family:'Lora',serif; color:var(--cokelat-gelap); margin-bottom:6px; font-size:18px;" id="proof-modal-title">Bukti Transfer</h3>
        <p style="color:#888; font-size:13px; margin-bottom:16px;" id="proof-modal-order"></p>
        <div style="border-radius:10px; overflow:hidden; border:1px solid #eae0d5; margin-bottom:20px; text-align:center;">
            <img id="proof-modal-img" src="" alt="Bukti Transfer" style="max-width:100%; max-height:380px; object-fit:contain;">
        </div>
        <div id="modal-actions" style="display:flex; gap:10px; flex-wrap:wrap;">
            <form id="form-confirm" method="POST" style="flex:1;">
                @csrf
                <button type="submit" style="width:100%; background:#10b981; color:white; border:none; padding:12px; border-radius:8px; font-weight:700; font-family:'Nunito',sans-serif; font-size:14px; cursor:pointer;">
                    Konfirmasi Lunas
                </button>
            </form>
            <form id="form-reject" method="POST" style="flex:1;" onsubmit="return confirm('Yakin ingin menolak pembayaran ini?');">
                @csrf
                <button type="submit" style="width:100%; background:#ef4444; color:white; border:none; padding:12px; border-radius:8px; font-weight:700; font-family:'Nunito',sans-serif; font-size:14px; cursor:pointer;">
                    Tolak Pembayaran
                </button>
            </form>
        </div>
        <div id="modal-status-text" style="display:none; text-align:center; padding:12px; border-radius:8px; font-weight:700; font-family:'Nunito',sans-serif; font-size:14px;"></div>
    </div>
</div>

<script>
function showProofModal(imgUrl, orderId, orderDbId, paymentStatus) {
    document.getElementById('proof-modal-img').src = imgUrl;
    document.getElementById('proof-modal-title').textContent = 'Bukti Transfer — ' + orderId;
    document.getElementById('form-confirm').action = '/admin/payment/confirm/' + orderDbId;
    document.getElementById('form-reject').action = '/admin/payment/reject/' + orderDbId;

    const actionsDiv = document.getElementById('modal-actions');
    const statusTextDiv = document.getElementById('modal-status-text');
    const descText = document.getElementById('proof-modal-order');

    if (paymentStatus === 'uploaded') {
        actionsDiv.style.display = 'flex';
        statusTextDiv.style.display = 'none';
        descText.textContent = 'Verifikasi foto bukti transfer sebelum mengkonfirmasi pembayaran.';
    } else if (paymentStatus === 'confirmed') {
        actionsDiv.style.display = 'none';
        statusTextDiv.style.display = 'block';
        statusTextDiv.style.backgroundColor = '#d1fae5';
        statusTextDiv.style.color = '#065f46';
        statusTextDiv.textContent = 'Pembayaran Telah Dikonfirmasi Lunas';
        descText.textContent = 'Status pembayaran: Lunas (Confirmed)';
    } else if (paymentStatus === 'rejected') {
        actionsDiv.style.display = 'none';
        statusTextDiv.style.display = 'block';
        statusTextDiv.style.backgroundColor = '#fee2e2';
        statusTextDiv.style.color = '#991b1b';
        statusTextDiv.textContent = 'Pembayaran Telah Ditolak';
        descText.textContent = 'Status pembayaran: Ditolak (Rejected)';
    } else {
        actionsDiv.style.display = 'none';
        statusTextDiv.style.display = 'block';
        statusTextDiv.style.backgroundColor = '#f3f4f6';
        statusTextDiv.style.color = '#374151';
        statusTextDiv.textContent = 'Status Pembayaran: ' + paymentStatus;
        descText.textContent = 'Status pembayaran saat ini.';
    }

    const modal = document.getElementById('proof-modal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeProofModal() {
    document.getElementById('proof-modal').style.display = 'none';
    document.body.style.overflow = '';
}
document.getElementById('proof-modal').addEventListener('click', function(e) {
    if (e.target === this) closeProofModal();
});
</script>
@endsection
