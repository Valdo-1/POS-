@extends('app')

@section('header')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">TAMBAH PRODUK BARU</h1>
        <p class="text-slate-500 text-sm">Input data menu makanan / minuman baru ke katalog KETARA</p>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-semibold">
            <li><a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-emerald-600">Home</a></li>
            <li><span class="text-slate-300">/</span></li>
            <li><a href="{{ route('products.index') }}" class="text-slate-500 hover:text-emerald-600">Products</a></li>
            <li><span class="text-slate-300">/</span></li>
            <li class="text-emerald-700 font-bold" aria-current="page">Create</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs max-w-3xl">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Kategori Menu <span class="text-rose-500">*</span></label>
                <select name="category_id" id="category_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:border-emerald-500" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-rose-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="product_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Produk / Menu <span class="text-rose-500">*</span></label>
                <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}" 
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500" placeholder="Contoh: Kopi Susu Aren" required>
                @error('product_name')
                    <div class="text-rose-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="product_price" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Harga Satuan (Rp) <span class="text-rose-500">*</span></label>
                <input type="number" name="product_price" id="product_price" value="{{ old('product_price') }}" 
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none focus:border-emerald-500" placeholder="15000" min="0" required>
                @error('product_price')
                    <div class="text-rose-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="stock" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Stok Awal <span class="text-rose-500">*</span></label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', 50) }}" 
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none focus:border-emerald-500" placeholder="50" min="0" required>
                @error('stock')
                    <div class="text-rose-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div>
            <label for="product_photo" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Foto Produk (Opsional)</label>
            <input type="file" name="product_photo" id="product_photo" accept="image/*"
                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700">
            @error('product_photo')
                <div class="text-rose-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="product_description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Deskripsi Produk (Opsional)</label>
            <textarea name="product_description" id="product_description" rows="3" 
                      class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500" placeholder="Catatan komposisi / rasa menu...">{{ old('product_description') }}</textarea>
            @error('product_description')
                <div class="text-rose-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                   class="w-4 h-4 text-emerald-600 bg-slate-100 border-slate-300 rounded focus:ring-emerald-500">
            <label for="is_active" class="text-xs font-bold text-slate-700 cursor-pointer">Aktifkan produk ini di mesin kasir POS</label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
            <a href="{{ route('products.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer flex items-center gap-1">
                <i class="bi bi-save"></i> Simpan Produk
            </button>
        </div>
    </form>
</div>
@endsection
