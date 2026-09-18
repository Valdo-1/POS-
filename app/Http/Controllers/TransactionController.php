<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // tampilin layar POS buat kasir jualan
    public function index(Request $request)
    {
        $categories = Category::all();

        // cuma ambil produk yang statusnya masih aktif dijual
        $query = Product::with('category')->where('is_active', true);
        
        // saring produk kalau kasir pilih salah satu kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // cari produk berdasarkan ketikan nama
        if ($request->filled('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('product_name')->get();

        // ambil 5 transaksi terakhir kasir ini hari ini biar gampang crosscheck
        $todayOrders = Order::with('details.product')
            ->where('user_id', Auth::id())
            ->whereDate('order_date', today())
            ->latest()
            ->take(5)
            ->get();

        return view('pos.index', compact('products', 'categories', 'todayOrders'));
    }

    // proses checkout keranjang belanja
    public function store(Request $request)
    {
        // pastiin keranjang ga kosong dan uang bayar valid
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        try {
            // bungkus database transaction biar kalau ada error di tengah jalan, stok ga kepotong sepihak
            $order = DB::transaction(function () use ($request) {
                $cartSubtotal = 0;
                $itemsToProcess = [];

                foreach ($request->items as $item) {
                    // lock baris produk biar ga bentrok stok kalau ada kasir lain checkout barengan
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                    $qty = (int) $item['qty'];

                    // kalau stok fisik ga cukup, batalin transaksi
                    if ($product->stock < $qty) {
                        throw new \Exception("Stok tidak mencukupi untuk {$product->product_name}. Sisa stok: {$product->stock}");
                    }

                    $itemSubtotal = $product->product_price * $qty;
                    $cartSubtotal += $itemSubtotal;

                    $itemsToProcess[] = [
                        'product' => $product,
                        'qty' => $qty,
                        'order_price' => $product->product_price,
                        'order_amount' => $itemSubtotal,
                    ];
                }

                // PAJAK
                $taxRate = 11.0;
                $taxAmount = round($cartSubtotal * ($taxRate / 100));
                $totalPrice = $cartSubtotal + $taxAmount;

                $paidAmount = (int) $request->paid_amount;
                // uang bayar kurang, tolak transaksi
                if ($paidAmount < $totalPrice) {
                    throw new \Exception("Uang pembayaran kurang! Total: Rp " . number_format($totalPrice, 0, ',', '.') . ", Pembayaran: Rp " . number_format($paidAmount, 0, ',', '.'));
                }

                $change = $paidAmount - $totalPrice;

                // bikin nomor invoice unik format TRX-waktu-random
                $orderCode = 'TRX-' . date('YmdHis') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'order_code' => $orderCode,
                    'order_date' => now()->toDateString(),
                    'subtotal' => $cartSubtotal,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'total_price' => $totalPrice,
                    'order_amount' => $totalPrice,
                    'order_change' => $change,
                    'order_status' => 1,
                ]);

                // simpan rincian item & langsung kurangi stok produk
                foreach ($itemsToProcess as $processed) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $processed['product']->id,
                        'qty' => $processed['qty'],
                        'order_price' => $processed['order_price'],
                        'order_amount' => $processed['order_amount'],
                    ]);

                    $processed['product']->decrement('stock', $processed['qty']);
                }

                return $order;
            });

            // transaksi beres, balikin info sukses beserta kembaliannya
            return redirect()->route('transactions.index')
                ->with('success', "Transaksi {$order->order_code} berhasil! Total: Rp " . number_format($order->order_amount, 0, ',', '.') . " | Kembalian: Rp " . number_format($order->order_change, 0, ',', '.'))
                ->with('last_order_id', $order->id);
        } catch (\Exception $e) {
            // kalau ada masalah (stok habis / uang kurang), lempar pesan error ke layar POS
            return redirect()->route('transactions.index')->with('error', $e->getMessage());
        }
    }

    // cetak struk kasir (thermal receipt)
    public function printReceipt($id)
    {
        $order = Order::with(['user', 'details.product'])->findOrFail($id);
        return view('pos.receipt', compact('order'));
    }
}
