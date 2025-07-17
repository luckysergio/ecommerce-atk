<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\DetailTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;


class ChekoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|max:2048',
        ]);

        $user = Auth::user();
        if (!$user || !$user->customer) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Silakan login sebagai customer.']);
            }
            return redirect()->route('login')->withErrors(['Silakan login sebagai customer.']);
        }

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return response()->json(['status' => 'error', 'message' => 'Keranjang kosong.']);
        }

        DB::beginTransaction();
        try {
            $totalHarga = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);
            $path = $request->file('bukti_pembayaran')->store('bukti', 'public');

            $transaksi = Transaction::create([
                'id_customer' => $user->customer->id,
                'status' => 'pending',
                'total_harga' => $totalHarga,
                'bukti_pembayaran' => $path,
            ]);

            foreach ($cart as $idProduct => $item) {
                $product = Product::findOrFail($idProduct);

                if ($product->qty < $item['qty']) {
                    throw new \Exception("Stok untuk {$product->nama} tidak mencukupi.");
                }

                DetailTransaction::create([
                    'id_order' => $transaksi->id,
                    'id_product' => $idProduct,
                    'qty' => $item['qty'],
                    'total_harga' => $item['harga'] * $item['qty'],
                    'catatan' => null,
                ]);

                $product->decrement('qty', $item['qty']);
            }

            Session::forget('cart');
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil!',
                'redirect' => route('pesanan.customer'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal melakukan checkout: ' . $e->getMessage(),
            ]);
        }
    }

    public function beliLangsung(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'catatan' => 'nullable|string',
        ]);

        $user = Auth::user();
        if (!$user || !$user->customer) {
            return response()->json(['status' => 'error', 'message' => 'Anda harus login sebagai customer.']);
        }

        $product = Product::findOrFail($request->product_id);
        if ($product->qty < $request->qty) {
            return response()->json(['status' => 'error', 'message' => 'Stok tidak mencukupi.']);
        }

        DB::beginTransaction();
        try {
            $total = $product->harga_jual * $request->qty;

            $buktiPath = $request->file('bukti_pembayaran')->store('bukti', 'public');

            $transaksi = Transaction::create([
                'id_customer' => $user->customer->id,
                'status' => 'pending',
                'total_harga' => $total,
                'bukti_pembayaran' => $buktiPath,
            ]);

            DetailTransaction::create([
                'id_order' => $transaksi->id,
                'id_product' => $product->id,
                'qty' => $request->qty,
                'total_harga' => $total,
                'catatan' => $request->catatan ?? null,
            ]);

            $product->decrement('qty', $request->qty);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil.',
                'redirect' => route('pesanan.customer')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }
}
