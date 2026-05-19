<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class AdminController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $pendingOrders = Order::where('status', 'menunggu')->count();
        
        // Asumsikan pesanan selesai/diambil yang sudah lunas/diterima
        $monthlyIncome = Order::whereMonth('created_at', date('m'))
                            ->whereYear('created_at', date('Y'))
                            ->whereIn('status', ['selesai', 'diambil'])
                            ->sum('total_price');
                            
        $recentOrders = Order::with(['user', 'product'])->latest()->take(5)->get();

        // 1. Produk Terpopuler
        $popularProducts = Order::select('product_id', \Illuminate\Support\Facades\DB::raw('count(*) as total_sold'))
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->take(5)
            ->get();

        // 2. Statistik Penjualan Bulanan (Harian untuk Bulan Ini)
        $salesThisMonth = Order::select(
            \Illuminate\Support\Facades\DB::raw('DAY(created_at) as day'),
            \Illuminate\Support\Facades\DB::raw('COUNT(*) as total_orders')
        )
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->whereIn('status', ['selesai', 'diambil'])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Dapatkan jumlah hari pada bulan ini
        $daysInMonth = (int)date('t');
        $chartData = array_fill(0, $daysInMonth, 0);
        foreach ($salesThisMonth as $sale) {
            $chartData[$sale->day - 1] = $sale->total_orders;
        }
        $chartLabels = range(1, $daysInMonth);

        return view('pages.admin.dashboard', compact('totalProducts', 'pendingOrders', 'monthlyIncome', 'recentOrders', 'popularProducts', 'chartData', 'chartLabels'));
    }
}
