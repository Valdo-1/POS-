@extends('app')

@section('header')
<div class="mb-6">
    <h1 class="text-2xl font-black text-slate-800 tracking-tight">DASHBOARD</h1>
    <p class="text-slate-500 text-sm">Selamat datang di <strong class="text-emerald-700 font-extrabold">KETARA Point of Sales</strong> PPKD Jakarta Pusat.</p>
</div>
@endsection

@section('content')
<!-- Banner Sapaan & Quick Action Shortcuts -->
<div class="bg-gradient-to-br from-emerald-900 via-emerald-800 to-slate-900 text-white rounded-3xl p-6 lg:p-8 mb-8 shadow-xl shadow-emerald-950/20 relative overflow-hidden">
    <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold mb-3">
                <i class="bi bi-person-badge"></i> Role: {{ ucfirst(Auth::user()->role->name ?? 'User') }}
            </div>
            <h2 class="text-2xl lg:text-3xl font-black tracking-tight">Halo, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-emerald-100/80 text-sm mt-1 max-w-xl">Sistem KETARA siap digunakan untuk memproses transaksi kasir, pemantauan stok produk, dan laporan omzet secara efisien.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if(Auth::user()->hasPermission('pos'))
                <a href="{{ route('transactions.index') }}" class="px-5 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-white font-bold text-sm shadow-lg shadow-emerald-900/40 flex items-center gap-2 transition-all duration-200">
                    <i class="bi bi-cart-check text-lg"></i>
                    <span>Buka Mesin POS</span>
                </a>
            @endif
            @if(Auth::user()->hasPermission('reports'))
                <a href="{{ route('reports.index') }}" class="px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold text-sm flex items-center gap-2 transition-all duration-200 backdrop-blur-md">
                    <i class="bi bi-graph-up text-lg"></i>
                    <span>Lihat Laporan</span>
                </a>
            @endif
            @if(Auth::user()->hasPermission('stock'))
                <a href="{{ route('stock.index') }}" class="px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold text-sm flex items-center gap-2 transition-all duration-200 backdrop-blur-md">
                    <i class="bi bi-boxes text-lg"></i>
                    <span>Cek Stok</span>
                </a>
            @endif
        </div>
    </div>
</div>

<!-- 4 Kartu Ringkasan Cepat -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl font-bold">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Produk</div>
                <div class="text-2xl font-black text-slate-800">{{ $productCount }}</div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl font-bold">
                <i class="bi bi-cart-check"></i>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Transaksi</div>
                <div class="text-2xl font-black text-slate-800">{{ $orderCount }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-2xl font-bold">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Penjualan</div>
                <div class="text-xl font-black text-slate-800">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl font-bold">
                <i class="bi bi-tags"></i>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kategori Menu</div>
                <div class="text-2xl font-black text-slate-800">{{ $categoryCount }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Hak Akses & Panduan Navigasi -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="bi bi-shield-check text-emerald-600"></i>
            <span>Ringkasan Hak Akses Sistem</span>
        </h3>
        <ul class="space-y-4 text-sm text-slate-600">
            <li class="pb-3 border-b border-slate-100">
                <strong class="text-slate-800 block mb-0.5">Admin / Administrator:</strong>
                <p class="text-xs text-slate-500">Memiliki akses penuh ke seluruh master data (Produk, Categories, Users, Roles), serta monitoring transaksi dan laporan penjualan.</p>
            </li>
            <li class="pb-3 border-b border-slate-100">
                <strong class="text-slate-800 block mb-0.5">Kasir:</strong>
                <p class="text-xs text-slate-500">Akses khusus mesin kasir POS untuk pemprosesan pesanan dan pemantauan ketersediaan stok barang.</p>
            </li>
            <li>
                <strong class="text-slate-800 block mb-0.5">Pimpinan & Role Kustom (misal: Gudang):</strong>
                <p class="text-xs text-slate-500">Memiliki izin fitur yang dikonfigurasikan secara dinamis melalui menu Kelola Role & Hak Akses.</p>
            </li>
        </ul>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="bi bi-compass text-emerald-600"></i>
            <span>Pintas Fitur Utama</span>
        </h3>
        <div class="space-y-2.5">
            @if(Auth::user()->hasPermission('users'))
            <a href="{{ route('users.index') }}" class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 text-slate-700 hover:text-emerald-700 font-semibold text-sm transition-all duration-200 group">
                <i class="bi bi-people text-lg text-slate-400 group-hover:text-emerald-600"></i>
                <span>Kelola User & Role</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('products'))
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 text-slate-700 hover:text-emerald-700 font-semibold text-sm transition-all duration-200 group">
                <i class="bi bi-box-seam text-lg text-slate-400 group-hover:text-emerald-600"></i>
                <span>Kelola Master Produk</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('stock'))
            <a href="{{ route('stock.index') }}" class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 text-slate-700 hover:text-emerald-700 font-semibold text-sm transition-all duration-200 group">
                <i class="bi bi-boxes text-lg text-slate-400 group-hover:text-emerald-600"></i>
                <span>Buka Monitoring Stok Produk</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('pos'))
            <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 text-slate-700 hover:text-emerald-700 font-semibold text-sm transition-all duration-200 group">
                <i class="bi bi-cart3 text-lg text-slate-400 group-hover:text-emerald-600"></i>
                <span>Buka Mesin Kasir POS</span>
            </a>
            @endif

            @if(Auth::user()->hasPermission('reports'))
            <a href="{{ route('reports.index') }}" class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 text-slate-700 hover:text-emerald-700 font-semibold text-sm transition-all duration-200 group">
                <i class="bi bi-file-earmark-bar-graph text-lg text-slate-400 group-hover:text-emerald-600"></i>
                <span>Buka Laporan Penjualan & Omzet</span>
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
