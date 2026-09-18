<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // list semua produk buat halaman manajemen admin
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        return view('admin.product.index', compact('products'));
    }

    // buka form tambah menu/produk baru
    public function create()
    {
        $categories = Category::all();
        return view('admin.product.create', compact('categories'));
    }

    // simpan produk baru ke database
    public function store(Request $request)
    {
        // filter input biar ga ada data ngaco
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255',
            'product_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'product_price' => 'required|integer|min:0',
            'product_description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'stock' => 'required|integer|min:0',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
            'stock' => $request->stock,
        ];

        // simpan foto ke disk publik kalau user upload gambar
        if ($request->hasFile('product_photo')) {
            $data['product_photo'] = $request->file('product_photo')->store('products', 'public');
        }

        Product::create($data);
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(string $id)
    {
        // belum kepakai, detail produk langsung diedit aja
    }

    // form edit data produk
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    // simpan perubahan data produk
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255',
            'product_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'product_price' => 'required|integer|min:0',
            'product_description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'stock' => 'required|integer|min:0',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
            'stock' => $request->stock,
        ];

        // timpa foto lama kalau ada upload foto baru
        if ($request->hasFile('product_photo')) {
            $data['product_photo'] = $request->file('product_photo')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // hapus produk
    public function destroy(Product $product)
    {
        // jangan hapus produk kalau udah pernah kejual, nanti data riwayat transaksi kasir jadi rusak
        $hasOrders = DB::table('order_details')->where('product_id', $product->id)->exists();
        if ($hasOrders) {
            return redirect()->route('products.index')->with('error', 'Cannot delete product because it has associated transactions.');
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
