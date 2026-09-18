<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan folder exist
        Storage::disk('public')->makeDirectory('products');

        $products = [
            // 1. Coffee (Category 1)
            [1, 'Espresso', 15000, 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?w=600&auto=format&fit=crop&q=80'],
            [1, 'Americano', 18000, 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600&auto=format&fit=crop&q=80'],
            [1, 'Caffe Latte', 22000, 'https://images.unsplash.com/photo-1534778101976-62847782c213?w=600&auto=format&fit=crop&q=80'],
            [1, 'Cappuccino', 22000, 'https://images.unsplash.com/photo-1572442388796-11668ba67e53?w=600&auto=format&fit=crop&q=80'],
            [1, 'Macchiato', 20000, 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?w=600&auto=format&fit=crop&q=80'],
            [1, 'Flat White', 24000, 'https://images.unsplash.com/photo-1577968897966-3d4325b36b61?w=600&auto=format&fit=crop&q=80'],
            [1, 'Mocha', 25000, 'https://images.unsplash.com/photo-1578314675249-a6910f80cc4e?w=600&auto=format&fit=crop&q=80'],
            [1, 'Affogato', 25000, 'https://images.unsplash.com/photo-1592663527359-cf6642f54cff?w=600&auto=format&fit=crop&q=80'],
            [1, 'Cold Brew', 20000, 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=600&auto=format&fit=crop&q=80'],
            [1, 'Vanilla Latte', 25000, 'https://images.unsplash.com/photo-1541167760496-1628856ab772?w=600&auto=format&fit=crop&q=80'],
            [1, 'Caramel Latte', 25000, 'https://images.unsplash.com/photo-1599390043813-f92576b50937?w=600&auto=format&fit=crop&q=80'],
            [1, 'Hazelnut Latte', 25000, 'https://images.unsplash.com/photo-1570968915860-54d5c301fa9f?w=600&auto=format&fit=crop&q=80'],
            [1, 'Irish Coffee', 30000, 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=600&auto=format&fit=crop&q=80'],
            [1, 'Piccolo', 20000, 'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?w=600&auto=format&fit=crop&q=80'],
            [1, 'Ristretto', 15000, 'https://images.unsplash.com/photo-1610889556528-9a770e32642f?w=600&auto=format&fit=crop&q=80'],
            
            // 2. Non-Coffee (Category 2)
            [2, 'Matcha Latte', 28000, 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=600&auto=format&fit=crop&q=80'],
            [2, 'Taro Latte', 25000, 'https://images.unsplash.com/photo-1558857563-b371033873b8?w=600&auto=format&fit=crop&q=80'],
            [2, 'Red Velvet Latte', 25000, 'https://images.unsplash.com/photo-1618160702438-9b02ab6515c9?w=600&auto=format&fit=crop&q=80'],
            [2, 'Hot Chocolate', 24000, 'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?w=600&auto=format&fit=crop&q=80'],
            [2, 'Iced Chocolate', 26000, 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=600&auto=format&fit=crop&q=80'],
            [2, 'Oreo Frappe', 30000, 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=600&auto=format&fit=crop&q=80'],
            [2, 'Vanilla Milkshake', 28000, 'https://images.unsplash.com/photo-1579954115545-a95591f28bfc?w=600&auto=format&fit=crop&q=80'],
            [2, 'Strawberry Milkshake', 28000, 'https://images.unsplash.com/photo-1553787499-6f9133860278?w=600&auto=format&fit=crop&q=80'],
            [2, 'Mango Smoothie', 32000, 'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?w=600&auto=format&fit=crop&q=80'],
            [2, 'Berry Smoothie', 32000, 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?w=600&auto=format&fit=crop&q=80'],
            
            // 3. Tea (Category 3)
            [3, 'Lemon Tea', 15000, 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=600&auto=format&fit=crop&q=80'],
            [3, 'Lychee Tea', 18000, 'https://images.unsplash.com/photo-1597481499750-3e6b22637e12?w=600&auto=format&fit=crop&q=80'],
            [3, 'Peach Tea', 18000, 'https://images.unsplash.com/photo-1595981267035-7b04ca84a82d?w=600&auto=format&fit=crop&q=80'],
            [3, 'Earl Grey', 20000, 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=600&auto=format&fit=crop&q=80'],
            [3, 'Chamomile Tea', 20000, 'https://images.unsplash.com/photo-1576092762791-dd9e2220abd4?w=600&auto=format&fit=crop&q=80'],
            [3, 'Peppermint Tea', 20000, 'https://images.unsplash.com/photo-1506084868230-bb9d95c24759?w=600&auto=format&fit=crop&q=80'],
            [3, 'English Breakfast', 20000, 'https://images.unsplash.com/photo-1564890369478-c89ca6d9cde9?w=600&auto=format&fit=crop&q=80'],
            
            // 4. Pastry (Category 4)
            [4, 'Butter Croissant', 18000, 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=600&auto=format&fit=crop&q=80'],
            [4, 'Almond Croissant', 25000, 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600&auto=format&fit=crop&q=80'],
            [4, 'Pain au Chocolat', 22000, 'https://images.unsplash.com/photo-1608198093002-ad4e005484ec?w=600&auto=format&fit=crop&q=80'],
            [4, 'Cinnamon Roll', 20000, 'https://images.unsplash.com/photo-1509365465985-25d11c17e812?w=600&auto=format&fit=crop&q=80'],
            [4, 'Cheese Danish', 22000, 'https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=600&auto=format&fit=crop&q=80'],
            [4, 'Blueberry Muffin', 18000, 'https://images.unsplash.com/photo-1607958996333-41aef7caefaa?w=600&auto=format&fit=crop&q=80'],
            [4, 'Chocolate Chip Cookie', 15000, 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?w=600&auto=format&fit=crop&q=80'],
            [4, 'Brownie', 20000, 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=600&auto=format&fit=crop&q=80'],
            
            // 5. Main Course (Category 5)
            [5, 'Classic Beef Burger', 45000, 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&auto=format&fit=crop&q=80'],
            [5, 'Chicken Sandwich', 35000, 'https://images.unsplash.com/photo-1521305916504-4a1121188589?w=600&auto=format&fit=crop&q=80'],
            [5, 'Spaghetti Carbonara', 40000, 'https://images.unsplash.com/photo-1612874742237-6526221588e3?w=600&auto=format&fit=crop&q=80'],
            [5, 'Spaghetti Bolognese', 40000, 'https://images.unsplash.com/photo-1621996346565-e3d5d6281288?w=600&auto=format&fit=crop&q=80'],
            [5, 'Fish and Chips', 45000, 'https://images.unsplash.com/photo-1579208030886-b937da0925dc?w=600&auto=format&fit=crop&q=80'],
            
            // 6. Snack (Category 6)
            [6, 'French Fries', 20000, 'https://images.unsplash.com/photo-1576107232684-1279f3908594?w=600&auto=format&fit=crop&q=80'],
            [6, 'Potato Wedges', 22000, 'https://images.unsplash.com/photo-1585109649139-366815a0d713?w=600&auto=format&fit=crop&q=80'],
            [6, 'Onion Rings', 20000, 'https://images.unsplash.com/photo-1639024471283-03518883512d?w=600&auto=format&fit=crop&q=80'],
            [6, 'Chicken Wings', 30000, 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?w=600&auto=format&fit=crop&q=80'],
            [6, 'Nachos', 25000, 'https://images.unsplash.com/photo-1513456852971-30c0b8199d4d?w=600&auto=format&fit=crop&q=80'],
        ];

        foreach ($products as $item) {
            $catId = $item[0];
            $name = $item[1];
            $price = $item[2];
            $imageUrl = $item[3];
            
            $slug = Str::slug($name);
            $filename = 'products/' . $slug . '.jpg';
            
            try {
                $response = Http::retry(3, 500)->timeout(20)->get($imageUrl);
                if ($response->successful()) {
                    Storage::disk('public')->put($filename, $response->body());
                }
            } catch (\Exception $e) {
                // Network error handled by fallback below
            }

            // Guaranteed fallback if network download failed
            if (!Storage::disk('public')->exists($filename)) {
                // 1x1 transparent PNG fallback byte string
                $pngFallback = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
                Storage::disk('public')->put($filename, $pngFallback);
            }

            Product::create([
                'category_id' => $catId,
                'product_name' => $name,
                'product_price' => $price,
                'product_description' => 'Delicious ' . $name,
                'is_active' => true,
                'stock' => 50,
                'product_photo' => $filename,
            ]);
        }
    }
}
