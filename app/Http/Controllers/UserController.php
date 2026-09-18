<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // list semua akun user di sistem
    public function index()
    {
        // load nama role sekalian biar ga kena n+1
        $users = User::with('role')->latest()->get();
        return view('admin.user.index', compact('users'));
    }

    // buka form tambah user baru
    public function create()
    {
        $roles = Role::all();
        return view('admin.user.create', compact('roles'));
    }

    // simpen user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            // wajib di-hash dulu biar ga kesimpen plain text
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    // form edit data profil & role user
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.user.edit', compact('user', 'roles'));
    }

    // simpen update akun user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ];

        // kalau password diisi berarti mau ganti password, kalau kosong ya biarin yang lama
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // hapus user
    public function destroy(User $user)
    {
        // 1. Proteksi minimal 1 user per role (Admin, Kasir, Pimpinan)
        if ($user->role) {
            $roleName = strtolower(trim($user->role->name));
            if (in_array($roleName, ['admin', 'administrator', 'kasir', 'cashier', 'pimpinan', 'leader'])) {
                $roleUserCount = User::where('role_id', $user->role_id)->count();
                if ($roleUserCount <= 1) {
                    $roleLabel = ucfirst($user->role->name);
                    return redirect()->route('users.index')->with('error', "User dengan role {$roleLabel} minimal harus tersisa 1 dan tidak dapat dihapus.");
                }
            }
        }

        // 2. jangan hapus kasir/user yang udah ada riwayat transaksi, nanti laporannya bolong
        $hasOrders = DB::table('orders')->where('user_id', $user->id)->exists();
        if ($hasOrders) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus user karena sudah memiliki riwayat transaksi.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
