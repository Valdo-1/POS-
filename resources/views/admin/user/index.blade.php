@extends('app')

@section('header')
<div class="page-header">
    <div>
        <h1 class="page-title">Users</h1>
        <p class="page-subtitle">Manage system users.</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted-green">Home</a></li>
            <li class="breadcrumb-item active text-main" aria-current="page">Users</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="card p-4 border-light shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">User List</h4>
        <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="bi bi-person-plus"></i> Create User</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role)
                            <span class="badge bg-primary">{{ $user->role->name }}</span>
                        @else
                            <span class="text-muted">No Role</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at?->format('Y-m-d') ?? '-' }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Edit User"><i class="bi bi-pencil"></i></a>
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
                        {{-- tanya dulu biar admin ga nyesel kalau kehapus --}}
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus User"><i class="bi bi-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
