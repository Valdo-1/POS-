@extends('app')

@section('header')
<div class="mb-2">
    <div class="flex items-center gap-2">
        <h1 class="text-xl font-black text-amber-100 uppercase tracking-wider font-mono">DASHBOARD UTAMA</h1>
        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-amber-900/40 text-amber-300 font-semibold border border-amber-700/40">SYSTEM READY</span>
    </div>
    <p class="text-xs text-stone-400 mt-1">Ringkasan operasional dan statistik real-time aplikasi <strong class="text-amber-300 font-bold">KETARA POS</strong>.</p>
</div>
@endsection

@section('content')
<!-- Banner Sapaan & Quick Action Shortcuts -->
<div class="glass-espresso rounded-3xl p-6 lg:p-7 shadow-glass-warm border border-amber-900/30 relative overflow-hidden">
    <div class="absolute -right-20 -bottom-20 w-60 h-60 bg-amber-700/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-900/40 border border-amber-700/40 text-amber-300 text-[10px] font-mono font-semibold uppercase mb-3">
                <i data-lucide="shield" class="w-3.5 h-3.5 text-amber-400"></i> Level Hak Akses: {{ Auth::user()->role->name ?? 'User' }}
            </div>
            <h2 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-amber-100">Selamat Datang, {{ Auth::user()->name }} 👋</h2>
            <p class="text-stone-400 text-xs mt-1.5 max-w-xl leading-relaxed">Sistem KETARA siap digunakan untuk memproses transaksi kasir, pemantauan stok produk, dan laporan omzet secara efisien.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if(Auth::user()->hasPermission('pos'))
                <a href="{{ route('transactions.index') }}" 
                   onclick="playWarmChime('tap')"
                   class="h-11 px-5 rounded-2xl bg-gradient-to-r from-amber-700 via-amber-600 to-amber-700 hover:shadow-glow-bronze text-amber-50 font-bold text-xs uppercase tracking-wider transition-all duration-300 border border-amber-500/40 flex items-center gap-2 shadow-lg active:scale-[0.98]">
                    <i data-lucide="shopping-bag" class="w-4 h-4 text-amber-200"></i>
                    <span>Buka Mesin POS</span>
                </a>
            @endif
            @if(Auth::user()->hasPermission('reports'))
                <a href="{{ route('reports.index') }}" 
                   onclick="playWarmChime('tap')"
                   class="h-11 px-4 rounded-2xl bg-stone-900/80 hover:bg-stone-800 border border-amber-900/30 text-amber-200 font-semibold text-xs transition flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 text-amber-400"></i>
                    <span>Laporan Omzet</span>
                </a>
            @endif
            @if(Auth::user()->hasPermission('stock'))
                <a href="{{ route('stock.index') }}" 
                   onclick="playWarmChime('tap')"
                   class="h-11 px-4 rounded-2xl bg-stone-900/80 hover:bg-stone-800 border border-amber-900/30 text-amber-200 font-semibold text-xs transition flex items-center gap-2">
                    <i data-lucide="boxes" class="w-4 h-4 text-amber-400"></i>
                    <span>Cek Stok</span>
                </a>
            @endif
        </div>
    </div>
</div>

<!-- 4 Kartu Ringkasan Cepat -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="card-espresso rounded-2xl p-5 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold text-amber-400/80 uppercase tracking-wider block">Total Katalog Produk</span>
            <div class="text-2xl font-black font-mono text-amber-100 tabular-nums">{{ $productCount }}</div>
            <span class="text-[10px] text-stone-500 font-mono">ITEM TERSEDA</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-950/60 border border-amber-800/40 text-amber-300 flex items-center justify-center shadow-inner">
            <i data-lucide="package" class="w-6 h-6"></i>
        </div>
    </div>
    
    <div class="card-espresso rounded-2xl p-5 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold text-amber-400/80 uppercase tracking-wider block">Total Transaksi</span>
            <div class="text-2xl font-black font-mono text-amber-100 tabular-nums">{{ $orderCount }}</div>
            <span class="text-[10px] text-stone-500 font-mono">PESANAN TERSELESAIKAN</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-950/60 border border-amber-800/40 text-amber-300 flex items-center justify-center shadow-inner">
            <i data-lucide="receipt" class="w-6 h-6"></i>
        </div>
    </div>

    <div class="card-espresso rounded-2xl p-5 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold text-amber-400/80 uppercase tracking-wider block">Total Penjualan</span>
            <div class="text-xl font-black font-mono text-amber-300 tabular-nums">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            <span class="text-[10px] text-stone-500 font-mono">AKUMULASI OMZET</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-950/60 border border-amber-800/40 text-amber-300 flex items-center justify-center shadow-inner">
            <i data-lucide="banknote" class="w-6 h-6"></i>
        </div>
    </div>

    <div class="card-espresso rounded-2xl p-5 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold text-amber-400/80 uppercase tracking-wider block">Kategori Menu</span>
            <div class="text-2xl font-black font-mono text-amber-100 tabular-nums">{{ $categoryCount }}</div>
            <span class="text-[10px] text-stone-500 font-mono">KATEGORI AKTIF</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-950/60 border border-amber-800/40 text-amber-300 flex items-center justify-center shadow-inner">
            <i data-lucide="tags" class="w-6 h-6"></i>
        </div>
    </div>
</div>

<!-- Informasi Hak Akses & Panduan Navigasi -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card-espresso rounded-2xl p-6">
        <h3 class="text-xs font-bold font-mono uppercase tracking-widest text-amber-300 mb-4 flex items-center gap-2 border-b border-amber-900/30 pb-3">
            <i data-lucide="shield-check" class="w-4 h-4 text-amber-400"></i>
            <span>Ringkasan Hak Akses Sistem</span>
        </h3>
        <ul class="space-y-3.5 text-xs">
            <li class="pb-3 border-b border-amber-900/20">
                <strong class="text-amber-100 block mb-0.5">Admin / Administrator:</strong>
                <p class="text-[11px] text-stone-400 leading-relaxed font-light">Memiliki akses penuh ke seluruh master data (Produk, Kategori, Users, Roles), serta monitoring transaksi dan laporan penjualan.</p>
            </li>
            <li class="pb-3 border-b border-amber-900/20">
                <strong class="text-amber-100 block mb-0.5">Kasir:</strong>
                <p class="text-[11px] text-stone-400 leading-relaxed font-light">Akses khusus mesin kasir POS untuk pemprosesan pesanan dan pemantauan ketersediaan stok barang.</p>
            </li>
            <li>
                <strong class="text-amber-100 block mb-0.5">Pimpinan & Role Kustom:</strong>
                <p class="text-[11px] text-stone-400 leading-relaxed font-light">Memiliki izin fitur yang dikonfigurasikan secara dinamis melalui menu Kelola Role & Hak Akses.</p>
            </li>
        </ul>
    </div>

    <div class="card-espresso rounded-2xl p-6">
        <h3 class="text-xs font-bold font-mono uppercase tracking-widest text-amber-300 mb-4 flex items-center gap-2 border-b border-amber-900/30 pb-3">
            <i data-lucide="compass" class="w-4 h-4 text-amber-400"></i>
            <span>Pintas Fitur Utama</span>
        </h3>
        <div class="space-y-2">
            @if(Auth::user()->hasPermission('users'))
            <a href="{{ route('users.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-stone-900/40 hover:bg-stone-800/80 border border-amber-900/20 hover:border-amber-700/40 text-stone-200 hover:text-amber-200 transition group text-xs font-medium">
                <div class="flex items-center gap-2.5">
                    <i data-lucide="users" class="w-4 h-4 text-stone-400 group-hover:text-amber-400"></i>
                    <span>Kelola User & Role System</span>
                </div>
                <i data-lucide="chevron-right" class="w-4 h-4 text-stone-600 group-hover:text-amber-400"></i>
            </a>
            @endif

            @if(Auth::user()->hasPermission('products'))
            <a href="{{ route('products.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-stone-900/40 hover:bg-stone-800/80 border border-amber-900/20 hover:border-amber-700/40 text-stone-200 hover:text-amber-200 transition group text-xs font-medium">
                <div class="flex items-center gap-2.5">
                    <i data-lucide="package" class="w-4 h-4 text-stone-400 group-hover:text-amber-400"></i>
                    <span>Kelola Master Katalog Produk</span>
                </div>
                <i data-lucide="chevron-right" class="w-4 h-4 text-stone-600 group-hover:text-amber-400"></i>
            </a>
            @endif

            @if(Auth::user()->hasPermission('stock'))
            <a href="{{ route('stock.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-stone-900/40 hover:bg-stone-800/80 border border-amber-900/20 hover:border-amber-700/40 text-stone-200 hover:text-amber-200 transition group text-xs font-medium">
                <div class="flex items-center gap-2.5">
                    <i data-lucide="boxes" class="w-4 h-4 text-stone-400 group-hover:text-amber-400"></i>
                    <span>Buka Monitoring Stok Produk</span>
                </div>
                <i data-lucide="chevron-right" class="w-4 h-4 text-stone-600 group-hover:text-amber-400"></i>
            </a>
            @endif

            @if(Auth::user()->hasPermission('pos'))
            <a href="{{ route('transactions.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-stone-900/40 hover:bg-stone-800/80 border border-amber-900/20 hover:border-amber-700/40 text-stone-200 hover:text-amber-200 transition group text-xs font-medium">
                <div class="flex items-center gap-2.5">
                    <i data-lucide="shopping-bag" class="w-4 h-4 text-stone-400 group-hover:text-amber-400"></i>
                    <span>Buka Mesin Kasir POS</span>
                </div>
                <i data-lucide="chevron-right" class="w-4 h-4 text-stone-600 group-hover:text-amber-400"></i>
            </a>
            @endif

            @if(Auth::user()->hasPermission('reports'))
            <a href="{{ route('reports.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-stone-900/40 hover:bg-stone-800/80 border border-amber-900/20 hover:border-amber-700/40 text-stone-200 hover:text-amber-200 transition group text-xs font-medium">
                <div class="flex items-center gap-2.5">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 text-stone-400 group-hover:text-amber-400"></i>
                    <span>Buka Laporan Penjualan & Omzet</span>
                </div>
                <i data-lucide="chevron-right" class="w-4 h-4 text-stone-600 group-hover:text-amber-400"></i>
            </a>
            @endif
        </div>
    </div>
</div>
@endsection

