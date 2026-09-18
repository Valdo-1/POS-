@extends('app')

@section('header')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">TAMBAH KATEGORI BARU</h1>
        <p class="text-slate-500 text-sm">Buat kategori menu baru untuk pengelompokan produk KETARA</p>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-semibold">
            <li><a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-emerald-600">Home</a></li>
            <li><span class="text-slate-300">/</span></li>
            <li><a href="{{ route('categories.index') }}" class="text-slate-500 hover:text-emerald-600">Categories</a></li>
            <li><span class="text-slate-300">/</span></li>
            <li class="text-emerald-700 font-bold" aria-current="page">Create</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs max-w-2xl">
    <form action="{{ route('categories.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label for="category_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
            <input type="text" name="category_name" id="category_name" value="{{ old('category_name') }}" 
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" placeholder="Contoh: Coffee, Pastry, Main Course" required>
            @error('category_name')
                <div class="text-rose-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
            <a href="{{ route('categories.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer flex items-center gap-1">
                <i class="bi bi-save"></i> Simpan Kategori
            </button>
        </div>
    </form>
</div>
@endsection
