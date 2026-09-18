<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Daftar modul / hak akses yang tersedia di sistem POS
     */
    public static function availablePermissions(): array
    {
        return [
            'pos' => [
                'label' => 'PESANAN / POS Kasir',
                'icon' => 'bi-cart-check-fill',
                'desc' => 'Akses fitur mesin kasir dan transaksi pesanan'
            ],
            'stock' => [
                'label' => 'STOK PRODUK',
                'icon' => 'bi-boxes',
                'desc' => 'Akses pemantauan dan update stok barang'
            ],
            'reports' => [
                'label' => 'LAPORAN PENJUALAN',
                'icon' => 'bi-graph-up-arrow',
                'desc' => 'Akses grafik omzet dan laporan histori transaksi'
            ],
            'products' => [
                'label' => 'Produk',
                'icon' => 'bi-box-seam',
                'desc' => 'Akses kelola daftar barang dan harga produk'
            ],
            'categories' => [
                'label' => 'Kategori',
                'icon' => 'bi-tags',
                'desc' => 'Akses kelola kategori produk'
            ],
            'users' => [
                'label' => 'Users / Pengguna',
                'icon' => 'bi-people',
                'desc' => 'Akses kelola akun pengguna sistem'
            ],
            'roles' => [
                'label' => 'Roles & Hak Akses',
                'icon' => 'bi-shield-lock',
                'desc' => 'Akses kelola peran dan pengaturan hak akses'
            ],
        ];
    }

    // list semua jabatan/role user
    public function index()
    {
        $roles = Role::latest()->get();
        $availablePermissions = self::availablePermissions();
        return view('admin.role.index', compact('roles', 'availablePermissions'));
    }

    // form bikin role baru
    public function create()
    {
        $availablePermissions = self::availablePermissions();
        return view('admin.role.create', compact('availablePermissions'));
    }

    // simpen role baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string'
        ]);

        Role::create([
            'name' => $request->name,
            'permissions' => $request->input('permissions', [])
        ]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat dengan hak akses yang dipilih.');
    }

    public function show(string $id)
    {
        //
    }

    // form edit role & hak akses
    public function edit(Role $role)
    {
        $availablePermissions = self::availablePermissions();
        return view('admin.role.edit', compact('role', 'availablePermissions'));
    }

    // simpen perubahan role & hak akses
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'string'
        ]);

        $role->update([
            'name' => $request->name,
            'permissions' => $request->input('permissions', [])
        ]);

        return redirect()->route('roles.index')->with('success', 'Role & hak akses berhasil diperbarui.');
    }

    // hapus role
    public function destroy(Role $role)
    {
        $roleName = strtolower(trim($role->name));

        // 1. Proteksi: Role Admin/Administrator adalah role utama sistem dan tidak boleh dihapus
        if ($roleName === 'admin' || $roleName === 'administrator') {
            return redirect()->route('roles.index')->with('error', 'Role Admin adalah role utama sistem dan tidak dapat dihapus.');
        }

        // 2. Proteksi: Role Kasir minimal harus ada 1 di sistem
        if (in_array($roleName, ['kasir', 'cashier'])) {
            $kasirCount = Role::whereIn(\Illuminate\Support\Facades\DB::raw('LOWER(name)'), ['kasir', 'cashier'])->count();
            if ($kasirCount <= 1) {
                return redirect()->route('roles.index')->with('error', 'Role Kasir minimal harus tersisa 1 dan tidak dapat dihapus.');
            }
        }

        // 3. Proteksi: Role Pimpinan minimal harus ada 1 di sistem
        if (in_array($roleName, ['pimpinan', 'leader'])) {
            $pimpinanCount = Role::whereIn(\Illuminate\Support\Facades\DB::raw('LOWER(name)'), ['pimpinan', 'leader'])->count();
            if ($pimpinanCount <= 1) {
                return redirect()->route('roles.index')->with('error', 'Role Pimpinan minimal harus tersisa 1 dan tidak dapat dihapus.');
            }
        }

        // 4. Jangan hapus kalau masih ada user yang pegang role ini
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')->with('error', 'Tidak dapat menghapus role karena masih digunakan oleh user.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }
}
