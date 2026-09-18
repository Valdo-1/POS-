@extends('app')

@section('header')
<div class="mb-2 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-2">
            <h1 class="text-xl font-black text-amber-100 uppercase tracking-wider font-mono">MESIN KASIR (POS)</h1>
            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-amber-900/40 text-amber-300 font-semibold border border-amber-700/40">OPERATIONAL MODE</span>
        </div>
        <p class="text-xs text-stone-400 mt-1">Terminal Transaksi Penjualan Resto KETARA PPKD Jakarta Pusat</p>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-[11px] font-mono">
            <li><a href="{{ route('dashboard') }}" class="text-stone-500 hover:text-amber-300">Home</a></li>
            <li><span class="text-stone-600">/</span></li>
            <li class="text-amber-300 font-semibold" aria-current="page">POS Terminal</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Kolom Kiri: Menu & Katalog Produk (8 cols / 60%) -->
    <div class="lg:col-span-7 xl:col-span-8 space-y-6">
        <div class="glass-espresso rounded-3xl p-6 border border-amber-900/30 shadow-glass-warm">
            
            <!-- Filter Kategori & Column Search Bar -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-5 pb-4 border-b border-amber-900/30">
                <!-- Search Form -->
                <form action="{{ route('transactions.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-80">
                    <div class="relative flex-grow">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-stone-500 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full h-9 pl-9 pr-3 rounded-xl bg-stone-900/60 border border-amber-900/30 text-xs text-stone-100 placeholder-stone-500 focus:outline-none focus:border-amber-500/60 transition font-sans" 
                               placeholder="Cari menu, SKU, atau barang...">
                    </div>
                    <button type="submit" 
                            onclick="playWarmChime('tap')"
                            class="h-9 px-3.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-stone-950 font-bold text-xs transition">
                        Cari
                    </button>
                    @if(request('search') || request('category_id'))
                        <a href="{{ route('transactions.index') }}" 
                           onclick="playWarmChime('tap')"
                           class="h-9 px-3 rounded-xl bg-stone-900 hover:bg-stone-800 text-stone-400 hover:text-amber-200 border border-amber-900/30 flex items-center justify-center transition">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </form>

                <!-- Category Pills -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 max-w-full">
                    <a href="{{ route('transactions.index') }}" 
                       onclick="playWarmChime('tap')"
                       class="px-3.5 py-1.5 rounded-xl text-xs font-medium transition {{ !request('category_id') ? 'bg-amber-900/40 border border-amber-600/40 text-amber-200 font-bold' : 'bg-stone-900/40 border border-amber-900/20 text-stone-400 hover:text-amber-200 hover:border-amber-700/30' }}">
                        Semua
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('transactions.index', ['category_id' => $cat->id, 'search' => request('search')]) }}" 
                           onclick="playWarmChime('tap')"
                           class="px-3.5 py-1.5 rounded-xl text-xs font-medium whitespace-nowrap transition {{ request('category_id') == $cat->id ? 'bg-amber-900/40 border border-amber-600/40 text-amber-200 font-bold' : 'bg-stone-900/40 border border-amber-900/20 text-stone-400 hover:text-amber-200 hover:border-amber-700/30' }}">
                            {{ $cat->category_name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Grid Katalog Produk Kasir -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-[560px] overflow-y-auto pr-1">
                @forelse($products as $product)
                <div class="card-espresso rounded-2xl p-4 flex flex-col justify-between group relative select-none cursor-pointer transition {{ $product->stock <= 0 ? 'opacity-50' : '' }}"
                     @if($product->stock > 0)
                     onclick="addToCartFromCard(this)"
                     data-id="{{ $product->id }}" 
                     data-name="{{ $product->product_name }}" 
                     data-price="{{ $product->product_price }}" 
                     data-stock="{{ $product->stock }}" 
                     @endif>
                    
                    <!-- Photo Header -->
                    <div class="relative w-full h-32 rounded-xl overflow-hidden mb-3 bg-stone-950 border border-amber-900/20 flex items-center justify-center">
                        @if($product->product_photo && file_exists(public_path('storage/' . $product->product_photo)))
                            <img src="{{ asset('storage/' . $product->product_photo) }}" alt="{{ $product->product_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 group-hover:opacity-100">
                        @else
                            <i data-lucide="coffee" class="w-8 h-8 text-amber-400/40 stroke-[1.2]"></i>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0e0a07] via-transparent to-transparent"></div>
                        <span class="absolute top-2.5 left-2.5 text-[9px] font-mono uppercase font-bold px-2 py-0.5 rounded-full border text-amber-300 bg-amber-950/70 border-amber-700/40 backdrop-blur-md">
                            {{ $product->category->category_name ?? 'Umum' }}
                        </span>
                    </div>
                    
                    <!-- Title & Details -->
                    <div>
                        <h4 class="text-xs font-bold text-amber-100 group-hover:text-amber-300 transition line-clamp-1 mb-1" title="{{ $product->product_name }}">{{ $product->product_name }}</h4>
                        <div class="text-[11px] font-mono text-stone-400 flex items-center justify-between">
                            <span>Stok:</span>
                            <span class="font-bold tabular-nums {{ $product->stock > 10 ? 'text-stone-300' : ($product->stock > 0 ? 'text-amber-400' : 'text-rose-400') }}">
                                {{ $product->stock }} pcs
                            </span>
                        </div>
                    </div>

                    <!-- Price & Add Action -->
                    <div class="mt-3 pt-2.5 border-t border-amber-900/20 flex items-center justify-between">
                        <span class="text-xs font-bold font-mono text-amber-200 tabular-nums">Rp {{ number_format($product->product_price, 0, ',', '.') }}</span>
                        @if($product->stock > 0)
                            <button type="button" 
                                    class="w-7 h-7 rounded-lg bg-amber-900/40 group-hover:bg-amber-600 group-hover:text-stone-950 text-amber-300 flex items-center justify-center text-xs transition border border-amber-700/30"
                                    data-id="{{ $product->id }}" 
                                    data-name="{{ $product->product_name }}" 
                                    data-price="{{ $product->product_price }}" 
                                    data-stock="{{ $product->stock }}" 
                                    onclick="event.stopPropagation(); addToCartFromBtn(this)">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </button>
                        @else
                            <span class="text-[10px] font-mono text-rose-400 bg-rose-950/50 px-2 py-0.5 rounded border border-rose-800/40">HABIS</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12 text-stone-500 space-y-2">
                    <i data-lucide="package-open" class="w-10 h-10 stroke-[1.2] mx-auto text-amber-400/40"></i>
                    <p class="text-xs font-mono uppercase tracking-wider">Tidak ada produk yang ditemukan.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Riwayat Transaksi Kasir Hari Ini -->
        @if(count($todayOrders) > 0)
        <div class="glass-espresso rounded-3xl p-6 border border-amber-900/30 shadow-glass-warm">
            <h3 class="text-xs font-bold font-mono uppercase tracking-widest text-amber-300 mb-3 flex items-center gap-2">
                <i data-lucide="history" class="w-4 h-4 text-amber-400"></i>
                <span>5 Transaksi Terakhir Hari Ini</span>
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-stone-900/60 text-stone-400 uppercase font-mono text-[10px] border-b border-amber-900/30">
                        <tr>
                            <th class="py-2.5 px-3">No. Order</th>
                            <th class="py-2.5 px-3">Waktu</th>
                            <th class="py-2.5 px-3">Total</th>
                            <th class="py-2.5 px-3">Kembalian</th>  
                            <th class="py-2.5 px-3 text-center">Struk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-900/20 font-mono text-[11px]">
                        @foreach($todayOrders as $order)
                        <tr class="hover:bg-stone-900/40 transition">
                            <td class="py-2 px-3 font-semibold text-amber-200">{{ $order->order_code }}</td>
                            <td class="py-2 px-3 text-stone-400">{{ $order->created_at?->format('H:i') }} WIB</td>
                            <td class="py-2 px-3 font-bold text-amber-100 tabular-nums">Rp {{ number_format($order->order_amount, 0, ',', '.') }}</td>
                            <td class="py-2 px-3 text-stone-300 tabular-nums">Rp {{ number_format($order->order_change, 0, ',', '.') }}</td>
                            <td class="py-2 px-3 text-center">
                                <a href="{{ route('transactions.print', $order->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-stone-900 hover:bg-stone-800 text-amber-300 border border-amber-900/40 text-[10px] transition">
                                    <i data-lucide="printer" class="w-3 h-3"></i> Cetak
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Kolom Kanan: Sleek Warm Bronze Ledger (4 cols / 40%) -->
    <div class="lg:col-span-5 xl:col-span-4">
        <aside class="glass-espresso rounded-3xl p-6 shadow-glass-warm border border-amber-900/30 relative overflow-hidden sticky top-20">
            <!-- Glow ambient accent in ledger corner -->
            <div class="absolute -right-20 -top-20 w-48 h-48 bg-amber-700/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Ledger Header -->
            <div class="flex items-center justify-between pb-4 border-b border-amber-900/30 shrink-0">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-amber-200">Current Order</h3>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-amber-900/40 text-amber-300 font-semibold border border-amber-700/40">POS TAB</span>
                    </div>
                    <p class="text-[11px] text-stone-400 mt-0.5">Kasir: {{ Auth::user()->name }}</p>
                </div>
                <button type="button" onclick="clearCart()" class="text-[11px] font-mono text-stone-500 hover:text-amber-300 transition px-2 py-1 rounded-lg hover:bg-stone-900/50">
                    Reset Tab
                </button>
            </div>

            <form action="{{ route('transactions.store') }}" method="POST" id="checkoutForm" onsubmit="return validateCheckout()">
                @csrf
                
                <!-- Items list -->
                <div class="py-3 max-h-[280px] overflow-y-auto space-y-2.5" id="cartContainer">
                    <div id="emptyCartMessage" class="h-44 flex flex-col items-center justify-center text-center p-4 text-stone-500">
                        <div class="w-12 h-12 rounded-2xl bg-amber-950/30 border border-amber-900/30 flex items-center justify-center text-amber-400/60 mb-2">
                            <i data-lucide="shopping-bag" class="w-6 h-6 stroke-[1.2]"></i>
                        </div>
                        <p class="text-xs font-bold text-amber-200/80 uppercase tracking-wider">Keranjang Kosong</p>
                        <p class="text-[11px] text-stone-500 mt-1">Pilih menu dari katalog sebelah kiri</p>
                    </div>
                    <div class="space-y-2" id="cartList"></div>
                </div>

                <div id="hiddenInputsContainer"></div>

                <!-- Footer Summary & Checkout Controls -->
                <div class="pt-4 border-t border-amber-900/30 shrink-0 space-y-2.5">
                    <div class="flex justify-between text-xs text-stone-400 font-medium">
                        <span>Subtotal:</span>
                        <span id="subtotalText" class="font-mono tabular-nums text-stone-200">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-xs text-stone-400 font-medium">
                        <span class="flex items-center gap-1.5">
                            Pajak PPN
                            <span class="text-[9px] font-mono px-1 py-0.2 rounded bg-amber-900/30 text-amber-400 border border-amber-800/40">11%</span>
                        </span>
                        <span id="taxText" class="font-mono tabular-nums text-stone-200">Rp 0</span>
                    </div>

                    <!-- Grand Total Display -->
                    <div class="pt-3 border-t border-amber-900/40 flex items-baseline justify-between">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-amber-400/80 block">Total Tagihan</span>
                            <span class="text-[10px] text-stone-500 font-mono">TERMASUK PPN 11%</span>
                        </div>
                        <div id="totalText" class="text-2xl font-extrabold font-mono tabular-nums tracking-tight text-amber-100">
                            Rp 0
                        </div>
                    </div>
                    <input type="hidden" id="totalPriceInput" value="0">

                    <!-- Cash Input Section -->
                    <div class="mt-3">
                        <label for="paidAmountInput" class="text-[10px] font-mono uppercase tracking-wider text-amber-300/80 block mb-1">Nominal Pembayaran Tunai (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-mono text-xs text-stone-500 font-bold">Rp</span>
                            <input type="number" name="paid_amount" id="paidAmountInput" 
                                   class="w-full h-10 pl-10 pr-4 rounded-xl bg-stone-950 border border-amber-900/40 text-right font-mono text-sm tabular-nums text-amber-100 focus:outline-none focus:border-amber-500" 
                                   placeholder="0" min="0" oninput="calculateChange()" required>
                        </div>
                    </div>

                    <!-- Shortcut Quick Amounts -->
                    <div class="grid grid-cols-5 gap-1.5" id="quickAmounts">
                        <button type="button" class="h-7 rounded-lg bg-stone-900/80 hover:bg-stone-800 border border-amber-900/30 text-[10px] font-mono text-amber-200 transition" onclick="setExactAmount()">Pas</button>
                        <button type="button" class="h-7 rounded-lg bg-stone-900/80 hover:bg-stone-800 border border-amber-900/30 text-[10px] font-mono text-amber-200 transition" onclick="addAmount(10000)">10K</button>
                        <button type="button" class="h-7 rounded-lg bg-stone-900/80 hover:bg-stone-800 border border-amber-900/30 text-[10px] font-mono text-amber-200 transition" onclick="addAmount(20000)">20K</button>
                        <button type="button" class="h-7 rounded-lg bg-stone-900/80 hover:bg-stone-800 border border-amber-900/30 text-[10px] font-mono text-amber-200 transition" onclick="addAmount(50000)">50K</button>
                        <button type="button" class="h-7 rounded-lg bg-stone-900/80 hover:bg-stone-800 border border-amber-900/30 text-[10px] font-mono text-amber-200 transition" onclick="addAmount(100000)">100K</button>
                    </div>

                    <!-- Change Display -->
                    <div class="p-3 rounded-xl bg-stone-950/80 border border-amber-900/30 flex items-center justify-between">
                        <span class="text-xs text-stone-400">Kembalian:</span>
                        <span id="changeText" class="font-mono text-sm font-bold tabular-nums text-amber-300">Rp 0</span>
                    </div>

                    <!-- Pay Now Submit Button -->
                    <button 
                      type="submit"
                      id="submitBtn"
                      disabled
                      onclick="triggerSuccessEffect()"
                      class="w-full mt-2 h-12 rounded-2xl bg-gradient-to-r from-amber-700 via-amber-600 to-amber-700 hover:shadow-glow-bronze text-amber-50 font-bold text-xs uppercase tracking-widest transition-all duration-300 disabled:opacity-30 disabled:cursor-not-allowed border border-amber-500/40 flex items-center justify-center gap-2 active:scale-[0.98] shadow-lg cursor-pointer"
                    >
                        <i data-lucide="sparkles" class="w-4 h-4 text-amber-200"></i>
                        <span>Selesaikan Transaksi</span>
                    </button>

                    @if(session('last_order_id'))
                    <a href="{{ route('transactions.print', session('last_order_id')) }}" target="_blank" class="w-full py-2 rounded-xl bg-stone-900 hover:bg-stone-800 text-[11px] font-mono text-amber-200 border border-amber-900/30 flex items-center justify-center gap-2 transition">
                        <i data-lucide="printer" class="w-3.5 h-3.5"></i> Cetak Struk Terakhir
                    </a>
                    @endif
                </div>
            </form>
        </aside>
    </div>
</div>

<script>
    let cart = [];

    function formatRupiah(number) {
        return 'Rp ' + Number(number).toLocaleString('id-ID');
    }

    function addToCartFromCard(card) {
        playWarmChime('tap');
        addToCart(
            Number(card.dataset.id),
            card.dataset.name,
            Number(card.dataset.price),
            Number(card.dataset.stock)
        );
    }

    function addToCartFromBtn(btn) {
        playWarmChime('tap');
        addToCart(
            Number(btn.dataset.id),
            btn.dataset.name,
            Number(btn.dataset.price),
            Number(btn.dataset.stock)
        );
    }

    function addToCart(id, name, price, maxStock) {
        const existing = cart.find(item => item.id === id);
        if (existing) {
            if (existing.qty < maxStock) {
                existing.qty += 1;
            } else {
                alert('Stok produk ' + name + ' tidak mencukupi! Maksimum: ' + maxStock);
                return;
            }
        } else {
            cart.push({
                id: id,
                name: name,
                price: price,
                qty: 1,
                maxStock: maxStock
            });
        }
        renderCart();
    }

    function updateQty(id, delta) {
        playWarmChime('tap');
        const item = cart.find(i => i.id === id);
        if (!item) return;

        const newQty = item.qty + delta;
        if (newQty <= 0) {
            cart = cart.filter(i => i.id !== id);
        } else if (newQty > item.maxStock) {
            alert('Stok tidak mencukupi! Maksimum: ' + item.maxStock);
            return;
        } else {
            item.qty = newQty;
        }
        renderCart();
    }

    function removeFromCart(id) {
        playWarmChime('tap');
        cart = cart.filter(i => i.id !== id);
        renderCart();
    }

    function clearCart() {
        playWarmChime('tap');
        if (cart.length > 0 && confirm('Kosongkan semua item di keranjang?')) {
            cart = [];
            renderCart();
        }
    }

    function renderCart() {
        const cartList = document.getElementById('cartList');
        const emptyMsg = document.getElementById('emptyCartMessage');
        const hiddenContainer = document.getElementById('hiddenInputsContainer');
        const submitBtn = document.getElementById('submitBtn');

        cartList.innerHTML = '';
        hiddenContainer.innerHTML = '';

        if (cart.length === 0) {
            emptyMsg.style.display = 'flex';
            submitBtn.disabled = true;
            document.getElementById('subtotalText').innerText = formatRupiah(0);
            document.getElementById('taxText').innerText = formatRupiah(0);
            document.getElementById('totalText').innerText = formatRupiah(0);
            document.getElementById('totalPriceInput').value = 0;
            calculateChange();
            if (typeof lucide !== 'undefined') lucide.createIcons();
            return;
        }

        emptyMsg.style.display = 'none';

        let cartSubtotal = 0;

        cart.forEach((item, index) => {
            const itemSubtotal = item.price * item.qty;
            cartSubtotal += itemSubtotal;

            const div = document.createElement('div');
            div.className = 'p-3 rounded-2xl bg-stone-900/50 border border-amber-900/20 hover:border-amber-700/30 transition-all flex items-center justify-between gap-3 group';
            div.innerHTML = `
                <div class="flex-1 min-w-0">
                    <h5 class="text-xs font-semibold text-stone-100 truncate">${item.name}</h5>
                    <div class="text-[10px] font-mono text-amber-400/80 tabular-nums">@ ${formatRupiah(item.price)}</div>
                </div>

                <div class="flex items-center gap-1.5 bg-black/60 px-2 py-1 rounded-xl border border-amber-900/40">
                    <button type="button" onclick="updateQty(${item.id}, -1)" class="w-4 h-4 flex items-center justify-center text-stone-400 hover:text-amber-200 transition">
                        <i data-lucide="minus" class="w-3 h-3"></i>
                    </button>
                    <span class="w-5 text-center text-xs font-mono font-bold text-amber-100 tabular-nums">${item.qty}</span>
                    <button type="button" onclick="updateQty(${item.id}, 1)" class="w-4 h-4 flex items-center justify-center text-stone-400 hover:text-amber-200 transition">
                        <i data-lucide="plus" class="w-3 h-3"></i>
                    </button>
                </div>

                <span class="w-20 text-right font-mono text-xs font-bold text-amber-200 tabular-nums">
                    ${formatRupiah(itemSubtotal)}
                </span>
            `;
            cartList.appendChild(div);

            hiddenContainer.innerHTML += `
                <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
            `;
        });

        const taxRate = 0.11;
        const taxAmount = Math.round(cartSubtotal * taxRate);
        const grandTotal = cartSubtotal + taxAmount;

        document.getElementById('subtotalText').innerText = formatRupiah(cartSubtotal);
        document.getElementById('taxText').innerText = formatRupiah(taxAmount);
        document.getElementById('totalText').innerText = formatRupiah(grandTotal);
        document.getElementById('totalPriceInput').value = grandTotal;
        calculateChange();

        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function calculateChange() {
        const total = parseInt(document.getElementById('totalPriceInput').value) || 0;
        const paid = parseInt(document.getElementById('paidAmountInput').value) || 0;
        const change = paid - total;

        const changeText = document.getElementById('changeText');
        const submitBtn = document.getElementById('submitBtn');

        if (paid >= total && total > 0 && cart.length > 0) {
            changeText.innerText = formatRupiah(change);
            changeText.className = 'font-mono text-sm font-bold tabular-nums text-amber-300';
            submitBtn.disabled = false;
        } else {
            changeText.innerText = total > 0 && paid > 0 ? 'Kurang ' + formatRupiah(Math.abs(change)) : 'Rp 0';
            changeText.className = 'font-mono text-sm font-bold tabular-nums text-rose-400';
            submitBtn.disabled = true;
        }
    }

    function setExactAmount() {
        playWarmChime('tap');
        const total = parseInt(document.getElementById('totalPriceInput').value) || 0;
        document.getElementById('paidAmountInput').value = total;
        calculateChange();
    }

    function addAmount(nominal) {
        playWarmChime('tap');
        const current = parseInt(document.getElementById('paidAmountInput').value) || 0;
        document.getElementById('paidAmountInput').value = current + nominal;
        calculateChange();
    }

    function triggerSuccessEffect() {
        playWarmChime('success');
        if (typeof confetti !== 'undefined') {
            confetti({
                particleCount: 60,
                spread: 70,
                origin: { y: 0.65 },
                colors: ['#f59e0b', '#d97706', '#92400e', '#ffffff', '#fbbf24']
            });
        }
    }

    function validateCheckout() {
        if (cart.length === 0) {
            alert('Keranjang belanja masih kosong!');
            return false;
        }
        const total = parseInt(document.getElementById('totalPriceInput').value) || 0;
        const paid = parseInt(document.getElementById('paidAmountInput').value) || 0;
        if (paid < total) {
            alert('Nominal pembayaran masih kurang!');
            return false;
        }
        return true;
    }
</script>
@endsection

