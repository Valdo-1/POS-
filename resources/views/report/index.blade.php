@extends('app')

@section('header')
<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Penjualan</h1>
        <p class="page-subtitle">Rekapitulasi penjualan Resto PPKD Jakarta Pusat</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted-green">Home</a></li>
            <li class="breadcrumb-item active text-main" aria-current="page">Laporan Penjualan</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
{{-- filter tab periode: bisa pilih harian, mingguan, atau bulanan --}}
<div class="card p-3 border-light shadow-sm mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <!-- Tab Periode -->
        <ul class="nav nav-pills gap-1">
            <li class="nav-item">
                <a href="{{ route('reports.index', ['period' => 'daily']) }}" class="nav-link {{ $period === 'daily' ? 'active' : '' }}">
                    <i class="bi bi-calendar-day me-1"></i> Harian
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('reports.index', ['period' => 'weekly']) }}" class="nav-link {{ $period === 'weekly' ? 'active' : '' }}">
                    <i class="bi bi-calendar-week me-1"></i> Mingguan
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('reports.index', ['period' => 'monthly']) }}" class="nav-link {{ $period === 'monthly' ? 'active' : '' }}">
                    <i class="bi bi-calendar-month me-1"></i> Bulanan
                </a>
            </li>
        </ul>

        <!-- Form Filter Input Sesuai Tab Aktif -->
        <form action="{{ route('reports.index') }}" method="GET" class="d-flex gap-2 align-items-center flex-wrap">
            <input type="hidden" name="period" value="{{ $period }}">

            @if($period === 'daily')
                <label class="small text-muted mb-0">Tanggal:</label>
                <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" class="form-control form-control-sm" style="width: 150px;">
            @elseif($period === 'weekly')
                <label class="small text-muted mb-0">Dari:</label>
                <input type="date" name="start_date" value="{{ request('start_date', \Carbon\Carbon::now()->subDays(6)->format('Y-m-d')) }}" class="form-control form-control-sm" style="width: 140px;">
                <label class="small text-muted mb-0">Sampai:</label>
                <input type="date" name="end_date" value="{{ request('end_date', \Carbon\Carbon::now()->format('Y-m-d')) }}" class="form-control form-control-sm" style="width: 140px;">
            @elseif($period === 'monthly')
                <label class="small text-muted mb-0">Bulan:</label>
                <select name="month" class="form-select form-select-sm" style="width: 130px;">
                    @for($m = 1; $m <= 12; $m++)
                        @php $val = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                        <option value="{{ $val }}" {{ request('month', date('m')) == $val ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
                <label class="small text-muted mb-0">Tahun:</label>
                <select name="year" class="form-select form-select-sm" style="width: 100px;">
                    @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            @endif

            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-filter"></i> Terapkan</button>
            {{-- langsung buka print dialog browser tanpa ribet install lib pdf --}}
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer"></i> Cetak</button>
        </form>
    </div>
</div>

<!-- Header Info Laporan -->
<div class="alert alert-light border d-flex justify-content-between align-items-center mb-4 py-2">
    <div>
        <i class="bi bi-info-circle text-primary me-2"></i>
        <strong class="text-dark">{{ $filterTitle }}</strong>
    </div>
    <span class="badge bg-secondary">Total: {{ $totalOrders }} Transaksi</span>
</div>

{{-- 3 kartu statistik ringkas omzet & barang laku --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card p-3 border-light shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-primary text-white p-3 rounded-3 me-3">
                    <i class="bi bi-receipt fs-4"></i>
                </div>
                <div>
                    <span class="text-muted small">Jumlah Transaksi</span>
                    <h4 class="mb-0 fw-bold">{{ number_format($totalOrders, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 border-light shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-success text-white p-3 rounded-3 me-3">
                    <i class="bi bi-currency-dollar fs-4"></i>
                </div>
                <div>
                    <span class="text-muted small">Total Omzet / Pendapatan</span>
                    <h4 class="mb-0 fw-bold text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 border-light shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-info text-white p-3 rounded-3 me-3">
                    <i class="bi bi-cup-straw fs-4"></i>
                </div>
                <div>
                    <span class="text-muted small">Item Terjual</span>
                    <h4 class="mb-0 fw-bold text-info">{{ number_format($totalItemsSold, 0, ',', '.') }} Pcs</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- tabel detail transaksi beserta nama kasir dan rincian belanja --}}
    <div class="col-lg-8">
        <div class="card p-4 border-light shadow-sm h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-list-check me-2"></i>Daftar Transaksi</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Order</th>
                            <th>Waktu</th>
                            <th>Kasir</th>
                            <th>Item & Rincian</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Cetak Struk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border fw-bold">{{ $order->order_code }}</span>
                            </td>
                            <td class="small">{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') : $order->created_at?->format('d/m/Y') }}</td>
                            <td>{{ $order->user->name ?? 'Kasir' }}</td>
                            <td>
                                <ul class="list-unstyled mb-0 small text-muted">
                                    @foreach($order->details as $det)
                                        <li>• {{ $det->product->product_name ?? 'Produk Dihapus' }} (x{{ $det->qty }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="text-end fw-bold text-success">Rp {{ number_format($order->order_amount, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('transactions.print', $order->id) }}" target="_blank" class="btn btn-xs btn-outline-success fw-bold" title="Cetak Struk Transaksi Ini">
                                    <i class="bi bi-printer me-1"></i> Print Struk
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Tidak ada data transaksi pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- leaderboard 5 menu paling laris --}}
    <div class="col-lg-4">
        <div class="card p-4 border-light shadow-sm h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-trophy text-warning me-2"></i>Produk Terlaris</h5>
            @if(count($topProducts) > 0)
                <ul class="list-group list-group-flush">
                    @foreach($topProducts as $idx => $item)
                    <li class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-light text-dark rounded-circle me-2" style="width: 24px; height: 24px; line-height: 16px;">{{ $idx + 1 }}</span>
                            <div>
                                <h6 class="mb-0 small fw-bold">{{ $item->product->product_name ?? 'Produk' }}</h6>
                                <span class="text-muted small">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $item->total_qty }} pcs</span>
                    </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-4 text-muted">
                    <p class="small mb-0">Belum ada data penjualan pada periode ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
