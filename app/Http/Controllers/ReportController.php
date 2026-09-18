<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // rekap omzet & data penjualan buat pimpinan dan admin
    public function index(Request $request)
    {
        // default-nya tarik laporan hari ini kalau ga ada pilihan periode
        $period = $request->get('period', 'daily');

        // eager load kasir dan item produk biar ga kena masalah n+1 query
        $query = Order::with(['user', 'details.product']);

        $startDate = null;
        $endDate = null;
        $filterTitle = '';

        if ($period === 'daily') {
            // filter 1 hari penuh
            $selectedDate = $request->get('date', date('Y-m-d'));
            $startDate = Carbon::parse($selectedDate)->startOfDay();
            $endDate = Carbon::parse($selectedDate)->endOfDay();
            $filterTitle = 'Laporan Harian: ' . Carbon::parse($selectedDate)->translatedFormat('d F Y');
            $query->whereBetween('order_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        } elseif ($period === 'weekly') {
            // filter rentang 7 hari terakhir
            $selectedWeek = $request->get('start_date', Carbon::now()->subDays(6)->format('Y-m-d'));
            $selectedEnd = $request->get('end_date', Carbon::now()->format('Y-m-d'));
            $startDate = Carbon::parse($selectedWeek)->startOfDay();
            $endDate = Carbon::parse($selectedEnd)->endOfDay();
            $filterTitle = 'Laporan Mingguan: ' . Carbon::parse($selectedWeek)->format('d M Y') . ' s/d ' . Carbon::parse($selectedEnd)->format('d M Y');
            $query->whereBetween('order_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        } elseif ($period === 'monthly') {
            // filter dari awal bulan sampai akhir bulan terpilih
            $selectedMonth = $request->get('month', date('m'));
            $selectedYear = $request->get('year', date('Y'));
            $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();
            $filterTitle = 'Laporan Bulanan: ' . Carbon::createFromDate($selectedYear, $selectedMonth, 1)->translatedFormat('F Y');
            $query->whereBetween('order_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        }

        $orders = $query->latest('id')->get();

        // hitung total transaksi & total pemasukan
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('order_amount');

        // kumpulin semua ID order buat ngitung rincian produk yang kejual
        $orderIds = $orders->pluck('id');
        $totalItemsSold = OrderDetail::whereIn('order_id', $orderIds)->sum('qty');

        // cari 5 menu yang paling laris manis di periode ini
        $topProducts = OrderDetail::whereIn('order_id', $orderIds)
            ->select('product_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(order_amount) as total_sales'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('report.index', compact(
            'orders',
            'period',
            'filterTitle',
            'totalOrders',
            'totalRevenue',
            'totalItemsSold',
            'topProducts'
        ));
    }
}
