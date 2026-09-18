@extends('app')

@section('header')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">POINT OF SALES (POS)</h1>
        <p class="text-slate-500 text-sm">Mesin Kasir Transaksi Penjualan Resto PPKD Jakarta Pusat</p>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-semibold">
            <li><a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-emerald-600">Home</a></li>
            <li><span class="text-slate-300">/</span></li>
            <li class="text-emerald-700 font-bold" aria-current="page">POS Transaksi</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Kolom Kiri: Menu & Katalog Produk (8 cols) -->
    <div class="lg:col-span-7 xl:col-span-8 space-y-6">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            
            <!-- Filter Kategori & Column Search Bar -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-5 pb-4 border-b border-slate-100">
                <!-- Search Form -->
                <form action="{{ route('transactions.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-72">
                    <div class="relative flex-grow">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" 
                               placeholder="Cari menu produk...">
                        <i class="bi bi-search absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                    </div>
                    <button type="submit" class="px-3 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors">
                        Cari
                    </button>
                    @if(request('search') || request('category_id'))
                        <a href="{{ route('transactions.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition-colors">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </form>

                <!-- Category Pills -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 max-w-full">
                    <a href="{{ route('transactions.index') }}" 
                       class="px-3 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-colors {{ !request('category_id') ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Semua
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('transactions.index', ['category_id' => $cat->id, 'search' => request('search')]) }}" 
                           class="px-3 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-colors {{ request('category_id') == $cat->id ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            {{ $cat->category_name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Grid Katalog Produk Kasir -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-[560px] overflow-y-auto pr-1">
                @forelse($products as $product)
                <div class="bg-white border rounded-2xl p-3 flex flex-col justify-between hover:border-emerald-500 hover:shadow-md transition-all group relative {{ $product->stock <= 0 ? 'opacity-60 bg-slate-50' : '' }}">
                    <div class="w-full h-28 rounded-xl bg-slate-50 flex items-center justify-center overflow-hidden mb-3 border border-slate-100 group-hover:scale-105 transition-transform duration-200">
                        @if($product->product_photo && file_exists(public_path('storage/' . $product->product_photo)))
                            <img src="{{ asset('storage/' . $product->product_photo) }}" alt="{{ $product->product_name }}" class="max-h-24 max-w-full object-contain">
                        @else
                            <i class="bi bi-cup-hot text-emerald-600/40 text-4xl"></i>
                        @endif
                    </div>
                    
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2 py-0.5 rounded-md inline-block mb-1">
                            {{ $product->category->category_name ?? 'Umum' }}
                        </span>
                        <h4 class="font-bold text-sm text-slate-800 line-clamp-1 mb-1" title="{{ $product->product_name }}">{{ $product->product_name }}</h4>
                        <div class="text-emerald-700 font-extrabold text-sm mb-1">Rp {{ number_format($product->product_price, 0, ',', '.') }}</div>
                        <div class="text-xs mb-3 {{ $product->stock > 10 ? 'text-slate-500' : ($product->stock > 0 ? 'text-amber-600 font-semibold' : 'text-rose-600 font-bold') }}">
                            Stok: {{ $product->stock }}
                        </div>
                    </div>

                    @if($product->stock > 0)
                        <button type="button" 
                                class="w-full py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition-all cursor-pointer" 
                                data-id="{{ $product->id }}" 
                                data-name="{{ $product->product_name }}" 
                                data-price="{{ $product->product_price }}" 
                                data-stock="{{ $product->stock }}" 
                                onclick="addToCartFromBtn(this)">
                            <i class="bi bi-cart-plus"></i> Tambah
                        </button>
                    @else
                        <button type="button" class="w-full py-2 px-3 rounded-xl bg-slate-200 text-slate-400 font-bold text-xs cursor-not-allowed" disabled>Habis</button>
                    @endif
                </div>
                @empty
                <div class="col-span-full text-center py-12 text-slate-400">
                    <i class="bi bi-inbox text-4xl block mb-2"></i>
                    <p class="text-sm">Tidak ada produk yang ditemukan.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Riwayat Transaksi Kasir Hari Ini -->
        @if(count($todayOrders) > 0)
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <h3 class="font-bold text-sm text-slate-800 mb-3 flex items-center gap-2">
                <i class="bi bi-clock-history text-emerald-600"></i>
                <span>5 Transaksi Terakhir Kasir Hari Ini</span>
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="py-2.5 px-3">No. Order</th>
                            <th class="py-2.5 px-3">Waktu</th>
                            <th class="py-2.5 px-3">Total</th>
                            <th class="py-2.5 px-3">Kembalian</th>  
                            <th class="py-2.5 px-3 text-center">Cetak Struk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($todayOrders as $order)
                        <tr class="hover:bg-slate-50">
                            <td class="py-2 px-3 font-semibold text-slate-800">{{ $order->order_code }}</td>
                            <td class="py-2 px-3">{{ $order->created_at?->format('H:i') }} WIB</td>
                            <td class="py-2 px-3 font-bold text-emerald-700">Rp {{ number_format($order->order_amount, 0, ',', '.') }}</td>
                            <td class="py-2 px-3">Rp {{ number_format($order->order_change, 0, ',', '.') }}</td>
                            <td class="py-2 px-3 text-center">
                                <a href="{{ route('transactions.print', $order->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold border border-emerald-200 text-[11px] transition-colors">
                                    <i class="bi bi-printer"></i> Struk
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

    <!-- Kolom Kanan: Keranjang Belanja & Checkout (5 cols) -->
    <div class="lg:col-span-5 xl:col-span-4">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-md sticky top-20 overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-base text-slate-800 flex items-center gap-2">
                    <i class="bi bi-cart3 text-emerald-600"></i>
                    <span>Keranjang Belanja</span>
                </h3>
                <button type="button" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors cursor-pointer" onclick="clearCart()" title="Kosongkan Keranjang">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

            <form action="{{ route('transactions.store') }}" method="POST" id="checkoutForm" onsubmit="return validateCheckout()">
                @csrf
                
                <!-- Items list -->
                <div class="p-4 max-h-[280px] overflow-y-auto" id="cartContainer">
                    <div id="emptyCartMessage" class="text-center py-8 text-slate-400">
                        <i class="bi bi-cart-x text-4xl block mb-2 opacity-50"></i>
                        <p class="text-xs font-medium">Keranjang belanja masih kosong.<br>Pilih menu produk di sebelah kiri.</p>
                    </div>
                    <ul class="space-y-3" id="cartList"></ul>
                </div>

                <div id="hiddenInputsContainer"></div>

                <!-- Footer Summary & Checkout Controls -->
                <div class="p-4 bg-slate-50 border-t border-slate-200 space-y-3">
                    <div class="flex justify-between items-center text-xs text-slate-600">
                        <span>Subtotal:</span>
                        <span class="font-bold" id="subtotalText">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center text-xs text-slate-600 pb-2 border-b border-slate-200">
                        <span>PPN (11%):</span>
                        <span class="font-bold text-rose-600" id="taxText">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-extrabold text-sm text-slate-800">Total Bayar:</span>
                        <span class="text-xl font-black text-emerald-700" id="totalText">Rp 0</span>
                    </div>
                    <input type="hidden" id="totalPriceInput" value="0">

                    <div>
                        <label for="paidAmountInput" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Nominal Bayar (Rp):</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500 font-bold text-sm">Rp</span>
                            <input type="number" name="paid_amount" id="paidAmountInput" 
                                   class="w-full pl-10 pr-3 py-2.5 bg-white border border-slate-300 rounded-xl font-bold text-base text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" 
                                   placeholder="0" min="0" oninput="calculateChange()" required>
                        </div>
                    </div>

                    <!-- Shortcut Quick Amounts -->
                    <div class="grid grid-cols-5 gap-1" id="quickAmounts">
                        <button type="button" class="py-1 px-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500 text-[10px] font-bold text-slate-700 transition-colors" onclick="setExactAmount()">Pas</button>
                        <button type="button" class="py-1 px-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500 text-[10px] font-bold text-slate-700 transition-colors" onclick="addAmount(10000)">+10k</button>
                        <button type="button" class="py-1 px-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500 text-[10px] font-bold text-slate-700 transition-colors" onclick="addAmount(20000)">+20k</button>
                        <button type="button" class="py-1 px-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500 text-[10px] font-bold text-slate-700 transition-colors" onclick="addAmount(50000)">+50k</button>
                        <button type="button" class="py-1 px-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500 text-[10px] font-bold text-slate-700 transition-colors" onclick="addAmount(100000)">+100k</button>
                    </div>

                    <div class="flex justify-between items-center p-2.5 bg-white rounded-xl border border-slate-200">
                        <span class="font-bold text-xs text-slate-600">Kembalian:</span>
                        <span class="font-black text-base text-emerald-700" id="changeText">Rp 0</span>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 text-white font-bold rounded-xl shadow-lg shadow-emerald-950/20 flex items-center justify-center gap-2 transition-all cursor-pointer text-sm" id="submitBtn" disabled>
                        <i class="bi bi-check-circle"></i> Selesaikan Transaksi
                    </button>

                    @if(session('last_order_id'))
                    <a href="{{ route('transactions.print', session('last_order_id')) }}" target="_blank" class="w-full py-2.5 px-4 bg-white border border-emerald-600 text-emerald-700 hover:bg-emerald-50 font-bold rounded-xl flex items-center justify-center gap-2 transition-all text-xs">
                        <i class="bi bi-printer"></i> Cetak Struk Transaksi Terakhir
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let cart = [];

    function formatRupiah(number) {
        return 'Rp ' + Number(number).toLocaleString('id-ID');
    }

    function addToCartFromBtn(btn) {
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
        cart = cart.filter(i => i.id !== id);
        renderCart();
    }

    function clearCart() {
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
            emptyMsg.style.display = 'block';
            submitBtn.disabled = true;
            document.getElementById('subtotalText').innerText = formatRupiah(0);
            document.getElementById('taxText').innerText = formatRupiah(0);
            document.getElementById('totalText').innerText = formatRupiah(0);
            document.getElementById('totalPriceInput').value = 0;
            calculateChange();
            return;
        }

        emptyMsg.style.display = 'none';
        submitBtn.disabled = false;

        let cartSubtotal = 0;

        cart.forEach((item, index) => {
            const itemSubtotal = item.price * item.qty;
            cartSubtotal += itemSubtotal;

            const li = document.createElement('li');
            li.className = 'flex items-center justify-between pb-2 border-b border-slate-100 text-xs';
            li.innerHTML = `
                <div class="pr-2">
                    <div class="font-bold text-slate-800 line-clamp-1 max-w-[150px]">${item.name}</div>
                    <div class="text-[11px] text-slate-400">${formatRupiah(item.price)} x ${item.qty}</div>
                    <div class="font-bold text-emerald-700">${formatRupiah(itemSubtotal)}</div>
                </div>
                <div class="flex items-center gap-1">
                    <button type="button" class="w-6 h-6 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold flex items-center justify-center cursor-pointer" onclick="updateQty(${item.id}, -1)">-</button>
                    <span class="px-1.5 font-bold text-xs text-slate-800">${item.qty}</span>
                    <button type="button" class="w-6 h-6 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold flex items-center justify-center cursor-pointer" onclick="updateQty(${item.id}, 1)">+</button>
                    <button type="button" class="w-6 h-6 rounded bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center ml-1 cursor-pointer" onclick="removeFromCart(${item.id})"><i class="bi bi-x"></i></button>
                </div>
            `;
            cartList.appendChild(li);

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
    }

    function calculateChange() {
        const total = parseInt(document.getElementById('totalPriceInput').value) || 0;
        const paid = parseInt(document.getElementById('paidAmountInput').value) || 0;
        const change = paid - total;

        const changeText = document.getElementById('changeText');
        const submitBtn = document.getElementById('submitBtn');

        if (paid >= total && total > 0) {
            changeText.innerText = formatRupiah(change);
            changeText.className = 'font-black text-base text-emerald-700';
            submitBtn.disabled = false;
        } else {
            changeText.innerText = total > 0 && paid > 0 ? 'Kurang ' + formatRupiah(Math.abs(change)) : 'Rp 0';
            changeText.className = 'font-black text-base text-rose-600';
            if (paid < total || total === 0) {
                submitBtn.disabled = true;
            }
        }
    }

    function setExactAmount() {
        const total = parseInt(document.getElementById('totalPriceInput').value) || 0;
        document.getElementById('paidAmountInput').value = total;
        calculateChange();
    }

    function addAmount(nominal) {
        const current = parseInt(document.getElementById('paidAmountInput').value) || 0;
        document.getElementById('paidAmountInput').value = current + nominal;
        calculateChange();
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
