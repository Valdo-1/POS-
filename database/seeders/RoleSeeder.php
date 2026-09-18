<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    
    public function run(): void
    {
        // 3 role pokok sesuai kebutuhan POS resto: Admin, Kasir, dan Bos/Pimpinan
        Role::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'admin',
                'permissions' => ['pos', 'stock', 'reports', 'products', 'categories', 'users', 'roles']
            ]
        );
        Role::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'kasir',
                'permissions' => ['pos', 'stock']
            ]
        );
        Role::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'pimpinan',
                'permissions' => ['stock', 'reports']
            ]
        );
    }
}
