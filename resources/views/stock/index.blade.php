@extends('app')

@section('header')
<div class="page-header">
    <div>
        <h1 class="page-title">Monitoring Stok Produk</h1>
        <p class="page-subtitle">Informasi ketersediaan stok barang resto.</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted-green">Home</a></li>
            <li class="breadcrumb-item active text-main" aria-current="page">Stok Produk</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
{{-- 4 kartu ringkasan kondisi stok barang di resto --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 border-light shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-primary text-white p-3 rounded-3 me-3">
                    <i class="bi bi-box-seam fs-4"></i>
                </div>
                <div>
                    <span class="text-muted small">Total Produk</span>
                    <h4 class="mb-0 fw-bold">{{ $totalItems }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 border-light shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-success text-white p-3 rounded-3 me-3">
                    <i class="bi bi-stack fs-4"></i>
                </div>
                <div>
                    <span class="text-muted small">Total Stok</span>
                    <h4 class="mb-0 fw-bold">{{ number_format($totalStock, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 border-light shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-warning text-dark p-3 rounded-3 me-3">
                    <i class="bi bi-exclamation-triangle fs-4"></i>
                </div>
                <div>
                    <span class="text-muted small">Stok Terbatas</span>
                    <h4 class="mb-0 fw-bold text-warning">{{ $lowStock }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 border-light shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-danger text-white p-3 rounded-3 me-3">
                    <i class="bi bi-x-octagon fs-4"></i>
                </div>
                <div>
                    <span class="text-muted small">Stok Habis (0)</span>
                    <h4 class="mb-0 fw-bold text-danger">{{ $outOfStock }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Card Tabel Stok Produk -->
<div class="card p-4 border-light shadow-sm">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <h4 class="mb-0">Daftar Ketersediaan Stok</h4>
        
        {{-- filter dropdown kategori & status stok, langsung auto submit pas dipilih --}}
        <form action="{{ route('stock.index') }}" method="GET" class="d-flex gap-2 flex-wrap">
            <select name="category_id" class="form-select form-select-sm" style="width: 160px;" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Stok Aman (>10)</option>
                <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Stok Menipis (<=10)</option>
                <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Stok Habis (0)</option>
            </select>

            <div class="input-group input-group-sm" style="width: 200px;">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama...">
                <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
            </div>

            @if(request('category_id') || request('search') || request('status'))
                <a href="{{ route('stock.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 70px;">Foto</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga Satuan</th>
                    <th class="text-center">Sisa Stok</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        @if($product->product_photo && file_exists(public_path('storage/' . $product->product_photo)))
                            <img src="{{ asset('storage/'.$product->product_photo) }}" alt="{{ $product->product_name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="bg-light text-muted d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; border-radius: 6px;">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        @endif
                    </td>
                    <td class="fw-bold">{{ $product->product_name }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $product->category->category_name ?? '-' }}</span></td>
                    <td class="text-success fw-semibold">Rp {{ number_format($product->product_price, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="fs-6 fw-bold">{{ $product->stock }}</span>
                    </td>
                    <td class="text-center">
                        @if($product->stock > 10)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1">Tersedia</span>
                        @elseif($product->stock > 0)
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1">Menipis</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1">Habis</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Data produk tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
