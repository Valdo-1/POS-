@extends('app')

@section('header')
<div class="mb-2 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-2">
            <h1 class="text-xl font-black text-amber-100 uppercase tracking-wider font-mono">LAPORAN PENJUALAN & OMZET</h1>
            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-amber-900/40 text-amber-300 font-semibold border border-amber-700/40">REVENUE REPORT</span>
        </div>
        <p class="text-xs text-stone-400 mt-1">Rekapitulasi omzet dan data transaksi Resto KETARA PPKD Jakarta Pusat</p>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-[11px] font-mono">
            <li><a href="{{ route('dashboard') }}" class="text-stone-500 hover:text-amber-300">Home</a></li>
            <li><span class="text-stone-600">/</span></li>
            <li class="text-amber-300 font-semibold" aria-current="page">Laporan Penjualan</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<!-- Filter Tab Periode & Form Control -->
<div class="glass-espresso rounded-3xl p-5 border border-amber-900/30 shadow-glass-warm mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Tab Nav Buttons -->
        <div class="inline-flex p-1 bg-stone-900/80 rounded-2xl border border-amber-900/30 space-x-1">
            <a href="{{ route('reports.index', ['period' => 'daily']) }}" 
               onclick="playWarmChime('tap')"
               class="px-4 py-2 rounded-xl text-xs font-mono font-bold transition flex items-center gap-1.5 {{ $period === 'daily' ? 'bg-amber-900/40 border border-amber-600/40 text-amber-200 shadow-sm' : 'text-stone-400 hover:text-amber-200' }}">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i> Harian
            </a>
            <a href="{{ route('reports.index', ['period' => 'weekly']) }}" 
               onclick="playWarmChime('tap')"
               class="px-4 py-2 rounded-xl text-xs font-mono font-bold transition flex items-center gap-1.5 {{ $period === 'weekly' ? 'bg-amber-900/40 border border-amber-600/40 text-amber-200 shadow-sm' : 'text-stone-400 hover:text-amber-200' }}">
                <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i> Mingguan
            </a>
            <a href="{{ route('reports.index', ['period' => 'monthly']) }}" 
               onclick="playWarmChime('tap')"
               class="px-4 py-2 rounded-xl text-xs font-mono font-bold transition flex items-center gap-1.5 {{ $period === 'monthly' ? 'bg-amber-900/40 border border-amber-600/40 text-amber-200 shadow-sm' : 'text-stone-400 hover:text-amber-200' }}">
                <i data-lucide="calendar-range" class="w-3.5 h-3.5"></i> Bulanan
            </a>
        </div>

        <!-- Form Filter Input Sesuai Tab Aktif -->
        <form action="{{ route('reports.index') }}" method="GET" class="flex items-center gap-2 flex-wrap text-xs font-mono">
            <input type="hidden" name="period" value="{{ $period }}">

            @if($period === 'daily')
                <label class="text-stone-400">Tanggal:</label>
                <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" 
                       class="h-9 px-3 bg-stone-900 border border-amber-900/30 rounded-xl text-xs font-mono text-stone-200 focus:outline-none focus:border-amber-500/60">
            @elseif($period === 'weekly')
                <label class="text-stone-400">Dari:</label>
                <input type="date" name="start_date" value="{{ request('start_date', \Carbon\Carbon::now()->subDays(6)->format('Y-m-d')) }}" 
                       class="h-9 px-3 bg-stone-900 border border-amber-900/30 rounded-xl text-xs font-mono text-stone-200 focus:outline-none focus:border-amber-500/60">
                <label class="text-stone-400">Sampai:</label>
                <input type="date" name="end_date" value="{{ request('end_date', \Carbon\Carbon::now()->format('Y-m-d')) }}" 
                       class="h-9 px-3 bg-stone-900 border border-amber-900/30 rounded-xl text-xs font-mono text-stone-200 focus:outline-none focus:border-amber-500/60">
            @elseif($period === 'monthly')
                <label class="text-stone-400">Bulan:</label>
                <select name="month" class="h-9 px-3 bg-stone-900 border border-amber-900/30 rounded-xl text-xs font-mono text-stone-200 focus:outline-none focus:border-amber-500/60">
                    @for($m = 1; $m <= 12; $m++)
                        @php $val = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                        <option value="{{ $val }}" {{ request('month', date('m')) == $val ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
                <label class="text-stone-400">Tahun:</label>
                <select name="year" class="h-9 px-3 bg-stone-900 border border-amber-900/30 rounded-xl text-xs font-mono text-stone-200 focus:outline-none focus:border-amber-500/60">
                    @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            @endif

            <button type="submit" 
                    onclick="playWarmChime('tap')"
                    class="h-9 px-4 rounded-xl bg-amber-600 hover:bg-amber-500 text-stone-950 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="filter" class="w-3.5 h-3.5"></i> Terapkan
            </button>
            <button type="button" class="h-9 px-3.5 rounded-xl bg-stone-900 hover:bg-stone-800 text-amber-300 border border-amber-900/30 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer" onclick="window.print()">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i> Cetak
            </button>
        </form>
    </div>
</div>

<!-- Banner Header Info Laporan -->
<div class="p-4 rounded-2xl bg-amber-950/40 border border-amber-600/40 text-amber-200 text-xs flex flex-col sm:flex-row items-center justify-between gap-3 shadow-lg backdrop-blur-md mb-6">
    <div class="flex items-center gap-2.5 font-bold font-mono">
        <i data-lucide="info" class="w-4 h-4 text-amber-400 shrink-0"></i>
        <span>{{ $filterTitle }}</span>
    </div>
    <span class="px-3 py-1 rounded-full bg-amber-900/60 text-amber-300 font-mono text-[10px] font-bold border border-amber-700/40">
        TOTAL: {{ $totalOrders }} TRANSAKSI
    </span>
</div>

<!-- 3 Kartu Ringkasan Omzet & Terjual -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="card-espresso rounded-2xl p-5 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold text-amber-400/80 uppercase tracking-wider block">Jumlah Transaksi</span>
            <div class="text-2xl font-black font-mono text-amber-100 tabular-nums">{{ number_format($totalOrders, 0, ',', '.') }}</div>
            <span class="text-[10px] text-stone-500 font-mono">PESANAN DISLESAIKAN</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-950/60 border border-amber-800/40 text-amber-300 flex items-center justify-center shadow-inner">
            <i data-lucide="receipt" class="w-6 h-6"></i>
        </div>
    </div>

    <div class="card-espresso rounded-2xl p-5 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold text-amber-400/80 uppercase tracking-wider block">Total Omzet / Pendapatan</span>
            <div class="text-2xl font-black font-mono text-amber-300 tabular-nums">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <span class="text-[10px] text-stone-500 font-mono">TOTAL PENDAPATAN</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-950/60 border border-amber-800/40 text-amber-300 flex items-center justify-center shadow-inner">
            <i data-lucide="banknote" class="w-6 h-6"></i>
        </div>
    </div>

    <div class="card-espresso rounded-2xl p-5 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-[10px] font-mono font-bold text-amber-400/80 uppercase tracking-wider block">Total Item Terjual</span>
            <div class="text-2xl font-black font-mono text-amber-100 tabular-nums">{{ number_format($totalItemsSold, 0, ',', '.') }} Pcs</div>
            <span class="text-[10px] text-stone-500 font-mono">ITEM TERIKUT</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-950/60 border border-amber-800/40 text-amber-300 flex items-center justify-center shadow-inner">
            <i data-lucide="shopping-bag" class="w-6 h-6"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Tabel Detail Transaksi (8 cols) -->
    <div class="lg:col-span-8">
        <div class="glass-espresso rounded-3xl p-6 border border-amber-900/30 shadow-glass-warm">
            <h3 class="text-xs font-bold font-mono uppercase tracking-widest text-amber-300 mb-4 flex items-center gap-2 border-b border-amber-900/30 pb-3">
                <i data-lucide="history" class="w-4 h-4 text-amber-400"></i>
                <span>Daftar Histori Transaksi</span>
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-stone-900/60 text-stone-400 uppercase font-mono text-[10px] border-b border-amber-900/30">
                        <tr>
                            <th class="py-3 px-3">No. Order</th>
                            <th class="py-3 px-3">Waktu</th>
                            <th class="py-3 px-3">Kasir</th>
                            <th class="py-3 px-3">Item & Rincian</th>
                            <th class="py-3 px-3 text-right">Total</th>
                            <th class="py-3 px-3 text-center">Struk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-900/20 text-stone-200 font-sans">
                        @forelse($orders as $order)
                        <tr class="hover:bg-stone-900/40 transition">
                            <td class="py-3 px-3 font-mono">
                                <span class="bg-amber-950/60 text-amber-300 font-bold px-2 py-0.5 rounded border border-amber-700/40">
                                    {{ $order->order_code }}
                                </span>
                            </td>
                            <td class="py-3 px-3 font-mono text-[11px] text-stone-400">{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') : $order->created_at?->format('d/m/Y') }}</td>
                            <td class="py-3 px-3 font-semibold text-amber-100">{{ $order->user->name ?? 'Kasir' }}</td>
                            <td class="py-3 px-3">
                                <ul class="space-y-0.5 text-[11px] text-stone-400 font-mono">
                                    @foreach($order->details as $det)
                                        <li>• {{ $det->product->product_name ?? 'Produk Dihapus' }} (x{{ $det->qty }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="py-3 px-3 text-right font-extrabold font-mono text-amber-200 tabular-nums">Rp {{ number_format($order->order_amount, 0, ',', '.') }}</td>
                            <td class="py-3 px-3 text-center">
                                <a href="{{ route('transactions.print', $order->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-stone-900 hover:bg-stone-800 text-amber-300 border border-amber-900/40 text-[10px] font-mono transition">
                                    <i data-lucide="printer" class="w-3 h-3"></i> Struk
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-stone-500 font-mono">Tidak ada data transaksi pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Leaderboard 5 Menu Terlaris (4 cols) -->
    <div class="lg:col-span-4">
        <div class="glass-espresso rounded-3xl p-6 border border-amber-900/30 shadow-glass-warm h-full">
            <h3 class="text-xs font-bold font-mono uppercase tracking-widest text-amber-300 mb-4 flex items-center gap-2 border-b border-amber-900/30 pb-3">
                <i data-lucide="trophy" class="w-4 h-4 text-amber-400"></i>
                <span>Produk Terlaris</span>
            </h3>

            @if(count($topProducts) > 0)
                <div class="space-y-3">
                    @foreach($topProducts as $idx => $item)
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-stone-900/60 border border-amber-900/30">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-xl bg-amber-900/60 text-amber-300 font-extrabold text-xs flex items-center justify-center border border-amber-700/40 font-mono">
                                {{ $idx + 1 }}
                            </span>
                            <div>
                                <h4 class="font-bold text-xs text-amber-100 line-clamp-1">{{ $item->product->product_name ?? 'Produk' }}</h4>
                                <span class="text-[10px] font-mono text-stone-400">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-amber-950/60 text-amber-300 font-mono font-bold text-xs border border-amber-700/40">
                            {{ $item->total_qty }} pcs
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-stone-500">
                    <p class="text-xs font-mono">Belum ada data penjualan pada periode ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

