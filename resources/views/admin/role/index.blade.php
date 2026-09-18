@extends('app')

@section('header')
<div class="page-header">
    <div>
        <h1 class="page-title">Management Role & Hak Akses</h1>
        <p class="page-subtitle">Kelola peran pengguna dan atur hak akses fitur (permissions).</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted-green">Home</a></li>
            <li class="breadcrumb-item active text-main" aria-current="page">Roles</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="card p-4 border-light shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Daftar Role & Hak Akses</h4>
            <p class="text-muted small mb-0">Role menentukan menu dan aksi apa saja yang boleh diakses pengguna.</p>
        </div>
        <a href="{{ route('roles.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i> Tambah Role Baru</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th style="width: 180px;">Nama Role</th>
                    <th>Hak Akses (Permissions)</th>
                    <th style="width: 140px;">Dibuat Pada</th>
                    <th style="width: 110px;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                @php
                    $roleName = strtolower(trim($role->name));
                    $isAdmin = in_array($roleName, ['admin', 'administrator']);
                    $perms = $role->permissions ?? [];
                @endphp
                <tr>
                    <td class="fw-semibold">#{{ $role->id }}</td>
                    <td>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 fs-6 fw-bold">
                            <i class="bi bi-shield-person me-1"></i>{{ strtoupper($role->name) }}
                        </span>
                    </td>
                    <td>
                        @if($isAdmin)
                            <span class="badge bg-primary me-1 mb-1"><i class="bi bi-star-fill me-1"></i> Full Access (Semua Fitur)</span>
                        @elseif(!empty($perms) && is_array($perms))
                            @foreach($perms as $pKey)
                                @if(isset($availablePermissions[$pKey]))
                                    <span class="badge bg-light text-dark border me-1 mb-1" title="{{ $availablePermissions[$pKey]['desc'] }}">
                                        <i class="bi {{ $availablePermissions[$pKey]['icon'] }} text-success me-1"></i>
                                        {{ $availablePermissions[$pKey]['label'] }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark border me-1 mb-1">{{ $pKey }}</span>
                                @endif
                            @endforeach
                        @else
                            <span class="text-muted fst-italic small">Belum ada hak akses</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $role->created_at?->format('d M Y') ?? '-' }}</td>
                    <td class="text-end">
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit Role & Permissions"><i class="bi bi-pencil"></i></a>
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
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus role {{ $role->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Role"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Belum ada role yang terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
