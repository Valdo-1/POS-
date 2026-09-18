<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_custom_gudang_role_can_only_access_stock_and_products()
    {
        // 1. Buat role Gudang hanya dengan izin stock dan products
        $gudangRole = Role::create([
            'name' => 'gudang',
            'permissions' => ['stock', 'products']
        ]);

        $gudangUser = User::create([
            'name' => 'Staf Gudang',
            'email' => 'gudang@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => $gudangRole->id
        ]);

        // 2. Login sebagai staf gudang
        $this->actingAs($gudangUser);

        // 3. Harus bisa akses stok dan produk
        $this->get(route('stock.index'))->assertStatus(200);
        $this->get(route('products.index'))->assertStatus(200);

        // 4. Tidak boleh akses transaksi POS kasir (403 Forbidden)
        $this->get(route('transactions.index'))->assertStatus(403);

        // 5. Tidak boleh akses Laporan Penjualan (403 Forbidden)
        $this->get(route('reports.index'))->assertStatus(403);

        // 6. Tidak boleh akses Kelola User (403 Forbidden)
        $this->get(route('users.index'))->assertStatus(403);
    }
}
