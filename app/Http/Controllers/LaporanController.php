<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Jenis;

class LaporanController extends Controller
{
    public function produkTerjual(Request $request)
    {
        $jenisId = $request->query('jenis');

        $query = DB::table('products')
            ->join('detail_transactions', 'products.id', '=', 'detail_transactions.id_product')
            ->join('transactions', 'transactions.id', '=', 'detail_transactions.id_order')
            ->join('merks', 'products.id_merk', '=', 'merks.id')
            ->join('jenis', 'products.id_jenis', '=', 'jenis.id')
            ->select(
                'products.id',
                'products.nama',
                'merks.nama as merk',
                'products.harga_beli',
                'products.harga_jual',
                DB::raw('SUM(detail_transactions.qty) as total_terjual'),
                DB::raw('(SUM(detail_transactions.qty) * (products.harga_jual - products.harga_beli)) as keuntungan')
            )
            ->where('transactions.status', 'selesai')
            ->groupBy('products.id', 'products.nama', 'merks.nama', 'products.harga_jual', 'products.harga_beli');

        if ($jenisId) {
            $query->where('products.id_jenis', $jenisId);
        }

        $laporan = $query->get();
        $totalKeuntungan = $laporan->sum('keuntungan');
        $jenisList = Jenis::all();

        return view('pages.laporan.penjualan', compact('laporan', 'totalKeuntungan', 'jenisList'));
    }
}
