@extends('app')

@section('header')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">ROLE & HAK AKSES</h1>
        <p class="text-slate-500 text-sm">Kelola peran pengguna dan atur hak akses fitur (permissions) KETARA</p>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-semibold">
            <li><a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-emerald-600">Home</a></li>
            <li><span class="text-slate-300">/</span></li>
            <li class="text-emerald-700 font-bold" aria-current="page">Roles</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
    <div class="flex items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
        <div>
            <h3 class="font-bold text-lg text-slate-800">Daftar Role & Hak Akses</h3>
            <p class="text-xs text-slate-400">Role menentukan menu dan aksi apa saja yang boleh diakses pengguna.</p>
        </div>
        <a href="{{ route('roles.create') }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1.5 shadow-xs transition-colors">
            <i class="bi bi-plus-lg text-sm"></i> Tambah Role Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4 w-12">ID</th>
                    <th class="py-3 px-4 w-44">Nama Role</th>
                    <th class="py-3 px-4">Hak Akses (Permissions)</th>
                    <th class="py-3 px-4 w-32">Dibuat Pada</th>
                    <th class="py-3 px-4 text-right w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($roles as $role)
                @php
                    $roleName = strtolower(trim($role->name));
                    $isAdmin = in_array($roleName, ['admin', 'administrator']);
                    $perms = $role->permissions ?? [];
                @endphp
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-3 px-4 font-semibold text-slate-800">#{{ $role->id }}</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <i class="bi bi-shield-person"></i> {{ strtoupper($role->name) }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        @if($isAdmin)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white shadow-2xs">
                                <i class="bi bi-star-fill"></i> Full Access (Semua Fitur)
                            </span>
                        @elseif(!empty($perms) && is_array($perms))
                            <div class="flex flex-wrap gap-1">
                            @foreach($perms as $pKey)
                                @if(isset($availablePermissions[$pKey]))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200" title="{{ $availablePermissions[$pKey]['desc'] }}">
                                        <i class="bi {{ $availablePermissions[$pKey]['icon'] }} text-emerald-600"></i>
                                        {{ $availablePermissions[$pKey]['label'] }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">{{ $pKey }}</span>
                                @endif
                            @endforeach
                            </div>
                        @else
                            <span class="text-slate-400 italic text-xs">Belum ada hak akses</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-slate-400">{{ $role->created_at?->format('d M Y') ?? '-' }}</td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('roles.edit', $role->id) }}" class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" title="Edit Role & Hak Akses">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @php
                                $isDeletable = true;
                                if ($isAdmin) {
                                    $isDeletable = false;
                                } elseif (in_array($roleName, ['kasir', 'cashier'])) {
                                    $kasirCount = $roles->filter(fn($r) => in_array(strtolower(trim($r->name)), ['kasir', 'cashier']))->count();
                                    if ($kasirCount <= 1) $isDeletable = false;
                                } elseif (in_array($roleName, ['pimpinan', 'leader'])) {
                                    $pimpinanCount = $roles->filter(fn($r) => in_array(strtolower(trim($r->name)), ['pimpinan', 'leader']))->count();
                                    if ($pimpinanCount <= 1) $isDeletable = false;
                                }
                            @endphp

                            @if($isDeletable)
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus role {{ $role->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus Role">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-slate-400">Belum ada role yang terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
