@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Laporan Penjualan Produk</h2>

        <div class="flex justify-center mb-6">
            <form method="GET" action="{{ route('admin.laporan.penjualan') }}">
                <select name="jenis" class="border rounded px-4 py-2 w-64" onchange="this.form.submit()">
                    <option value="">-- Semua Jenis --</option>
                    @foreach ($jenisList as $jenis)
                        <option value="{{ $jenis->id }}" {{ request('jenis') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->nama }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if ($laporan->isEmpty())
            <div class="bg-white p-6 rounded shadow text-center text-gray-500">
                Belum ada data penjualan.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($laporan as $row)
                    <div class="bg-gradient-to-br from-[#2c3e50] to-[#3498db] text-white p-6 rounded-xl shadow-lg">
                        <div class="mb-3">
                            <h3 class="text-lg font-bold">
                                {{ $row->nama }} - <span class="text-white/80">{{ $row->merk }}</span>
                            </h3>
                            <p class="text-sm text-white/70">Jumlah Terjual: {{ $row->total_terjual }} pcs</p>
                        </div>

                        <ul class="text-sm space-y-2 mb-3">
                            <li class="border-b border-white/30 pb-1">
                                Harga Beli:
                                <span class="font-semibold">Rp{{ number_format($row->harga_beli, 0, ',', '.') }}</span>
                            </li>
                            <li class="border-b border-white/30 pb-1">
                                Harga Jual:
                                <span class="font-semibold">Rp{{ number_format($row->harga_jual, 0, ',', '.') }}</span>
                            </li>
                            <li class="pt-1">
                                Keuntungan:
                                <span class="font-bold text-green-300">
                                    Rp{{ number_format($row->keuntungan, 0, ',', '.') }}
                                </span>
                            </li>
                        </ul>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 bg-white p-4 rounded shadow text-right text-lg font-semibold text-green-700">
                Total Keuntungan: Rp{{ number_format($totalKeuntungan, 0, ',', '.') }}
            </div>
        @endif
    </div>
@endsection
