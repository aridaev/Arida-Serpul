<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use App\Models\Produk;
use App\Models\Setting;
use App\Services\DigiflazzService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProdukController extends Controller
{
    // ─── Kategori ───

    public function kategoris()
    {
        $kategoris = KategoriProduk::withCount('produks')->orderBy('nama')->get();
        return view('admin.kategoris', compact('kategoris'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'icon' => 'nullable|string|max:10',
        ]);

        KategoriProduk::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'icon' => $request->icon,
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function deleteKategori(KategoriProduk $kategori)
    {
        if ($kategori->produks()->count() > 0) {
            return back()->with('error', 'Kategori masih memiliki produk, hapus produk terlebih dahulu.');
        }
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    // ─── Produk ───

    public function produks(Request $request)
    {
        $query = Produk::with(['kategori', 'provider']);

        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }
        if ($request->search) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }
        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $produks = $query->orderBy('kategori_id')->orderBy('harga_jual_idr')->paginate(50);
        $kategoris = KategoriProduk::orderBy('nama')->get();

        return view('admin.produks', compact('produks', 'kategoris'));
    }

    public function createProduk()
    {
        $kategoris = KategoriProduk::orderBy('nama')->get();
        return view('admin.produk-form', ['produk' => null, 'kategoris' => $kategoris]);
    }

    public function storeProduk(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_produks,id',
            'nama_produk' => 'required|string|max:150',
            'kode_provider' => 'required|string|max:50',
            'brand' => 'nullable|string|max:50',
            'harga_beli_idr' => 'required|numeric|min:0',
            'harga_jual_idr' => 'required|numeric|min:0',
            'status' => 'required|boolean',
        ]);

        Produk::create([
            'kategori_id' => $request->kategori_id,
            'nama_produk' => $request->nama_produk,
            'slug' => Str::slug($request->nama_produk),
            'kode_provider' => $request->kode_provider,
            'brand' => $request->brand,
            'harga_beli_idr' => $request->harga_beli_idr,
            'harga_jual_idr' => $request->harga_jual_idr,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.produks')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function editProduk(Produk $produk)
    {
        $kategoris = KategoriProduk::orderBy('nama')->get();
        return view('admin.produk-form', compact('produk', 'kategoris'));
    }

    public function updateProduk(Request $request, Produk $produk)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_produks,id',
            'nama_produk' => 'required|string|max:150',
            'kode_provider' => 'required|string|max:50',
            'brand' => 'nullable|string|max:50',
            'harga_beli_idr' => 'required|numeric|min:0',
            'harga_jual_idr' => 'required|numeric|min:0',
            'status' => 'required|boolean',
        ]);

        $produk->update([
            'kategori_id' => $request->kategori_id,
            'nama_produk' => $request->nama_produk,
            'slug' => Str::slug($request->nama_produk),
            'kode_provider' => $request->kode_provider,
            'brand' => $request->brand,
            'harga_beli_idr' => $request->harga_beli_idr,
            'harga_jual_idr' => $request->harga_jual_idr,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.produks')->with('success', 'Produk berhasil diupdate.');
    }

    public function deleteProduk(Produk $produk)
    {
        $produk->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleProduk(Produk $produk)
    {
        $produk->update(['status' => !$produk->status]);
        return back()->with('success', 'Status produk berhasil diubah.');
    }

    // ─── DigiFlazz Sync ───

    public function syncDigiflazz()
    {
        $digiflazz = app(DigiflazzService::class);

        if (!$digiflazz->isConfigured()) {
            return back()->with('error', 'DigiFlazz belum dikonfigurasi. Isi username & API key di Settings.');
        }

        $priceList = $digiflazz->getPriceList('prepaid');

        if (empty($priceList)) {
            return back()->with('error', 'Gagal mengambil data dari DigiFlazz. Cek koneksi dan credentials.');
        }

        $profitMode = Setting::get('profit_mode', 'percent');
        $profitPercent = (float) Setting::get('profit_margin_percent', '5');
        $profitFlat = (float) Setting::get('profit_margin_flat', '500');

        $categoryMap = [
            'Pulsa' => 'Pulsa',
            'Data' => 'Paket Data',
            'PLN' => 'Token PLN',
            'Games' => 'Game',
            'E-Money' => 'E-Wallet',
            'Voucher' => 'Voucher',
        ];

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($priceList as $item) {
            if (!($item['buyer_product_status'] ?? true)) {
                $skipped++;
                continue;
            }

            $categoryName = $categoryMap[$item['category'] ?? ''] ?? ($item['category'] ?? 'Lainnya');
            $kategori = KategoriProduk::firstOrCreate(
                ['nama' => $categoryName],
                ['slug' => Str::slug($categoryName)]
            );

            $hargaBeli = (float) ($item['price'] ?? $item['seller_price'] ?? 0);
            if ($hargaBeli <= 0) {
                $skipped++;
                continue;
            }

            $hargaJual = $profitMode === 'percent'
                ? ceil($hargaBeli * (1 + $profitPercent / 100))
                : $hargaBeli + $profitFlat;

            $existing = Produk::where('kode_provider', $item['buyer_sku_code'] ?? '')->first();

            if ($existing) {
                $existing->update([
                    'harga_beli_idr' => $hargaBeli,
                    'harga_jual_idr' => $hargaJual,
                    'nama_produk' => $item['product_name'] ?? $existing->nama_produk,
                    'brand' => $item['brand'] ?? $existing->brand,
                    'status' => ($item['buyer_product_status'] ?? true) && ($item['seller_product_status'] ?? true),
                ]);
                $updated++;
            } else {
                $namaP = $item['product_name'] ?? $item['buyer_sku_code'] ?? 'Unknown';
                Produk::create([
                    'kategori_id' => $kategori->id,
                    'kode_provider' => $item['buyer_sku_code'] ?? '',
                    'nama_produk' => $namaP,
                    'slug' => Str::slug($namaP . '-' . Str::random(4)),
                    'brand' => $item['brand'] ?? null,
                    'harga_beli_idr' => $hargaBeli,
                    'harga_jual_idr' => $hargaJual,
                    'status' => ($item['buyer_product_status'] ?? true) && ($item['seller_product_status'] ?? true),
                ]);
                $created++;
            }
        }

        return back()->with('success', "Sync DigiFlazz selesai: {$created} baru, {$updated} diupdate, {$skipped} dilewati.");
    }

    public function digiflazzBalance()
    {
        $digiflazz = app(DigiflazzService::class);

        if (!$digiflazz->isConfigured()) {
            return response()->json(['error' => 'DigiFlazz belum dikonfigurasi'], 400);
        }

        $balance = $digiflazz->checkBalance();
        return response()->json($balance);
    }
}
