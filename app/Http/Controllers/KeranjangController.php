<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class KeranjangController extends Controller
{
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);
        return view('pages.customer.keranjang', compact('cart'));
    }

    public function tambah(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::findOrFail($productId);

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] += 1;
        } else {
            $cart[$productId] = [
                'nama' => $product->nama,
                'harga' => $product->harga_jual,
                'foto' => $product->foto,
                'qty' => 1,
            ];
        }

        session(['cart' => $cart]);

        return response()->json([
            'status' => 'success',
            'message' => 'Produk ditambahkan ke keranjang',
            'product_id' => $productId,
            'qty' => $cart[$productId]['qty'],
        ]);
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        foreach ($request->qty as $id => $jumlah) {
            if (isset($cart[$id])) {
                $cart[$id]['qty'] = (int) $jumlah;
            }
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function updateAjax(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->input('id');
        $qty = (int) $request->input('qty');

        if (isset($cart[$id])) {
            $cart[$id]['qty'] = $qty;
            session()->put('cart', $cart);

            $subtotal = $cart[$id]['qty'] * $cart[$id]['harga'];
            $grandTotal = array_reduce($cart, function ($total, $item) {
                return $total + ($item['qty'] * $item['harga']);
            }, 0);

            return response()->json([
                'success' => true,
                'subtotal' => $subtotal,
                'grand_total' => $grandTotal,
            ]);
        }

        return response()->json(['success' => false], 400);
    }

    
}
