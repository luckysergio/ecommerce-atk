@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Transaksi Selesai</h2>

        <form method="GET" action="{{ route('admin.transaksi.by_status', 'selesai') }}"
            class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div class="flex flex-col md:flex-row gap-4 w-full lg:w-auto">
                <select name="bulan" class="border rounded p-2 w-full md:w-auto" onchange="this.form.submit()">
                    <option value="">Pilih Bulan</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month((int) $m)->locale('id')->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>

                <select name="tahun" class="border rounded p-2 w-full md:w-auto" onchange="this.form.submit()">
                    <option value="">Pilih Tahun</option>
                    @for ($year = 2025; $year <= 2030; $year++)
                        <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </div>

            @if (request('bulan') && request('tahun') && $transactions->isNotEmpty())
                <a href="{{ route('admin.transaksi.export_pdf', ['bulan' => request('bulan'), 'tahun' => request('tahun')]) }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-5 py-2 rounded-lg shadow-md transition duration-200">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            @endif
        </form>

        @if (request('bulan') && request('tahun') && $transactions->isNotEmpty())
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
            <div class="bg-white text-center text-gray-500 p-6 rounded shadow">
                Tidak ada transaksi selesai untuk filter yang dipilih.
            </div>
        @else
            <div class="bg-white text-center text-gray-500 p-6 rounded shadow">
                Silakan pilih bulan dan tahun terlebih dahulu.
            </div>
        @endif
    </div>
@endsection
