<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function pesanan(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return redirect()->route('login')->withErrors(['Silakan login sebagai customer.']);
        }

        $query = Transaction::with('detailTransactions.product')
            ->where('id_customer', $user->customer->id);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $transactions = $query
            ->orderByRaw("FIELD(status, 'siap diambil', 'proses', 'pending', 'selesai', 'batal')")
            ->latest()
            ->get();

        return view('pages.customer.pesanan', compact('transactions'));
    }

    public function byStatus(Request $request, $status)
    {
        $allowedStatuses = ['pending', 'proses', 'siap diambil', 'selesai'];
        if (!in_array($status, $allowedStatuses)) {
            abort(404);
        }

        $viewName = $status === 'siap diambil' ? 'siap-diambil' : $status;

        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

        $query = Transaction::with('detailTransactions.product', 'customer')
            ->where('status', $status);

        if ($bulan && $tahun) {
            $query->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);
        }

        $transactions = $query->latest()->get();

        return view("pages.transaksi.$viewName", compact('transactions', 'status', 'bulan', 'tahun'));
    }

    public function updateStatus($id, $status)
    {
        $allowed = ['pending', 'proses', 'siap diambil', 'selesai', 'batal'];
        if (!in_array($status, $allowed)) {
            abort(400, 'Status tidak valid.');
        }

        $transaksi = Transaction::with('detailTransactions.product')->findOrFail($id);

        if ($status === 'batal') {
            foreach ($transaksi->detailTransactions as $detail) {
                if ($detail->product) {
                    $detail->product->qty += $detail->qty;
                    $detail->product->save();
                }
            }
        }

        $transaksi->update(['status' => $status]);

        return back()->with('success', 'Status transaksi diperbarui.');
    }

    public function exportSelesaiPdf(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $query = Transaction::with('detailTransactions.product', 'customer')
            ->where('status', 'selesai');

        if ($bulan && $tahun) {
            $query->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);
        }

        $transactions = $query->get();

        $pdf = Pdf::loadView('exports.transaksi_selesai', compact('transactions', 'bulan', 'tahun'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-transaksi-selesai.pdf');
    }

    public function DownloadInvoice($id)
    {
        $user = Auth::user();

        $transaksi = Transaction::with(['detailTransactions.product', 'customer.user'])->findOrFail($id);

        if (!$user || !$user->customer || $transaksi->id_customer !== $user->customer->id) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }

        $pdf = Pdf::loadView('invoice.template', compact('transaksi'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Invoice_ATK_2025_' . str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function batalCustomer($id)
    {
        $user = Auth::user();

        $transaksi = Transaction::where('id', $id)
            ->where('id_customer', optional($user->customer)->id)
            ->whereIn('status', ['pending', 'proses'])
            ->firstOrFail();

        $transaksi->update(['status' => 'batal']);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
