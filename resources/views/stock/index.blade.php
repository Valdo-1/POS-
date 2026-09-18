@extends('app')

@section('header')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">MONITORING STOK PRODUK</h1>
        <p class="text-slate-500 text-sm">Informasi ketersediaan stok barang resto KETARA</p>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-semibold">
            <li><a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-emerald-600">Home</a></li>
            <li><span class="text-slate-300">/</span></li>
            <li class="text-emerald-700 font-bold" aria-current="page">Stok Produk</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<!-- 4 Kartu Ringkasan Stok Barang -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl font-bold">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Produk</div>
                <div class="text-2xl font-black text-slate-800">{{ $totalItems }}</div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl font-bold">
                <i class="bi bi-stack"></i>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Stok Fisik</div>
                <div class="text-2xl font-black text-slate-800">{{ number_format($totalStock, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl font-bold">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Stok Menipis (&le;10)</div>
                <div class="text-2xl font-black text-amber-600">{{ $lowStock }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl font-bold">
                <i class="bi bi-x-octagon"></i>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Stok Ludes (0)</div>
                <div class="text-2xl font-black text-rose-600">{{ $outOfStock }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Card Tabel Stok Produk -->
<div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
        <h3 class="font-bold text-lg text-slate-800">Daftar Ketersediaan Barang</h3>

        <!-- Filters Form -->
        <form action="{{ route('stock.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
            <select name="category_id" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-emerald-500" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-emerald-500" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Stok Aman (&gt;10)</option>
                <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Stok Menipis (&le;10)</option>
                <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Stok Ludes (0)</option>
            </select>

            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" class="pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-emerald-500" placeholder="Cari barang...">
                <i class="bi bi-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
            </div>

            @if(request('category_id') || request('search') || request('status'))
                <a href="{{ route('stock.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-colors" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4 w-16">Foto</th>
                    <th class="py-3 px-4">Nama Produk</th>
                    <th class="py-3 px-4">Kategori</th>
                    <th class="py-3 px-4">Harga Satuan</th>
                    <th class="py-3 px-4 text-center">Sisa Stok</th>
                    <th class="py-3 px-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-3 px-4">
                        @if($product->product_photo && file_exists(public_path('storage/' . $product->product_photo)))
                            <img src="{{ asset('storage/'.$product->product_photo) }}" alt="{{ $product->product_name }}" class="w-10 h-10 rounded-lg object-cover border border-slate-200">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        @endif
                    </td>
                    <td class="py-3 px-4 font-bold text-slate-800">{{ $product->product_name }}</td>
                    <td class="py-3 px-4">
                        <span class="bg-slate-100 text-slate-700 border border-slate-200 px-2.5 py-0.5 rounded-full text-[11px] font-semibold">
                            {{ $product->category->category_name ?? '-' }}
                        </span>
                    </td>
                    <td class="py-3 px-4 font-extrabold text-emerald-700">Rp {{ number_format($product->product_price, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 text-center font-bold text-slate-800 text-sm">{{ $product->stock }}</td>
                    <td class="py-3 px-4 text-center">
                        @if($product->stock > 10)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Aman</span>
                        @elseif($product->stock > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Menipis</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">Ludes</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-slate-400">Data produk tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
