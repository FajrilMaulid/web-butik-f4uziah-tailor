@extends('layouts.admin')

@section('page-title', 'Data Transaksi')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-container">
        <div class="table-header">
            <h2>Daftar Pesanan & Transaksi</h2>
        </div>
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
                            <td>{{ $order->user->name ?? 'User Terhapus' }}</td>
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

                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesanan ini secara permanen?');" style="align-self: flex-start;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" style="padding: 6px 12px;">Hapus</button>
                                    </form>
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
    </div>
@endsection
