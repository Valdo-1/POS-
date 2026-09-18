@extends('app')

@section('header')
<div class="mb-2 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-2">
            <h1 class="text-xl font-black text-amber-100 uppercase tracking-wider font-mono">MONITORING STOK PRODUK</h1>
            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-amber-900/40 text-amber-300 font-semibold border border-amber-700/40">INVENTORY CONTROL</span>
        </div>
        <p class="text-xs text-stone-400 mt-1">Informasi ketersediaan stok fisik barang resto KETARA</p>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-[11px] font-mono">
            <li><a href="{{ route('dashboard') }}" class="text-stone-500 hover:text-amber-300">Home</a></li>
            <li><span class="text-stone-600">/</span></li>
            <li class="text-amber-300 font-semibold" aria-current="page">Stok Produk</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<!-- 4 Kartu Ringkasan Stok Barang -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card-espresso rounded-2xl p-5 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold text-amber-400/80 uppercase tracking-wider block">Total Katalog Barang</span>
            <div class="text-2xl font-black font-mono text-amber-100 tabular-nums">{{ $totalItems }}</div>
            <span class="text-[10px] text-stone-500 font-mono">JENIS PRODUK</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-950/60 border border-amber-800/40 text-amber-300 flex items-center justify-center shadow-inner">
            <i data-lucide="package" class="w-6 h-6"></i>
        </div>
    </div>
    
    <div class="card-espresso rounded-2xl p-5 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold text-amber-400/80 uppercase tracking-wider block">Total Stok Fisik</span>
            <div class="text-2xl font-black font-mono text-amber-100 tabular-nums">{{ number_format($totalStock, 0, ',', '.') }}</div>
            <span class="text-[10px] text-stone-500 font-mono">TOTAL PCS</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-950/60 border border-amber-800/40 text-amber-300 flex items-center justify-center shadow-inner">
            <i data-lucide="layers" class="w-6 h-6"></i>
        </div>
    </div>

    <div class="card-espresso rounded-2xl p-5 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold text-amber-400/80 uppercase tracking-wider block">Stok Menipis (&le;10)</span>
            <div class="text-2xl font-black font-mono text-amber-400 tabular-nums">{{ $lowStock }}</div>
            <span class="text-[10px] text-amber-500/80 font-mono">PERLU RESTOK</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-950/60 border border-amber-600/40 text-amber-400 flex items-center justify-center shadow-inner">
            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
        </div>
    </div>

    <div class="card-espresso rounded-2xl p-5 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold text-rose-400/80 uppercase tracking-wider block">Stok Ludes (0)</span>
            <div class="text-2xl font-black font-mono text-rose-400 tabular-nums">{{ $outOfStock }}</div>
            <span class="text-[10px] text-rose-500/80 font-mono">STOK HABIS</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-rose-950/60 border border-rose-800/40 text-rose-400 flex items-center justify-center shadow-inner">
            <i data-lucide="x-circle" class="w-6 h-6"></i>
        </div>
    </div>
</div>

<!-- Card Tabel Stok Produk -->
<div class="glass-espresso rounded-3xl p-6 border border-amber-900/30 shadow-glass-warm">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-4 border-b border-amber-900/30">
        <h3 class="text-xs font-bold font-mono uppercase tracking-widest text-amber-300 flex items-center gap-2">
            <i data-lucide="list" class="w-4 h-4 text-amber-400"></i>
            <span>Daftar Ketersediaan Barang</span>
        </h3>

        <!-- Filters Form -->
        <form action="{{ route('stock.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
            <select name="category_id" class="h-9 px-3 bg-stone-900/80 border border-amber-900/30 rounded-xl text-xs font-medium text-stone-200 focus:outline-none focus:border-amber-500/60" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="h-9 px-3 bg-stone-900/80 border border-amber-900/30 rounded-xl text-xs font-medium text-stone-200 focus:outline-none focus:border-amber-500/60" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Stok Aman (&gt;10)</option>
                <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Stok Menipis (&le;10)</option>
                <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Stok Ludes (0)</option>
            </select>

            <div class="relative">
                <i data-lucide="search" class="w-3.5 h-3.5 text-stone-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="h-9 pl-9 pr-3 bg-stone-900/80 border border-amber-900/30 rounded-xl text-xs text-stone-100 placeholder-stone-500 focus:outline-none focus:border-amber-500/60" placeholder="Cari barang...">
            </div>

            @if(request('category_id') || request('search') || request('status'))
                <a href="{{ route('stock.index') }}" class="h-9 px-3 rounded-xl bg-stone-900 hover:bg-stone-800 text-stone-400 hover:text-amber-200 border border-amber-900/30 flex items-center justify-center transition" title="Reset Filter">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-stone-900/60 text-stone-400 uppercase font-mono text-[10px] border-b border-amber-900/30">
                <tr>
                    <th class="py-3 px-4 w-16">Foto</th>
                    <th class="py-3 px-4">Nama Produk</th>
                    <th class="py-3 px-4">Kategori</th>
                    <th class="py-3 px-4">Harga Satuan</th>
                    <th class="py-3 px-4 text-center">Sisa Stok</th>
                    <th class="py-3 px-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-amber-900/20 text-stone-200 font-sans">
                @forelse($products as $product)
                <tr class="hover:bg-stone-900/40 transition">
                    <td class="py-3 px-4">
                        @if($product->product_photo && file_exists(public_path('storage/' . $product->product_photo)))
                            <img src="{{ asset('storage/'.$product->product_photo) }}" alt="{{ $product->product_name }}" class="w-10 h-10 rounded-xl object-cover border border-amber-900/30">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-stone-900 border border-amber-900/30 text-amber-400/40 flex items-center justify-center">
                                <i data-lucide="coffee" class="w-5 h-5"></i>
                            </div>
                        @endif
                    </td>
                    <td class="py-3 px-4 font-bold text-amber-100">{{ $product->product_name }}</td>
                    <td class="py-3 px-4">
                        <span class="bg-amber-950/60 text-amber-300 border border-amber-700/40 px-2.5 py-0.5 rounded-full text-[10px] font-mono uppercase">
                            {{ $product->category->category_name ?? '-' }}
                        </span>
                    </td>
                    <td class="py-3 px-4 font-extrabold font-mono text-amber-200 tabular-nums">Rp {{ number_format($product->product_price, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 text-center font-bold font-mono text-stone-100 text-sm tabular-nums">{{ $product->stock }}</td>
                    <td class="py-3 px-4 text-center">
                        @if($product->stock > 10)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-amber-900/30 text-amber-300 border border-amber-700/40">AMAN</span>
                        @elseif($product->stock > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-amber-950/60 text-amber-400 border border-amber-600/40">MENIPIS</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-rose-950/60 text-rose-300 border border-rose-800/40">LUDES</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-stone-500 font-mono">Data produk tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

