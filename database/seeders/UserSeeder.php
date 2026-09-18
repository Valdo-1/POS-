<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // siapin akun default buat ngetes: admin, kasir, dan pimpinan
        // pake updateOrCreate biar aman kalau seeder dirunning berkali-kali (idempotent)
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Administrator', 'password' => \Illuminate\Support\Facades\Hash::make('123'), 'role_id' => 1]
        );
        User::updateOrCreate(
            ['email' => 'kasir@gmail.com'],
            ['name' => 'Kasir', 'password' => \Illuminate\Support\Facades\Hash::make('123'), 'role_id' => 2]
        );
        User::updateOrCreate(
            ['email' => 'pimpinan@gmail.com'],
            ['name' => 'Pimpinan', 'password' => \Illuminate\Support\Facades\Hash::make('123'), 'role_id' => 3]
        );
    }
}
