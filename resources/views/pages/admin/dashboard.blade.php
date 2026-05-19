@extends('layouts.admin')

@section('page-title', 'Ringkasan Sistem')

@section('content')
    <div class="summary-grid">
        <div class="stat-card">
            <h3>Total Produk</h3>
            <div class="number">{{ $totalProducts }}</div>
        </div>
        <div class="stat-card">
            <h3>Pesanan Menunggu</h3>
            <div class="number">{{ $pendingOrders }}</div>
        </div>
        <div class="stat-card">
            <h3>Pendapatan Bulan Ini</h3>
            <div class="number">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 30px;">
        <!-- Statistik Penjualan -->
        <div class="table-container" style="margin-bottom: 0;">
            <div class="table-header">
                <h2>Statistik Penjualan (Bulan Ini)</h2>
            </div>
            <div style="padding: 20px;">
                <canvas id="salesChart" style="width: 100%; height: 300px;"></canvas>
            </div>
        </div>

        <!-- Produk Terpopuler -->
        <div class="table-container" style="margin-bottom: 0;">
            <div class="table-header">
                <h2>Produk Terpopuler</h2>
            </div>
            <div style="padding: 10px 20px 20px 20px;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @forelse($popularProducts as $pop)
                        <li style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; padding: 12px 0;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <img src="{{ optional($pop->product)->image ? asset('storage/' . optional($pop->product)->image) : 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=100&auto=format&fit=crop' }}" alt="{{ optional($pop->product)->name ?? 'Produk' }}" style="width: 45px; height: 45px; border-radius: 8px; object-fit: cover;">
                                <div>
                                    <h4 style="margin: 0; font-size: 15px; font-family: 'Nunito', sans-serif;">{{ \Illuminate\Support\Str::limit(optional($pop->product)->name ?? 'Produk Terhapus', 20) }}</h4>
                                    <small style="color: #666;">Rp {{ number_format(optional($pop->product)->price ?? 0, 0, ',', '.') }}</small>
                                </div>
                            </div>
                            <div style="background-color: var(--cokelat-utama); color: white; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                                {{ $pop->total_sold }} Terjual
                            </div>
                        </li>
                    @empty
                        <li style="text-align: center; color: #888; padding: 20px;">Belum ada data penjualan.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h2>Transaksi Terbaru</h2>
            <button class="btn-tambah">Cetak Laporan</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Item</th>
                    <th>Total Pembayaran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td>#TRX-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $order->user->name ?? 'User Terhapus' }}</td>
                        <td>{{ $order->product->name ?? 'Produk Terhapus' }}</td>
                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $statusClass = match($order->status) {
                                    'menunggu' => 'status-pending',
                                    'proses' => 'status-pending',
                                    'selesai' => 'status-lunas',
                                    'diambil' => 'status-lunas',
                                    'batal' => 'status-pending',
                                    default => 'status-pending'
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}" style="text-transform: capitalize;">{{ $order->status }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_map(fn($day) => 'Tgl ' . $day, $chartLabels)) !!},
                    datasets: [{
                        label: 'Jumlah Pesanan Selesai',
                        data: @json($chartData),
                        backgroundColor: 'rgba(156, 122, 99, 0.2)',
                        borderColor: '#9c7a63',
                        borderWidth: 2,
                        pointBackgroundColor: '#9c7a63',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
