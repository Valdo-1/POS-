@extends('app')

@section('header')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">TAMBAH ROLE BARU</h1>
        <p class="text-slate-500 text-sm">Buat role baru dan konfigurasikan izin fitur (permissions) KETARA</p>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-semibold">
            <li><a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-emerald-600">Home</a></li>
            <li><span class="text-slate-300">/</span></li>
            <li><a href="{{ route('roles.index') }}" class="text-slate-500 hover:text-emerald-600">Roles</a></li>
            <li><span class="text-slate-300">/</span></li>
            <li class="text-emerald-700 font-bold" aria-current="page">Create</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs max-w-4xl">
    <form action="{{ route('roles.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Input Nama Role -->
        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Role / Jabatan <span class="text-rose-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                   placeholder="Contoh: gudang, kasir_shift_malam, supervisor" 
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" required>
            @error('name')
                <div class="text-rose-500 text-xs mt-1">{{ $message }}</div>
            @enderror
            <div class="text-slate-400 text-xs mt-1">Masukkan nama peran deskriptif untuk menentukan wewenang pengguna.</div>
        </div>

        <hr class="border-slate-100">

        <!-- Pengaturan Hak Akses (Permissions Grid) -->
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div>
                    <h3 class="font-bold text-base text-slate-800 flex items-center gap-2">
                        <i class="bi bi-shield-check text-emerald-600"></i>
                        <span>Pilih Hak Akses (Permissions)</span>
                    </h3>
                    <p class="text-xs text-slate-400">Centang fitur yang boleh diakses oleh role ini (misal: role Gudang hanya centang STOK PRODUK dan Produk).</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 text-xs font-bold transition-colors cursor-pointer" id="btn-select-all">
                        <i class="bi bi-check-all"></i> Pilih Semua
                    </button>
                    <button type="button" class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200 text-xs font-bold transition-colors cursor-pointer" id="btn-deselect-all">
                        <i class="bi bi-x-circle"></i> Batal Semua
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($availablePermissions as $key => $perm)
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 hover:border-emerald-500 hover:bg-white transition-all cursor-pointer group">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="{{ $key }}" 
                               class="permission-checkbox mt-1 w-4 h-4 text-emerald-600 bg-white border-slate-300 rounded focus:ring-emerald-500"
                               {{ is_array(old('permissions')) && in_array($key, old('permissions')) ? 'checked' : '' }}>
                        <div>
                            <div class="font-bold text-xs text-slate-800 flex items-center gap-1.5">
                                <i class="bi {{ $perm['icon'] }} text-emerald-600"></i>
                                {{ $perm['label'] }}
                            </div>
                            <div class="text-[11px] text-slate-400 mt-1 leading-snug">{{ $perm['desc'] }}</div>
                        </div>
                    </label>
                </div>
                @endforeach
            </div>
            @error('permissions')
                <div class="text-rose-500 text-xs mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
            <a href="{{ route('roles.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer flex items-center gap-1">
                <i class="bi bi-save"></i> Simpan Role & Hak Akses
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllBtn = document.getElementById('btn-select-all');
        const deselectAllBtn = document.getElementById('btn-deselect-all');
        const checkboxes = document.querySelectorAll('.permission-checkbox');

        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = true);
            });
        }
        if (deselectAllBtn) {
            deselectAllBtn.addEventListener('click', function() {
                checkboxes.forEach(cb => cb.checked = false);
            });
        }
    });
</script>
@endsection
