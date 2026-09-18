<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;

use Illuminate\Foundation\Testing\RefreshDatabase;

class PosSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // tes alur login tiap role: admin ke dashboard, kasir ke POS, bos ke laporan
    public function test_authentication_and_role_redirect()
    {
        // tes admin login
        $response = $this->post('/admin/action-login', [
            'email' => 'admin@gmail.com',
            'password' => '123',
        ]);
        $response->assertRedirect(route('dashboard'));

        // Test Kasir Login
        $response = $this->post('/admin/action-login', [
            'email' => 'kasir@gmail.com',
            'password' => '123',
        ]);
        $response->assertRedirect(route('transactions.index'));

        // Test Pimpinan Login
        $response = $this->post('/admin/action-login', [
            'email' => 'pimpinan@gmail.com',
            'password' => '123',
        ]);
        $response->assertRedirect(route('reports.index'));
    }

    // tes batasan hak akses: kasir dilarang otak-atik master data, bos ga boleh pegang kasir
    public function test_authorization_restrictions()
    {
        $kasir = User::where('email', 'kasir@gmail.com')->first();
        $pimpinan = User::where('email', 'pimpinan@gmail.com')->first();
        $admin = User::where('email', 'admin@gmail.com')->first();

        // kasir dilarang keras akses CRUD produk, user, kategori, dan role
        $this->actingAs($kasir)->get('/admin/products')->assertStatus(403);
        $this->actingAs($kasir)->get('/admin/users')->assertStatus(403);
        $this->actingAs($kasir)->get('/admin/categories')->assertStatus(403);
        $this->actingAs($kasir)->get('/admin/roles')->assertStatus(403);

        // 2. Kasir BOLEH akses transaksi dan monitoring stok
        $this->actingAs($kasir)->get('/admin/transactions')->assertStatus(200);
        $this->actingAs($kasir)->get('/admin/stock')->assertStatus(200);

        // 3. Pimpinan dilarang akses CRUD Product, User & Transaksi Kasir
        $this->actingAs($pimpinan)->get('/admin/products')->assertStatus(403);
        $this->actingAs($pimpinan)->get('/admin/users')->assertStatus(403);
        $this->actingAs($pimpinan)->get('/admin/transactions')->assertStatus(403);

        // 4. Pimpinan BOLEH akses monitoring stok dan laporan penjualan
        $this->actingAs($pimpinan)->get('/admin/stock')->assertStatus(200);
        $this->actingAs($pimpinan)->get('/admin/reports')->assertStatus(200);

        // 5. Admin BOLEH akses semua master data & laporan
        $this->actingAs($admin)->get('/admin/products')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/users')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/categories')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/roles')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/reports')->assertStatus(200);
    }

    // tes checkout berhasil: stok beneran kepotong dan invoice tersimpan
    public function test_transaction_execution_and_stock_deduction()
    {
        $kasir = User::where('email', 'kasir@gmail.com')->first();
        $category = Category::firstOrCreate(['category_name' => 'Minuman Resto']);
        
        $product = Product::create([
            'product_name' => 'Kopi Susu Gula Aren',
            'category_id' => $category->id,
            'product_price' => 15000,
            'stock' => 50,
            'is_active' => true,
        ]);

        $initialStock = $product->stock;
        $qtyToBuy = 2;
        $totalPrice = $product->product_price * $qtyToBuy;
        $paidAmount = 50000;

        $response = $this->actingAs($kasir)->post(route('transactions.store'), [
            'items' => [
                [
                    'product_id' => $product->id,
                    'qty' => $qtyToBuy,
                ]
            ],
            'paid_amount' => $paidAmount,
        ]);

        $response->assertSessionHas('success');

        // Verifikasi stok berkurang
        $product->refresh();
        $this->assertEquals($initialStock - $qtyToBuy, $product->stock);

        // Verifikasi Order tercatat di DB (termasuk pajak 11%)
        $taxAmount = round($totalPrice * 0.11);
        $expectedTotalWithTax = $totalPrice + $taxAmount;
        $order = Order::latest()->first();
        $this->assertEquals($expectedTotalWithTax, $order->order_amount);
        $this->assertEquals($paidAmount - $expectedTotalWithTax, $order->order_change);
        $this->assertEquals($kasir->id, $order->user_id);
    }

    // tes kalau kasir checkout barang melebihi stok: wajib gagal dan stok ga boleh berkurang
    public function test_transaction_fails_when_stock_insufficient()
    {
        $kasir = User::where('email', 'kasir@gmail.com')->first();
        $category = Category::first();
        
        $product = Product::create([
            'product_name' => 'Teh Manis Terbatas',
            'category_id' => $category->id,
            'product_price' => 5000,
            'stock' => 2,
            'is_active' => true,
        ]);

        $response = $this->actingAs($kasir)->post(route('transactions.store'), [
            'items' => [
                [
                    'product_id' => $product->id,
                    'qty' => 5, // melebihi stok 2
                ]
            ],
            'paid_amount' => 50000,
        ]);

        $response->assertSessionHas('error');

        // Pastikan stok tidak berubah
        $product->refresh();
        $this->assertEquals(2, $product->stock);
    }

    public function test_crud_category_product_user()
    {
        $admin = User::where('email', 'admin@gmail.com')->first();

        // 1. CRUD Category
        $catResponse = $this->actingAs($admin)->post('/admin/categories', [
            'category_name' => 'Makanan Ringan',
        ]);
        $catResponse->assertRedirect(route('categories.index'));
        $category = Category::where('category_name', 'Makanan Ringan')->first();
        $this->assertNotNull($category);

        // Update Category
        $this->actingAs($admin)->put("/admin/categories/{$category->id}", [
            'category_name' => 'Snack & Camilan',
        ])->assertRedirect(route('categories.index'));
        $category->refresh();
        $this->assertEquals('Snack & Camilan', $category->category_name);

        // 2. CRUD Product
        $prodResponse = $this->actingAs($admin)->post('/admin/products', [
            'product_name' => 'Kentang Goreng Krispi',
            'category_id' => $category->id,
            'product_price' => 12000,
            'stock' => 30,
            'is_active' => 1,
        ]);
        $prodResponse->assertRedirect(route('products.index'));
        $product = Product::where('product_name', 'Kentang Goreng Krispi')->first();
        $this->assertNotNull($product);

        // Update Product
        $this->actingAs($admin)->put("/admin/products/{$product->id}", [
            'product_name' => 'French Fries Premium',
            'category_id' => $category->id,
            'product_price' => 15000,
            'stock' => 25,
            'is_active' => 1,
        ])->assertRedirect(route('products.index'));
        $product->refresh();
        $this->assertEquals('French Fries Premium', $product->product_name);

        // Delete Product
        $this->actingAs($admin)->delete("/admin/products/{$product->id}")
            ->assertRedirect(route('products.index'));
        $this->assertNull(Product::find($product->id));

        // Delete Category
        $this->actingAs($admin)->delete("/admin/categories/{$category->id}")
            ->assertRedirect(route('categories.index'));
        $this->assertNull(Category::find($category->id));

        // 3. CRUD User
        $userResponse = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Kasir Baru',
            'email' => 'kasirbaru@gmail.com',
            'password' => 'secret123',
            'role_id' => 2,
        ]);
        $userResponse->assertRedirect(route('users.index'));
        $newUser = User::where('email', 'kasirbaru@gmail.com')->first();
        $this->assertNotNull($newUser);

        // Update User
        $this->actingAs($admin)->put("/admin/users/{$newUser->id}", [
            'name' => 'Kasir Utama Resto',
            'email' => 'kasirbaru@gmail.com',
            'role_id' => 2,
        ])->assertRedirect(route('users.index'));
        $newUser->refresh();
        $this->assertEquals('Kasir Utama Resto', $newUser->name);

        // Delete User
        $this->actingAs($admin)->delete("/admin/users/{$newUser->id}")
            ->assertRedirect(route('users.index'));
        $this->assertNull(User::find($newUser->id));
    }

    public function test_reports_daily_weekly_monthly()
    {
        $pimpinan = User::where('email', 'pimpinan@gmail.com')->first();

        // Laporan Harian
        $this->actingAs($pimpinan)->get('/admin/reports?period=daily&date=' . date('Y-m-d'))
            ->assertStatus(200)
            ->assertSee('Laporan Harian')
            ->assertSee('Jumlah Transaksi');

        // Laporan Mingguan
        $this->actingAs($pimpinan)->get('/admin/reports?period=weekly')
            ->assertStatus(200)
            ->assertSee('Laporan Mingguan')
            ->assertSee('Total Omzet');

        // Laporan Bulanan
        $this->actingAs($pimpinan)->get('/admin/reports?period=monthly&month=' . date('m') . '&year=' . date('Y'))
            ->assertStatus(200)
            ->assertSee('Laporan Bulanan');
    }

    public function test_crud_role()
    {
        $admin = User::where('email', 'admin@gmail.com')->first();

        // 1. Create Role
        $response = $this->actingAs($admin)->post('/admin/roles', [
            'name' => 'Supervisor',
        ]);
        $response->assertRedirect(route('roles.index'));
        $role = Role::where('name', 'Supervisor')->first();
        $this->assertNotNull($role);

        // 2. Update Role
        $this->actingAs($admin)->put("/admin/roles/{$role->id}", [
            'name' => 'Supervisor Resto',
        ])->assertRedirect(route('roles.index'));
        $role->refresh();
        $this->assertEquals('Supervisor Resto', $role->name);

        // 3. Delete Role
        $this->actingAs($admin)->delete("/admin/roles/{$role->id}")
            ->assertRedirect(route('roles.index'));
        $this->assertNull(Role::find($role->id));
    }

    public function test_stock_monitoring_and_filters()
    {
        $pimpinan = User::where('email', 'pimpinan@gmail.com')->first();
        $kasir = User::where('email', 'kasir@gmail.com')->first();

        // Akses oleh Pimpinan
        $responsePimpinan = $this->actingAs($pimpinan)->get('/admin/stock?status=low');
        $responsePimpinan->assertStatus(200);
        $responsePimpinan->assertSee('Monitoring Stok Produk');

        // Akses oleh Kasir
        $responseKasir = $this->actingAs($kasir)->get('/admin/stock?status=available');
        $responseKasir->assertStatus(200);
        $responseKasir->assertSee('Monitoring Stok Produk');
    }

    public function test_pos_catalog_filters()
    {
        $kasir = User::where('email', 'kasir@gmail.com')->first();

        $response = $this->actingAs($kasir)->get('/admin/transactions?search=Kopi');
        $response->assertStatus(200);
        $response->assertSee('Point of Sales');
    }
}
