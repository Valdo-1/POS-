@extends('app')

@section('header')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">LAPORAN PENJUALAN</h1>
        <p class="text-slate-500 text-sm">Rekapitulasi omzet dan data transaksi Resto KETARA PPKD Jakarta Pusat</p>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-semibold">
            <li><a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-emerald-600">Home</a></li>
            <li><span class="text-slate-300">/</span></li>
            <li class="text-emerald-700 font-bold" aria-current="page">Laporan Penjualan</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<!-- Filter Tab Periode & Form Control -->
<div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Tab Nav Buttons -->
        <div class="inline-flex p-1 bg-slate-100 rounded-xl space-x-1">
            <a href="{{ route('reports.index', ['period' => 'daily']) }}" 
               class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ $period === 'daily' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="bi bi-calendar-day"></i> Harian
            </a>
            <a href="{{ route('reports.index', ['period' => 'weekly']) }}" 
               class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ $period === 'weekly' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="bi bi-calendar-week"></i> Mingguan
            </a>
            <a href="{{ route('reports.index', ['period' => 'monthly']) }}" 
               class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ $period === 'monthly' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="bi bi-calendar-month"></i> Bulanan
            </a>
        </div>

        <!-- Form Filter Input Sesuai Tab Aktif -->
        <form action="{{ route('reports.index') }}" method="GET" class="flex items-center gap-2 flex-wrap">
            <input type="hidden" name="period" value="{{ $period }}">

            @if($period === 'daily')
                <label class="text-xs font-bold text-slate-500">Tanggal:</label>
                <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" 
                       class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-emerald-500">
            @elseif($period === 'weekly')
                <label class="text-xs font-bold text-slate-500">Dari:</label>
                <input type="date" name="start_date" value="{{ request('start_date', \Carbon\Carbon::now()->subDays(6)->format('Y-m-d')) }}" 
                       class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-emerald-500">
                <label class="text-xs font-bold text-slate-500">Sampai:</label>
                <input type="date" name="end_date" value="{{ request('end_date', \Carbon\Carbon::now()->format('Y-m-d')) }}" 
                       class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-emerald-500">
            @elseif($period === 'monthly')
                <label class="text-xs font-bold text-slate-500">Bulan:</label>
                <select name="month" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-emerald-500">
                    @for($m = 1; $m <= 12; $m++)
                        @php $val = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                        <option value="{{ $val }}" {{ request('month', date('m')) == $val ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
                <label class="text-xs font-bold text-slate-500">Tahun:</label>
                <select name="year" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-emerald-500">
                    @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            @endif

            <button type="submit" class="px-4 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors cursor-pointer">
                <i class="bi bi-filter"></i> Terapkan
            </button>
            <button type="button" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-colors cursor-pointer" onclick="window.print()">
                <i class="bi bi-printer"></i> Cetak
            </button>
        </form>
    </div>
</div>

<!-- Banner Header Info Laporan -->
<div class="bg-emerald-50 border border-emerald-200/80 rounded-2xl p-4 mb-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-emerald-900">
    <div class="flex items-center gap-2.5 font-bold text-sm">
        <i class="bi bi-info-circle-fill text-emerald-600 text-lg"></i>
        <span>{{ $filterTitle }}</span>
    </div>
    <span class="px-3 py-1 rounded-full bg-emerald-700 text-white text-xs font-extrabold shadow-xs">
        Total: {{ $totalOrders }} Transaksi
    </span>
</div>

<!-- 3 Kartu Ringkasan Omzet & Terjual -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl font-bold">
                <i class="bi bi-receipt"></i>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jumlah Transaksi</div>
                <div class="text-2xl font-black text-slate-800">{{ number_format($totalOrders, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center text-2xl font-bold">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Omzet / Pendapatan</div>
                <div class="text-2xl font-black text-emerald-700">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-2xl font-bold">
                <i class="bi bi-cup-straw"></i>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Item Terjual</div>
                <div class="text-2xl font-black text-sky-700">{{ number_format($totalItemsSold, 0, ',', '.') }} Pcs</div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Tabel Detail Transaksi (8 cols) -->
    <div class="lg:col-span-8">
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
            <h3 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2">
                <i class="bi bi-list-check text-emerald-600"></i>
                <span>Daftar Histori Transaksi</span>
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-3">No. Order</th>
                            <th class="py-3 px-3">Waktu</th>
                            <th class="py-3 px-3">Kasir</th>
                            <th class="py-3 px-3">Item & Rincian</th>
                            <th class="py-3 px-3 text-right">Total</th>
                            <th class="py-3 px-3 text-center">Struk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3 px-3">
                                <span class="bg-slate-100 text-slate-800 font-bold px-2 py-0.5 rounded border border-slate-200">
                                    {{ $order->order_code }}
                                </span>
                            </td>
                            <td class="py-3 px-3">{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') : $order->created_at?->format('d/m/Y') }}</td>
                            <td class="py-3 px-3 font-medium text-slate-800">{{ $order->user->name ?? 'Kasir' }}</td>
                            <td class="py-3 px-3">
                                <ul class="space-y-0.5 text-[11px] text-slate-500">
                                    @foreach($order->details as $det)
                                        <li>• {{ $det->product->product_name ?? 'Produk Dihapus' }} (x{{ $det->qty }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="py-3 px-3 text-right font-extrabold text-emerald-700">Rp {{ number_format($order->order_amount, 0, ',', '.') }}</td>
                            <td class="py-3 px-3 text-center">
                                <a href="{{ route('transactions.print', $order->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold border border-emerald-200 text-[10px]">
                                    <i class="bi bi-printer"></i> Print
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400">Tidak ada data transaksi pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Leaderboard 5 Menu Terlaris (4 cols) -->
    <div class="lg:col-span-4">
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs h-full">
            <h3 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2">
                <i class="bi bi-trophy text-amber-500"></i>
                <span>Produk Terlaris</span>
            </h3>

            @if(count($topProducts) > 0)
                <div class="space-y-3">
                    @foreach($topProducts as $idx => $item)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-full bg-emerald-600 text-white font-extrabold text-xs flex items-center justify-center shadow-xs">
                                {{ $idx + 1 }}
                            </span>
                            <div>
                                <h4 class="font-bold text-xs text-slate-800 line-clamp-1">{{ $item->product->product_name ?? 'Produk' }}</h4>
                                <span class="text-[11px] text-slate-400">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs">
                            {{ $item->total_qty }} pcs
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-slate-400">
                    <p class="text-xs">Belum ada data penjualan pada periode ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
