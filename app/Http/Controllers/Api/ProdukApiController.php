<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukApiController extends Controller
{
    public function kategoris()
    {
        $kategoris = KategoriProduk::all();
        return response()->json(['data' => $kategoris]);
    }

    public function byKategori(int $kategoriId)
    {
        $produks = Produk::where('kategori_id', $kategoriId)
            ->where('status', true)
            ->with('kategori')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nama_produk' => $p->nama_produk,
                    'kategori' => $p->kategori->nama,
                    'harga_jual_idr' => $p->harga_jual_idr,
                ];
            });

        return response()->json(['data' => $produks]);
    }
}
