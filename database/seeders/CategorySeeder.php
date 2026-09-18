<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // contoh kategori awal buat resto kopi / cafe
        $categories = ['Coffe', 'NonCoffe', 'Tea', 'Pastry', 'Main Course', 'Snack'];
        foreach ($categories as $index => $name) {
            Category::updateOrCreate(['id' => $index + 1], ['category_name' => $name]);
        }
    }
}
