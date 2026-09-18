@extends('app')

@section('header')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">TAMBAH USER BARU</h1>
        <p class="text-slate-500 text-sm">Buat akun pengguna baru untuk mengelola sistem KETARA</p>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-semibold">
            <li><a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-emerald-600">Home</a></li>
            <li><span class="text-slate-300">/</span></li>
            <li><a href="{{ route('users.index') }}" class="text-slate-500 hover:text-emerald-600">Users</a></li>
            <li><span class="text-slate-300">/</span></li>
            <li class="text-emerald-700 font-bold" aria-current="page">Create</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs max-w-3xl">
    <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" placeholder="Contoh: Muhammad Osvaldo" required>
            @error('name')
                <div class="text-rose-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" placeholder="user@gmail.com" required>
            @error('email')
                <div class="text-rose-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Password <span class="text-rose-500">*</span></label>
            <input type="password" name="password" id="password" 
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" placeholder="Minimal 8 karakter" required minlength="8">
            @error('password')
                <div class="text-rose-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="role_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Pilih Role / Jabatan <span class="text-rose-500">*</span></label>
            <select name="role_id" id="role_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:border-emerald-500" required>
                <option value="">-- Pilih Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ strtoupper($role->name) }}
                    </option>
                @endforeach
            </select>
            @error('role_id')
                <div class="text-rose-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
            <a href="{{ route('users.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer flex items-center gap-1">
                <i class="bi bi-save"></i> Simpan User
            </button>
        </div>
    </form>
</div>
@endsection
