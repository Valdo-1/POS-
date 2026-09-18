<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi - {{ $order->order_code }}</title>
    <style>
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 10px;
            }
            .no-print {
                display: none !important;
            }
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            color: #000;
            background-color: #f8f9fa;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .receipt-card {
            width: 320px;
            background: #fff;
            padding: 18px;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .dashed-line {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }
        .table-receipt {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .table-receipt td {
            padding: 4px 0;
            vertical-align: top;
        }
        .btn-print-action {
            display: inline-block;
            background-color: #10b981;
            color: #fff;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            font-family: sans-serif;
            font-weight: bold;
            margin-bottom: 15px;
            cursor: pointer;
            border: none;
        }
    </style>
</head>
<body>

<div>
    <div class="text-center no-print">
        <button onclick="window.print()" class="btn-print-action">🖨️ Cetak Struk Sekarang</button>
    </div>

    <div class="receipt-card">
        <div class="text-center">
            <h2 style="margin: 0 0 4px 0;">KETARA COFFEE</h2>
            <div class="fw-bold">KETARA PPKD Jakarta Pusat</div>
            <div style="font-size: 11px;">Jl. Karet Pasar Baru Barat V No. 23, RT. 3/RW. 7, Karet Tengsin, Kecamatan Tanah Abang, Kota Jakarta Pusat</div>
            <div style="font-size: 11px;">Telp: 0812 8477 5339</div>
        </div>

        <div class="dashed-line"></div>

        <table class="table-receipt" style="font-size: 11px;">
            <tr>
                <td>No. Order</td>
                <td class="text-right fw-bold">{{ $order->order_code }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td class="text-right">{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') : $order->created_at?->format('d/m/Y') }} {{ $order->created_at?->format('H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td class="text-right">{{ $order->user->name ?? 'Kasir' }}</td>
            </tr>
        </table>

        <div class="dashed-line"></div>

        <table class="table-receipt">
            <thead>
                <tr style="font-size: 11px;" class="fw-bold">
                    <td style="width: 50%;">Item</td>
                    <td class="text-center">Qty</td>
                    <td class="text-right">Total</td>
                </tr>
            </thead>
            <tbody>
                @foreach($order->details as $item)
                <tr>
                    <td colspan="3">
                        <div class="fw-bold">{{ $item->product->product_name ?? 'Produk Dihapus' }}</div>
                        <div style="font-size: 11px; color: #555; display: flex; justify-content: space-between;">
                            <span>{{ $item->qty }} x Rp {{ number_format($item->order_price, 0, ',', '.') }}</span>
                            <span class="fw-bold">Rp {{ number_format($item->order_amount, 0, ',', '.') }}</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="dashed-line"></div>

        <table class="table-receipt">
            <tr>
                <td>Subtotal</td>
                <td class="text-right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>PPN {{ number_format($order->tax_rate, 0) }}%</td>
                <td class="text-right">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</td>
            </tr>
            <tr class="fw-bold">
                <td>Total Bayar</td>
                <td class="text-right">Rp {{ number_format($order->order_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Nominal Bayar</td>
                <td class="text-right">Rp {{ number_format($order->order_amount + $order->order_change, 0, ',', '.') }}</td>
            </tr>
            <tr class="fw-bold">
                <td>Kembalian</td>
                <td class="text-right">Rp {{ number_format($order->order_change, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="dashed-line"></div>

        <div class="text-center" style="font-size: 11px; margin-top: 10px;">
            <div> KETARA PPKD Jakarta Pusat </div>
            <div>TERIMA KASIH</div>
            <div style="margin-top: 4px; color: #666;">Selamat Datang Kembali </div>
            <div style="margin-top: 4px; color: #666;">Kritik Dan Saran Bisa WA: 0812-8477-5339</div>
        </div>
    </div>
</div>

<script>
    // Otomatis buka dialog cetak saat halaman dimuat
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 500);
    };
</script>
</body>
</html>
