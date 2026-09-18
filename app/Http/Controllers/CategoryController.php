<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    // tampilin daftar kategori menu
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.category.index', compact('categories'));
    }

    // form bikin kategori baru
    public function create()
    {
        return view('admin.category.create');
    }

    // simpen nama kategori baru
    public function store(Request $request)
    {
        // nama kategori harus unik biar ga dobel-dobel
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name'
        ]);

        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function show(string $id)
    {
        // ga dipake, kategori cuma butuh nama aja
    }

    // form edit nama kategori
    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    // simpen update nama kategori
    public function update(Request $request, Category $category)
    {
        // validasi unik tapi lewati ID kategori ini sendiri biar ga error pas save
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name,' . $category->id
        ]);

        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    // hapus kategori
    public function destroy(Category $category)
    {
        // tolak hapus kalau masih ada produk di dalam kategori ini (anak yatim pencegahan)
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Cannot delete category because it contains products.');
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
