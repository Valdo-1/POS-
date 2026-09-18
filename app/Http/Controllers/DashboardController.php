<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class DashboardController extends Controller
{
    // tampilin ringkasan angka-angka penting di halaman beranda
    public function index()
    {
        // tarik data statistik kilat buat kartu ringkasan
        $productCount = Product::count();
        $categoryCount = Category::count();
        $orderCount = Order::count();
        $totalSales = Order::sum('order_amount');

        return view('dashboard', compact('productCount', 'categoryCount', 'orderCount', 'totalSales'));
    }
}
