<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $countPending = Transaction::where('status', 'pending')->count();
        $countProses = Transaction::where('status', 'proses')->count();
        $countSiap   = Transaction::where('status', 'siap diambil')->count();
        $countSelesai = Transaction::where('status', 'selesai')->count();

        return view('pages.dashboard', compact(
            'countPending',
            'countProses',
            'countSiap',
            'countSelesai'
        ));
    }

    public function showByStatus($status)
    {
        $allowedStatuses = ['pending', 'proses', 'siap diambil', 'selesai'];
        if (!in_array($status, $allowedStatuses)) {
            abort(404);
        }

        $transactions = Transaction::with('detailTransactions.product')
            ->where('status', $status)
            ->latest()
            ->get();

        return view('admin.transaksi_by_status', compact('transactions', 'status'));
    }
}
