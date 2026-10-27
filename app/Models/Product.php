<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'kategori',
        'deskripsi',
        'harga',
        'stok',
        'merek'
    ];
}
