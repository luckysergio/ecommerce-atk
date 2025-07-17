@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Transaksi Selesai</h2>

        @if (request('bulan') && request('tahun') && $transactions->isNotEmpty())
            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('admin.transaksi.by_status', 'selesai') }}" class="text-sm text-blue-600 hover:underline">←
                    Kembali ke Ringkasan Bulanan</a>

                <a href="{{ route('admin.transaksi.export_pdf', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-5 py-2 rounded-lg shadow-md transition duration-200">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($transactions as $trx)
                    <div class="bg-gradient-to-br from-[#2c3e50] to-[#3498db] text-white p-6 rounded-xl shadow-lg">
                        <div class="mb-3">
                            <h3 class="text-lg font-bold">ID: ATK-2025-{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}</h3>
                            <p class="text-sm text-white/70">Tanggal: {{ $trx->created_at->format('d M Y, H:i') }}</p>
                            <p class="text-sm">Customer: <span
                                    class="font-semibold">{{ $trx->customer->nama ?? '-' }}</span></p>
                        </div>

                        <ul class="text-sm space-y-2 mb-3">
                            @foreach ($trx->detailTransactions as $detail)
                                <li class="border-b border-white/30 pb-1">
                                    {{ $detail->product->nama ?? 'Produk dihapus' }} - {{ $detail->qty }} pcs <br>
                                    <span class="text-xs">Subtotal:
                                        Rp{{ number_format($detail->total_harga, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="text-right font-semibold text-lg">
                            Total: Rp{{ number_format($trx->total_harga, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif (request('bulan') && request('tahun'))
            <div class="text-center mb-4">
                <a href="{{ route('admin.transaksi.by_status', 'selesai') }}"
                    class="text-sm text-blue-600 hover:underline">← Kembali ke Ringkasan Bulanan</a>
            </div>
            <div class="bg-white text-center text-gray-500 p-6 rounded shadow">
                Tidak ada transaksi selesai untuk filter yang dipilih.
            </div>
        @elseif (!request('bulan') && !request('tahun') && !empty($allMonthlySummary))
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-6">
                @foreach ($allMonthlySummary as $month => $count)
                    <a href="{{ route('admin.transaksi.by_status', ['selesai']) }}?bulan={{ $month }}&tahun={{ $selectedYear }}"
                        class="block bg-gradient-to-br from-[#2c3e50] to-[#3498db] text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition">
                        <h4 class="text-xl font-bold">
                            {{ \Carbon\Carbon::create()->month($month)->locale('id')->translatedFormat('F') }}
                            {{ $selectedYear }}
                        </h4>
                        <p class="mt-2 text-white/80 text-sm">Total Order</p>
                        <p class="text-2xl font-semibold">{{ $count }}</p>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-white text-center text-gray-500 p-6 rounded shadow">
                Tidak ada data ringkasan transaksi.
            </div>
        @endif
    </div>
@endsection
