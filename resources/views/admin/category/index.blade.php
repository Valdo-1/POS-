@extends('app')

@section('header')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">KATEGORI PRODUK</h1>
        <p class="text-slate-500 text-sm">Kelola pengelompokan kategori menu resto KETARA</p>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-semibold">
            <li><a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-emerald-600">Home</a></li>
            <li><span class="text-slate-300">/</span></li>
            <li class="text-emerald-700 font-bold" aria-current="page">Categories</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs max-w-4xl">
    <div class="flex items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
        <div>
            <h3 class="font-bold text-lg text-slate-800">Daftar Kategori</h3>
            <p class="text-xs text-slate-400">Total {{ $categories->count() }} kategori menu terdaftar.</p>
        </div>
        <a href="{{ route('categories.create') }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1.5 shadow-xs transition-colors">
            <i class="bi bi-plus-lg text-sm"></i> Tambah Kategori
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4 w-12">ID</th>
                    <th class="py-3 px-4">Nama Kategori</th>
                    <th class="py-3 px-4">Jumlah Produk</th>
                    <th class="py-3 px-4">Dibuat Pada</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($categories as $category)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-3 px-4 font-semibold text-slate-800">#{{ $category->id }}</td>
                    <td class="py-3 px-4 font-bold text-slate-800">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-700">
                            <i class="bi bi-tags text-emerald-600"></i>
                            {{ $category->category_name }}
                        </span>
                    </td>
                    <td class="py-3 px-4 font-semibold text-slate-600">
                        {{ $category->products()->count() }} Produk
                    </td>
                    <td class="py-3 px-4 text-slate-400">{{ $category->created_at?->format('d M Y') ?? '-' }}</td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('categories.edit', $category->id) }}" class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" title="Edit Kategori">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus Kategori">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-slate-400">Tidak ada kategori ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
