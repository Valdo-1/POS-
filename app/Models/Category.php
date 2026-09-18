<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['category_name'];

    // satu kategori bisa nampung banyak produk
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
