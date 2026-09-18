<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class StockController extends Controller
{
    // tampilin data stok barang buat monitoring gudang
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Product::with('category');

        // filter per kategori kalau dipilih
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // cari barang berdasarkan nama
        if ($request->filled('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        // filter kondisi stok: mau liat yang mau abis, yang udah ludes, atau yang aman
        if ($request->filled('status')) {
            if ($request->status === 'low') {
                // stok menipis (1 - 10 item)
                $query->where('stock', '<=', 10)->where('stock', '>', 0);
            } elseif ($request->status === 'out') {
                // stok bener-bener kosong
                $query->where('stock', '<=', 0);
            } elseif ($request->status === 'available') {
                // stok masih aman melimpah
                $query->where('stock', '>', 10);
            }
        }

        $products = $query->latest()->get();

        // hitung ringkasan buat kartu statistik di atas tabel
        $totalItems = Product::count();
        $totalStock = Product::sum('stock');
        $outOfStock = Product::where('stock', '<=', 0)->count();
        $lowStock = Product::where('stock', '<=', 10)->where('stock', '>', 0)->count();

        return view('stock.index', compact('products', 'categories', 'totalItems', 'totalStock', 'outOfStock', 'lowStock'));
    }
}
