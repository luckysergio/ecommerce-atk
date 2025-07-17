<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Jenis;
use App\Models\Merk;
use Illuminate\Http\Request;

class CustomerProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['jenis', 'merk']);

        if ($request->filled('jenis')) {
            $query->where('id_jenis', $request->jenis);
        }

        if ($request->filled('merk')) {
            $query->where('id_merk', $request->merk);
        }

        $products = $query->where('status', 'tersedia')->latest()->paginate(12)->withQueryString();
        $allJenis = Jenis::all();
        $allMerk = Merk::all();

        return view('pages.customer.produk_customer', compact('products', 'allJenis', 'allMerk'));
    }
}
