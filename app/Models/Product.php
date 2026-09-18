<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'product_name',
        'product_photo',
        'product_price',
        'product_description',
        'is_active',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            // pastiin status aktif selalu terbaca boolean true/false di PHP
            'is_active' => 'boolean',
        ];
    }

    // produk nempel ke satu kategori (misal: Makanan, Minuman)
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // riwayat item penjualan yang nyantol ke produk ini
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }
}
