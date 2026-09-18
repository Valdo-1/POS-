@extends('app')

@section('header')
<div class="page-header">
    <div>
        <h1 class="page-title">Point of Sales (POS)</h1>
        <p class="page-subtitle">Transaksi Penjualan Resto PPKD Jakarta Pusat</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted-green">Home</a></li>
            <li class="breadcrumb-item active text-main" aria-current="page">POS Transaksi</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="row g-4">
    <!-- Kolom Kiri: Menu & Katalog Produk -->
    <div class="col-lg-7 col-xl-8">
        <div class="card p-3 border-light shadow-sm mb-4">
            {{-- filter kategori & kolom pencarian nama produk --}}
            <div class="row g-2 align-items-center mb-3">
                <div class="col-md-6">
                    <form action="{{ route('transactions.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Cari produk...">
                        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
                        @if(request('search') || request('category_id'))
                            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary ms-2"><i class="bi bi-x-circle"></i></a>
                        @endif
                    </form>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('transactions.index') }}" class="btn btn-sm {{ !request('category_id') ? 'btn-primary' : 'btn-outline-secondary' }} mb-1">Semua</a>
                    @foreach($categories as $cat)
                        <a href="{{ route('transactions.index', ['category_id' => $cat->id, 'search' => request('search')]) }}" 
                           class="btn btn-sm {{ request('category_id') == $cat->id ? 'btn-primary' : 'btn-outline-secondary' }} mb-1">
                            {{ $cat->category_name }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- grid katalog produk yang bisa diklik kasir --}}
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" style="max-height: 550px; overflow-y: auto;">
                @forelse($products as $product)
                <div class="col">
                    <div class="card h-100 border text-center shadow-none product-card p-2 {{ $product->stock <= 0 ? 'bg-light opacity-75' : '' }}">
                        <div class="d-flex justify-content-center align-items-center mb-2" style="height: 100px; background-color: #f8f9fa; border-radius: 6px; overflow: hidden;">
                            @if($product->product_photo && file_exists(public_path('storage/' . $product->product_photo)))
                                <img src="{{ asset('storage/' . $product->product_photo) }}" alt="{{ $product->product_name }}" style="max-height: 90px; max-width: 100%; object-fit: contain;" onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'bi bi-cup-hot text-muted\' style=\'font-size: 2.5rem;\'></i>';">
                            @else
                                <i class="bi bi-cup-hot text-muted" style="font-size: 2.5rem;"></i>
                            @endif
                        </div>
                        <h6 class="fw-bold mb-1 text-truncate" title="{{ $product->product_name }}">{{ $product->product_name }}</h6>
                        <span class="badge bg-light text-muted mb-1">{{ $product->category->category_name ?? 'Uncategorized' }}</span>
                        <div class="fw-bold text-success mb-1">Rp {{ number_format($product->product_price, 0, ',', '.') }}</div>
                        <div class="small mb-2 {{ $product->stock > 10 ? 'text-muted' : ($product->stock > 0 ? 'text-warning' : 'text-danger fw-bold') }}">
                            Stok: {{ $product->stock }}
                        </div>
                        @if($product->stock > 0)
                            <button type="button" 
                                class="btn btn-sm btn-primary w-100" 
                                data-id="{{ $product->id }}" 
                                data-name="{{ $product->product_name }}" 
                                data-price="{{ $product->product_price }}" 
                                data-stock="{{ $product->stock }}" 
                                onclick="addToCartFromBtn(this)">
                                <i class="bi bi-cart-plus"></i> Tambah
                            </button>
                        @else
                            <button type="button" class="btn btn-sm btn-secondary w-100" disabled>Habis</button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mt-2">Tidak ada produk yang ditemukan.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Riwayat Transaksi Terbaru Kasir -->
        @if(count($todayOrders) > 0)
        <div class="card p-3 border-light shadow-sm">
            <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-1"></i> Transaksi Terakhir Anda Hari Ini</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Order</th>
                            <th>Waktu</th>
                            <th>Total</th>
                            <th>Kembalian</th>  
                            <th class="text-center">Cetak Struk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todayOrders as $order)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $order->order_code }}</span></td>
                            <td>{{ $order->created_at?->format('H:i') }} WIB</td>
                            <td class="fw-bold text-success">Rp {{ number_format($order->order_amount, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($order->order_change, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('transactions.print', $order->id) }}" target="_blank" class="btn btn-xs btn-outline-success fw-bold" title="Cetak Struk Transaksi Ini">
                                    <i class="bi bi-printer me-1"></i> Print
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

    {{-- Kolom Kanan: Keranjang belanja & form pembayaran kasir --}}
    <div class="col-lg-5 col-xl-4">
        <div class="card border-light shadow-sm sticky-top" style="top: 20px;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-cart3 text-primary me-2"></i>Keranjang Belanja</h5>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearCart()" title="Kosongkan Keranjang">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            
            <form action="{{ route('transactions.store') }}" method="POST" id="checkoutForm" onsubmit="return validateCheckout()">
                @csrf
                {{-- container daftar produk yang ditambah ke keranjang --}}
                <div class="card-body p-3" id="cartContainer" style="max-height: 320px; overflow-y: auto;">
                    <div id="emptyCartMessage" class="text-center py-5 text-muted">
                        <i class="bi bi-cart-x fs-1 text-muted"></i>
                        <p class="mt-2 mb-0">Keranjang masih kosong.<br><small>Pilih produk di sebelah kiri.</small></p>
                    </div>
                    <ul class="list-group list-group-flush" id="cartList"></ul>
                </div>

                <!-- Hidden inputs untuk item keranjang -->
                <div id="hiddenInputsContainer"></div>

                <!-- Rincian Pembayaran -->
                <div class="card-footer bg-light p-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted small">Subtotal:</span>
                        <span class="fw-bold small" id="subtotalText">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <span class="text-muted small">PPN (11%):</span>
                        <span class="fw-bold small text-danger" id="taxText">Rp 0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold">Total Bayar:</span>
                        <h4 class="mb-0 fw-bold text-success" id="totalText">Rp 0</h4>
                    </div>
                    <input type="hidden" id="totalPriceInput" value="0">

                    <div class="mb-3 mt-3">
                        <label for="paidAmountInput" class="form-label small fw-bold">Nominal Pembayaran (Rp):</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="paid_amount" id="paidAmountInput" class="form-control form-control-lg fw-bold" placeholder="0" min="0" oninput="calculateChange()" required>
                        </div>
                    </div>

                    <!-- Shortcut Tombol Nominal Cepat -->
                    <div class="d-flex gap-1 mb-3 flex-wrap" id="quickAmounts">
                        <button type="button" class="btn btn-xs btn-outline-secondary flex-fill" onclick="setExactAmount()">Uang Pas</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary flex-fill" onclick="addAmount(10000)">+10k</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary flex-fill" onclick="addAmount(20000)">+20k</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary flex-fill" onclick="addAmount(50000)">+50k</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary flex-fill" onclick="addAmount(100000)">+100k</button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-white rounded border">
                        <span class="fw-bold small">Kembalian:</span>
                        <h5 class="mb-0 fw-bold text-primary" id="changeText">Rp 0</h5>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold py-2 shadow-sm" id="submitBtn" disabled>
                        <i class="bi bi-check-circle me-1"></i> Selesaikan Transaksi
                    </button>

                    <!-- Tombol Cetak Struk (Hanya tampil jika pembayaran transaksi baru saja selesai) -->
                    @if(session('last_order_id'))
                    <a href="{{ route('transactions.print', session('last_order_id')) }}" target="_blank" class="btn btn-outline-success w-100 fw-bold mt-2 py-2 shadow-sm">
                        <i class="bi bi-printer me-1"></i> Cetak Struk Transaksi
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // nyimpen state barang yang lagi dipilih di keranjang
    let cart = [];

    function formatRupiah(number) {
        return 'Rp ' + Number(number).toLocaleString('id-ID');
    }

    // baca data produk dari atribut data-* tombol biar bebas error kutip/sintaks blade
    function addToCartFromBtn(btn) {
        addToCart(
            Number(btn.dataset.id),
            btn.dataset.name,
            Number(btn.dataset.price),
            Number(btn.dataset.stock)
        );
    }

    // masukin produk ke keranjang, kalau udah ada tinggal naikin qty
    function addToCart(id, name, price, maxStock) {
        const existing = cart.find(item => item.id === id);
        if (existing) {
            // tahan kalau kasir nambah melebihi stok yang ada di gudang
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

    // buat tombol plus minus di keranjang
    function updateQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if (!item) return;

        const newQty = item.qty + delta;
        // kalau dikurangin sampe 0, langsung buang dari list belanja
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

    // gambar ulang isi keranjang & siapin input hidden buat dikirim form ke Laravel
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

            // bikin baris item di UI
            const li = document.createElement('li');
            li.className = 'list-group-item px-0 py-2 border-bottom';
            li.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1 pe-2">
                        <div class="fw-bold small text-truncate" style="max-width: 170px;">${item.name}</div>
                        <div class="text-muted small">${formatRupiah(item.price)} x ${item.qty}</div>
                        <div class="text-success fw-bold small">${formatRupiah(itemSubtotal)}</div>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <button type="button" class="btn btn-xs btn-outline-secondary" onclick="updateQty(${item.id}, -1)">-</button>
                        <span class="px-2 fw-bold small">${item.qty}</span>
                        <button type="button" class="btn btn-xs btn-outline-secondary" onclick="updateQty(${item.id}, 1)">+</button>
                        <button type="button" class="btn btn-xs btn-outline-danger ms-1" onclick="removeFromCart(${item.id})"><i class="bi bi-x"></i></button>
                    </div>
                </div>
            `;
            cartList.appendChild(li);

            // input hidden biar array items[] kebaca waktu di-POST ke controller
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

    // hitung uang kembalian dan cek apakah duitnya cukup
    function calculateChange() {
        const total = parseInt(document.getElementById('totalPriceInput').value) || 0;
        const paid = parseInt(document.getElementById('paidAmountInput').value) || 0;
        const change = paid - total;

        const changeText = document.getElementById('changeText');
        const submitBtn = document.getElementById('submitBtn');

        if (paid >= total && total > 0) {
            changeText.innerText = formatRupiah(change);
            changeText.className = 'mb-0 fw-bold text-success';
            submitBtn.disabled = false;
        } else {
            changeText.innerText = total > 0 && paid > 0 ? 'Kurang ' + formatRupiah(Math.abs(change)) : 'Rp 0';
            changeText.className = 'mb-0 fw-bold text-danger';
            if (paid < total || total === 0) {
                submitBtn.disabled = true;
            }
        }
    }

    // shortcut kasir: klik tombol uang pas
    function setExactAmount() {
        const total = parseInt(document.getElementById('totalPriceInput').value) || 0;
        document.getElementById('paidAmountInput').value = total;
        calculateChange();
    }

    // shortcut kasir: klik nominal cepat (+10k, +20k, dll)
    function addAmount(nominal) {
        const current = parseInt(document.getElementById('paidAmountInput').value) || 0;
        document.getElementById('paidAmountInput').value = current + nominal;
        calculateChange();
    }

    // double check di sisi browser sebelum transaksi ditembak ke server
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
