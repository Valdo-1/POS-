@extends('app')

@section('header')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Role & Hak Akses</h1>
        <p class="page-subtitle">Ubah nama role dan sesuaikan izin/permission fitur yang dapat diakses.</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted-green">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}" class="text-decoration-none text-muted-green">Roles</a></li>
            <li class="breadcrumb-item active text-main" aria-current="page">Edit</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="card p-4 border-light shadow-sm">
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Input Nama Role -->
        <div class="mb-4">
            <label for="name" class="form-label fw-bold fs-6">Nama Role / Jabatan <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                   id="name" name="name" value="{{ old('name', $role->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <hr class="my-4">

        <!-- Pengaturan Hak Akses (Permissions) -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1"><i class="bi bi-shield-check text-success me-2"></i>Pengaturan Hak Akses (Permissions)</h5>
                    <p class="text-muted small mb-0">Pilih atau hapus centang fitur yang dapat diakses oleh role ini.</p>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-success me-1" id="btn-select-all"><i class="bi bi-check-all"></i> Pilih Semua</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-deselect-all"><i class="bi bi-x-circle"></i> Batal Semua</button>
                </div>
            </div>

            @php
                $currentPermissions = old('permissions', $role->permissions ?? []);
                if (!is_array($currentPermissions)) {
                    $currentPermissions = [];
                }
                $isAdminRole = in_array(strtolower(trim($role->name)), ['admin', 'administrator']);
            @endphp

            @if($isAdminRole)
            <div class="alert alert-info border-info-subtle mb-3">
                <i class="bi bi-info-circle-fill me-2"></i> Role <strong>Admin / Administrator</strong> secara otomatis memiliki seluruh hak akses penuh di sistem.
            </div>
            @endif

            <div class="row g-3">
                @foreach($availablePermissions as $key => $perm)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border p-3 permission-card shadow-sm-hover">
                        <div class="form-check">
                            <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" 
                                   value="{{ $key }}" id="perm_{{ $key }}"
                                   {{ $isAdminRole || in_array($key, $currentPermissions) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-dark d-block cursor-pointer ms-2" for="perm_{{ $key }}">
                                <i class="bi {{ $perm['icon'] }} text-success me-1"></i> {{ $perm['label'] }}
                            </label>
                            <small class="text-muted d-block mt-1 ms-2 fs-7">{{ $perm['desc'] }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @error('permissions')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="text-end pt-3 border-top">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary me-2"><i class="bi bi-x-lg"></i> Batal</a>
            <button type="submit" class="btn btn-success px-4"><i class="bi bi-save me-1"></i> Perbarui Role & Hak Akses</button>
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
