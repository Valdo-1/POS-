@extends('app')

@section('header')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">MASTER PRODUK & MENU</h1>
        <p class="text-slate-500 text-sm">Kelola daftar menu makanan, minuman, harga, dan stok barang KETARA</p>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-semibold">
            <li><a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-emerald-600">Home</a></li>
            <li><span class="text-slate-300">/</span></li>
            <li class="text-emerald-700 font-bold" aria-current="page">Products</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
    <div class="flex items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
        <div>
            <h3 class="font-bold text-lg text-slate-800">Daftar Produk</h3>
            <p class="text-xs text-slate-400">Total {{ $products->count() }} produk terdaftar dalam katalog.</p>
        </div>
        <a href="{{ route('products.create') }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1.5 shadow-xs transition-colors">
            <i class="bi bi-box-seam text-sm"></i> Tambah Produk Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4 w-16">Foto</th>
                    <th class="py-3 px-4">Nama Produk</th>
                    <th class="py-3 px-4">Kategori</th>
                    <th class="py-3 px-4">Harga Satuan</th>
                    <th class="py-3 px-4 text-center">Stok</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-3 px-4">
                        @if($product->product_photo && file_exists(public_path('storage/' . $product->product_photo)))
                            <img src="{{ asset('storage/'.$product->product_photo) }}" alt="Foto Produk" class="w-10 h-10 rounded-lg object-cover border border-slate-200">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center">
                                <i class="bi bi-cup-hot text-lg"></i>
                            </div>
                        @endif
                    </td>
                    <td class="py-3 px-4 font-bold text-slate-800">{{ $product->product_name }}</td>
                    <td class="py-3 px-4">
                        <span class="bg-slate-100 text-slate-700 border border-slate-200 px-2.5 py-0.5 rounded-full text-[11px] font-semibold">
                            {{ $product->category ? $product->category->category_name : '-' }}
                        </span>
                    </td>
                    <td class="py-3 px-4 font-extrabold text-emerald-700">Rp {{ number_format($product->product_price, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 text-center font-bold text-slate-800 text-sm">
                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md {{ $product->stock > 10 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        @if($product->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">Nonaktif</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('products.edit', $product->id) }}" class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" title="Edit Produk">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus Produk">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-8 text-slate-400">Tidak ada produk ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
