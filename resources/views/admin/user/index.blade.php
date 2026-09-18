@extends('app')

@section('header')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">MANAGEMENT USERS</h1>
        <p class="text-slate-500 text-sm">Kelola akun pengguna dan pembagian role sistem KETARA</p>
    </div>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-semibold">
            <li><a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-emerald-600">Home</a></li>
            <li><span class="text-slate-300">/</span></li>
            <li class="text-emerald-700 font-bold" aria-current="page">Users</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
    <div class="flex items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
        <div>
            <h3 class="font-bold text-lg text-slate-800">Daftar Akun User</h3>
            <p class="text-xs text-slate-400">Total {{ $users->count() }} akun terdaftar dalam sistem.</p>
        </div>
        <a href="{{ route('users.create') }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-1.5 shadow-xs transition-colors">
            <i class="bi bi-person-plus text-sm"></i> Tambah User Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4 w-12">ID</th>
                    <th class="py-3 px-4">Nama Lengkap</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4">Role</th>
                    <th class="py-3 px-4">Dibuat Pada</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-3 px-4 font-semibold text-slate-800">#{{ $user->id }}</td>
                    <td class="py-3 px-4 font-bold text-slate-800">
                        <div class="flex items-center gap-2.5">
                            <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-emerald-500">
                            <span>{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-slate-600">{{ $user->email }}</td>
                    <td class="py-3 px-4">
                        @if($user->role)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ strtoupper($user->role->name) }}
                            </span>
                        @else
                            <span class="text-slate-400 italic text-xs">No Role</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-slate-400">{{ $user->created_at?->format('d M Y') ?? '-' }}</td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('users.edit', $user->id) }}" class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" title="Edit User">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @php
                                $userRoleName = strtolower(trim($user->role->name ?? ''));
                                $isUserDeletable = true;
                                if (in_array($userRoleName, ['admin', 'administrator', 'kasir', 'cashier', 'pimpinan', 'leader'])) {
                                    $roleCount = $users->filter(fn($u) => strtolower(trim($u->role->name ?? '')) === $userRoleName)->count();
                                    if ($roleCount <= 1) {
                                        $isUserDeletable = false;
                                    }
                                }
                            @endphp

                            @if($isUserDeletable)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus User">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-slate-400">Tidak ada user ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
