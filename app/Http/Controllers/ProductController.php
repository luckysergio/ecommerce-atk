<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Jenis;
use App\Models\Merk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['jenis', 'merk'])->latest();

        if ($request->filled('jenis')) {
            $query->where('id_jenis', $request->jenis);
        }

        if ($request->filled('merk')) {
            $query->where('id_merk', $request->merk);
        }

        $products = $query->paginate(10);

        $allJenis = Jenis::all();
        $allMerk = Merk::all();

        return view('pages.product.index', compact('products', 'allJenis', 'allMerk'));
    }

    public function create()
    {
        $jenis = Jenis::all();
        $merks = Merk::all();
        return view('pages.product.create', compact('jenis', 'merks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jenis'   => 'required|exists:jenis,id',
            'id_merk'    => 'required|exists:merks,id',
            'nama'       => 'required|string|max:255',
            'harga_beli' => 'required|string',
            'harga_jual' => 'required|string',
            'qty'        => 'required|integer|min:0',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'     => 'required|in:kosong,tersedia',
        ]);

        $hargaBeli = $this->parseRupiah($request->harga_beli);
        $hargaJual = $this->parseRupiah($request->harga_jual);

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('produk', 'public');
        }

        Product::create([
            'id_jenis'   => $request->id_jenis,
            'id_merk'    => $request->id_merk,
            'nama'       => $request->nama,
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaJual,
            'qty'        => $request->qty,
            'foto'       => $foto,
            'status'     => $request->status,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $jenis = Jenis::all();
        $merks = Merk::all();
        return view('pages.product.edit', compact('product', 'jenis', 'merks'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'id_jenis'   => 'required|exists:jenis,id',
            'id_merk'    => 'required|exists:merks,id',
            'nama'       => 'required|string|max:255',
            'harga_beli' => 'required|string',
            'harga_jual' => 'required|string',
            'qty'        => 'required|integer|min:0',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'     => 'required|in:kosong,tersedia', // ✅ Tambahan validasi
        ]);

        $hargaBeli = $this->parseRupiah($request->harga_beli);
        $hargaJual = $this->parseRupiah($request->harga_jual);

        if ($request->hasFile('foto')) {
            if ($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
            }

            $product->foto = $request->file('foto')->store('produk', 'public');
        }

        $product->update([
            'id_jenis'   => $request->id_jenis,
            'id_merk'    => $request->id_merk,
            'nama'       => $request->nama,
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaJual,
            'qty'        => $request->qty,
            'foto'       => $product->foto,
            'status'     => $request->status,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->foto && Storage::disk('public')->exists($product->foto)) {
            Storage::disk('public')->delete($product->foto);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }


    private function parseRupiah($value)
    {
        return (int) preg_replace('/\D/', '', $value);
    }
}
