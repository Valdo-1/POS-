<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'order_price',
        'order_amount',
    ];

    // balik ke struk/transaksi utamanya
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // produk apa yang kebeli di baris transaksi ini
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
