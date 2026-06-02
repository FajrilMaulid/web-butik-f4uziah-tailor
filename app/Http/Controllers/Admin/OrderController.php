<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $orders = Order::with(['user', 'product'])
            ->when($search, fn($q) => $q
                ->whereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"))
                ->orWhereHas('product', fn($pq) => $pq->where('name', 'like', "%{$search}%"))
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhere(\Illuminate\Support\Facades\DB::raw('CONCAT("#ORD-", LPAD(id, 5, "0"))'), 'like', "%{$search}%")
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();
        return view('pages.admin.orders.index', compact('orders', 'search'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('pages.admin.orders.create', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'required|string|max:50',
            'status' => 'required|in:menunggu,proses,selesai,diambil,batal',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($request->product_id);
        $totalPrice = $product->price * $request->quantity;

        // Gabungan ukuran dan kuantitas
        $notes = 'Ukuran: ' . $request->size . ', Jumlah: ' . $request->quantity;
        if ($request->filled('additional_notes')) {
            $notes .= '. Catatan: ' . $request->additional_notes;
        }

        Order::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'total_price' => $totalPrice,
            'status' => $request->status,
            'notes' => $notes,
        ]);

        return redirect()->route('orders.index')->with('success', 'Transaksi manual berhasil ditambahkan!');
    }

    public function edit(Order $order)
    {
        $users = User::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        // Mengurai kolom notes untuk mengambil data Ukuran, Jumlah, dan Catatan Tambahan
        $notes = $order->notes;

        $size = 'S';
        $quantity = 1;
        $additionalNotes = '';

        // Mengurai Ukuran
        if (preg_match('/Ukuran:\s*([^,]+)/i', $notes, $matches)) {
            $size = trim($matches[1]);
        }

        // Mengurai Jumlah
        if (preg_match('/Jumlah:\s*(\d+)/i', $notes, $matches)) {
            $quantity = (int) trim($matches[1]);
        }

        // Mengurai Catatan Tambahan
        if (preg_match('/\. Catatan:\s*(.*)/is', $notes, $matches)) {
            $additionalNotes = trim($matches[1]);
        }

        return view('pages.admin.orders.edit', compact('order', 'users', 'products', 'size', 'quantity', 'additionalNotes'));
    }

    public function update(Request $request, Order $order)
    {
        // Cek update status
        if ($request->has('status') && !$request->has('user_id')) {
            $request->validate([
                'status' => 'required|in:menunggu,proses,selesai,diambil,batal'
            ]);

            $order->update(['status' => $request->status]);
            return back()->with('success', 'Status pesanan berhasil diperbarui.');
        }

        // Update penuh dari halaman edit
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'required|string|max:50',
            'status' => 'required|in:menunggu,proses,selesai,diambil,batal',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($request->product_id);
        $totalPrice = $product->price * $request->quantity;

        // Gabungan ukuran dan kuantitas
        $notes = 'Ukuran: ' . $request->size . ', Jumlah: ' . $request->quantity;
        if ($request->filled('additional_notes')) {
            $notes .= '. Catatan: ' . $request->additional_notes;
        }

        $order->update([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'total_price' => $totalPrice,
            'status' => $request->status,
            'notes' => $notes,
        ]);

        return redirect()->route('orders.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Pesanan berhasil dihapus.');
    }

    public function export(\Illuminate\Http\Request $request)
    {
        $filter = $request->query('filter', 'all');
        $fileName = 'Data_Transaksi_' . ucfirst($filter) . '_' . date('Y-m-d_H-i') . '.xls';

        $query = Order::with(['user', 'product'])->latest();

        if ($filter === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
        }

        $orders = $query->get();

        $filterText = match($filter) {
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
            default => 'Semua Waktu'
        };

        $datePrinted = date('d-m-Y H:i:s');

        // Render HTML dengan CSS untuk styling Excel
        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--[if gte mso 9]>
<xml>
<x:ExcelWorkbook>
<x:ExcelWorksheets>
<x:ExcelWorksheet>
<x:Name>Data Transaksi</x:Name>
<x:WorksheetOptions>
<x:DisplayGridlines/>
</x:WorksheetOptions>
</x:ExcelWorksheet>
</x:ExcelWorksheets>
</x:ExcelWorkbook>
</xml>
<![endif]-->
<style>
    body {
        font-family: \'Segoe UI\', \'Nunito\', \'Arial\', sans-serif;
        color: #333333;
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th {
        background-color: #9c7a63;
        color: #ffffff;
        font-weight: bold;
        border: 1px solid #eae0d5;
        padding: 12px 10px;
        font-size: 11pt;
        text-align: center;
    }
    td {
        border: 1px solid #eae0d5;
        padding: 10px 8px;
        font-size: 10pt;
        vertical-align: middle;
    }
    .row-even {
        background-color: #ffffff;
    }
    .row-odd {
        background-color: #fdf8f0;
    }
    .text-center {
        text-align: center;
    }
    .text-right {
        text-align: right;
    }
    .text-bold {
        font-weight: bold;
    }
    .title-block {
        font-size: 16pt;
        font-family: \'Lora\', \'Georgia\', serif;
        font-weight: bold;
        color: #85644c;
    }
    .subtitle-block {
        font-size: 12pt;
        font-family: \'Lora\', \'Georgia\', serif;
        color: #9c7a63;
    }
    .meta-block {
        font-size: 9pt;
        color: #666666;
    }
    /* Format angka & teks Excel */
    .text-format {
        mso-number-format: "\@";
    }
    .num-format {
        mso-number-format: "\#\,\#\#0";
    }
    .date-format {
        mso-number-format: "yyyy-mm-dd hh:mm:ss";
    }
</style>
</head>
<body>
    <table>
        <tr>
            <td colspan="8" class="title-block" style="text-align: center; height: 35px; border: none;">
                LAPORAN DATA TRANSAKSI & PENJUALAN
            </td>
        </tr>
        <tr>
            <td colspan="8" class="subtitle-block" style="text-align: center; height: 25px; border: none;">
                Butik F4UZIAHTAILOR
            </td>
        </tr>
        <tr>
            <td colspan="8" class="meta-block" style="text-align: center; height: 20px; border: none;">
                Filter Waktu: ' . $filterText . ' | Tanggal Unduh: ' . $datePrinted . '
            </td>
        </tr>
        <tr>
            <td colspan="8" style="height: 15px; border: none;"></td>
        </tr>
        <thead>
            <tr>
                <th style="width: 110px;">ID Pesanan</th>
                <th style="width: 160px;">Pelanggan</th>
                <th style="width: 250px;">Alamat</th>
                <th style="width: 160px;">Produk</th>
                <th style="width: 120px;">Total Harga</th>
                <th style="width: 100px;">Status</th>
                <th style="width: 220px;">Catatan / Detail Ukuran</th>
                <th style="width: 150px;">Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($orders as $index => $order) {
            $rowClass = ($index % 2 === 0) ? 'row-even' : 'row-odd';
            $orderId = '#ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
            $pelanggan = htmlspecialchars($order->user->name ?? 'User Terhapus', ENT_QUOTES, 'UTF-8');
            $alamat = htmlspecialchars(str_replace(["\r", "\n"], ' ', $order->user->address ?? 'Belum diisi'), ENT_QUOTES, 'UTF-8');
            $produk = htmlspecialchars($order->product->name ?? 'Produk Terhapus', ENT_QUOTES, 'UTF-8');
            $totalHarga = $order->total_price;
            $status = ucfirst($order->status);
            $catatan = htmlspecialchars(str_replace(["\r", "\n"], ' ', $order->notes ?? '-'), ENT_QUOTES, 'UTF-8');
            $tanggal = $order->created_at->format('Y-m-d H:i:s');

            // Warna status
            $statusBg = match($order->status) {
                'menunggu' => '#fff3cd',
                'proses' => '#cce5ff',
                'selesai' => '#d4edda',
                'diambil' => '#e2e3e5',
                'batal' => '#f8d7da',
                default => '#f9f9f9'
            };
            $statusColor = match($order->status) {
                'menunggu' => '#856404',
                'proses' => '#004085',
                'selesai' => '#155724',
                'diambil' => '#383d41',
                'batal' => '#721c24',
                default => '#333333'
            };

            $html .= '
            <tr class="' . $rowClass . '">
                <td class="text-format text-center" style="font-weight: bold;">' . $orderId . '</td>
                <td>' . $pelanggan . '</td>
                <td>' . $alamat . '</td>
                <td>' . $produk . '</td>
                <td class="num-format text-right">Rp ' . number_format($totalHarga, 0, ',', '.') . '</td>
                <td class="text-center" style="background-color: ' . $statusBg . '; color: ' . $statusColor . '; font-weight: bold;">' . $status . '</td>
                <td>' . $catatan . '</td>
                <td class="date-format text-center">' . $tanggal . '</td>
            </tr>';
        }

        $html .= '
        </tbody>
    </table>
</body>
</html>';

        $headers = [
            "Content-type"        => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return response($html, 200, $headers);
    }
}
