<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_code',
        'order_date',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total_price',
        'order_amount',
        'order_change',
        'order_status',
    ];

    protected function casts(): array
    {
        return [
            // otomatis ubah string tanggal jadi Carbon instance biar gampang diformat
            'order_date' => 'date',
        ];
    }

    // kasir atau user yang ngelayanin transaksi ini
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // rincian barang-barang yang dibeli dalam satu struk transaksi
    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}
