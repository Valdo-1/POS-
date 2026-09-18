@extends('app')

@section('header')
<div class="page-header">
    <div>
        <h1 class="page-title">DASHBOARD</h1>
        <p class="page-subtitle">Selamat datang di WEB_ALDO_POS PPKD Jakarta Pusat.</p>
    </div>
</div>
@endsection

@section('content')
{{-- banner sapaan dan shortcut tombol sesuai wewenang role user --}}
<div class="card p-4 border-light shadow-sm mb-4 bg-light">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h4 class="fw-bold mb-1">Halo {{ Auth::user()->name }}</h4>
            <p class="text-muted mb-0">Anda login sebagai: <span class="badge bg-success">{{ Auth::user()->role->name ?? 'User' }}</span></p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            @if(Auth::user()->isKasir() || Auth::user()->isAdmin())
                <a href="{{ route('transactions.index') }}" class="btn btn-primary"><i class="bi bi-cart-check me-1"></i> Buka Kasir POS</a>
            @endif
            @if(Auth::user()->isPimpinan() || Auth::user()->isAdmin())
                <a href="{{ route('reports.index') }}" class="btn btn-outline-primary"><i class="bi bi-graph-up me-1"></i> Lihat Laporan</a>
            @endif
            <a href="{{ route('stock.index') }}" class="btn btn-outline-secondary"><i class="bi bi-boxes me-1"></i> Cek Stok</a>
        </div>
    </div>
</div>

{{-- 4 kartu ringkasan cepat: produk, transaksi, omzet, dan kategori --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 border-light shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-primary text-white p-3 rounded-3 me-3">
                    <i class="bi bi-box-seam fs-4"></i>
                </div>
                <div>
                    <span class="text-muted small">Total Produk</span>
                    <h4 class="mb-0 fw-bold">{{ $productCount }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 border-light shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-success text-white p-3 rounded-3 me-3">
                    <i class="bi bi-cart-check fs-4"></i>
                </div>
                <div>
                    <span class="text-muted small">Total Transaksi</span>
                    <h4 class="mb-0 fw-bold">{{ $orderCount }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 border-light shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-info text-white p-3 rounded-3 me-3">
                    <i class="bi bi-cash-stack fs-4"></i>
                </div>
                <div>
                    <span class="text-muted small">Total Penjualan</span>
                    <h4 class="mb-0 fw-bold">Rp {{ number_format($totalSales, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 border-light shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-warning text-dark p-3 rounded-3 me-3">
                    <i class="bi bi-tags fs-4"></i>
                </div>
                <div>
                    <span class="text-muted small">Kategori</span>
                    <h4 class="mb-0 fw-bold">{{ $categoryCount }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Hak Akses & Fitur -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="card p-4 border-light shadow-sm h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-shield-check text-primary me-2"></i>Hak Akses Sesuai Rolenya </h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item px-0">
                    <strong>Admin:</strong>
                    <p class="text-muted small mb-0">Membuat dan mengelola master data Produk, User, dan Kategori Produk, serta memonitor transaksi & laporan.</p>
                </li>
                <li class="list-group-item px-0">
                    <strong>Kasir:</strong>
                    <p class="text-muted small mb-0">Melakukan transaksi penjualan kasir (Point of Sales) dan memonitor ketersediaan stok barang.</p>
                </li>
                <li class="list-group-item px-0">
                    <strong>Pimpinan:</strong>
                    <p class="text-muted small mb-0">Melihat ketersediaan stok barang dan melihat laporan penjualan berkala (Harian, Mingguan, Bulanan).</p>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-4 border-light shadow-sm h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-activity text-success me-2"></i>Panduan Navigasinya</h5>
            <div class="d-grid gap-2">
                @if(Auth::user()->isAdmin())
                <a href="{{ route('users.index') }}" class="btn btn-outline-dark text-start p-2">
                    <i class="bi bi-people me-2"></i> Kelola User & Role
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-dark text-start p-2">
                    <i class="bi bi-box-seam me-2"></i> Kelola Master Produk
                </a>
                @endif
                <a href="{{ route('stock.index') }}" class="btn btn-outline-dark text-start p-2">
                    <i class="bi bi-boxes me-2"></i> Buka Monitoring Stok
                </a>
                @if(Auth::user()->isKasir() || Auth::user()->isAdmin())
                <a href="{{ route('transactions.index') }}" class="btn btn-outline-dark text-start p-2">
                    <i class="bi bi-cart3 me-2"></i> Buka Mesin Kasir POS
                </a>
                @endif
                @if(Auth::user()->isPimpinan() || Auth::user()->isAdmin())
                <a href="{{ route('reports.index') }}" class="btn btn-outline-dark text-start p-2">
                    <i class="bi bi-file-earmark-bar-graph me-2"></i> Buka Laporan Penjualan
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
