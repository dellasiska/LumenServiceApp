<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    // Menampilkan semua produk
    public function index()
    {
        return response()->json([
            'message' => 'Daftar produk',
            'results' => Product::orderBy('id', 'DESC')->paginate(10)
        ], 200);
    }

    // Menambahkan produk
    public function store(Request $request)
    {
        $product = Product::create($request->only([
            'kode_produk',
            'nama_produk',
            'kategori',
            'deskripsi',
            'harga',
            'stok',
            'merek'
        ]));

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'results' => $product
        ], 201);
    }

    // Menampilkan detail produk
    public function show($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail produk',
            'results' => $product
        ], 200);
    }

    // Mengubah produk
    public function update(Request $request, $productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $product->update($request->only([
            'kode_produk',
            'nama_produk',
            'kategori',
            'deskripsi',
            'harga',
            'stok',
            'merek'
        ]));

        return response()->json([
            'message' => 'Produk berhasil diubah',
            'results' => $product
        ], 200);
    }

    // Menghapus produk
    public function destroy($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus'
        ], 200);
    }
}
