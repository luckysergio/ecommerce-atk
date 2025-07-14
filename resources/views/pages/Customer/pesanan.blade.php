@extends('layouts.customer')

@section('content')
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    confirmButtonColor: '#2563eb',
                    timer: 2500,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#e3342f',
            });
        </script>
    @endif

    <div class="max-w-5xl mx-auto py-8 px-4" data-aos="fade-up">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Pesanan Saya</h2>

        {{-- Filter Status --}}
        <div class="flex flex-wrap gap-2 mb-6">
            @php
                $statuses = [
                    'all' => 'Semua',
                    'pending' => 'Pending',
                    'proses' => 'Proses',
                    'siap diambil' => 'Siap Diambil',
                    'selesai' => 'Selesai',
                    'batal' => 'Batal',
                ];
                $currentStatus = request('status');
            @endphp

            @foreach ($statuses as $key => $label)
                <a href="{{ route('pesanan.customer', ['status' => $key]) }}"
                    class="px-4 py-2 rounded-full text-sm shadow transition
                        {{ $currentStatus === $key || ($currentStatus === null && $key === 'all')
                            ? 'bg-blue-600 text-white'
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Daftar Pesanan --}}
        @if ($transactions->isEmpty())
            <div class="bg-white p-6 rounded shadow text-center text-gray-500">
                Tidak ada pesanan untuk status ini.
                <br>
                <a href="{{ route('produk.customer') }}" class="text-blue-600 hover:underline">Belanja sekarang</a>
            </div>
        @else
            @foreach ($transactions as $transaksi)
                <div class="bg-white shadow rounded-xl mb-6">
                    <div class="px-6 py-4 border-b flex justify-between items-center flex-wrap gap-2">
                        <div>
                            <p class="text-gray-700 font-semibold">
                                ID Pesanan:
                                <span class="text-blue-600">
                                    ATK-2025-{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </p>
                            <p class="text-sm text-gray-500">
                                Tanggal: {{ $transaksi->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span
                                class="text-sm px-3 py-1 rounded-full
                                {{ $transaksi->status === 'pending'
                                    ? 'bg-yellow-100 text-yellow-800'
                                    : ($transaksi->status === 'proses'
                                        ? 'bg-blue-100 text-blue-800'
                                        : ($transaksi->status === 'siap diambil'
                                            ? 'bg-purple-100 text-purple-800'
                                            : ($transaksi->status === 'selesai'
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-red-100 text-red-800'))) }}">
                                {{ ucfirst($transaksi->status) }}
                            </span>

                            @if (in_array($transaksi->status, ['proses', 'siap ambil', 'selesai']))
                                <a href="{{ route('invoice.download', $transaksi->id) }}"
                                    class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded shadow transition">
                                    Download Invoice
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        <table class="w-full text-sm text-left">
                            <thead class="text-gray-600 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2">Produk</th>
                                    <th class="px-4 py-2 text-center">Qty</th>
                                    <th class="px-4 py-2 text-center">Subtotal</th>
                                    <th class="px-4 py-2">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y text-gray-700">
                                @foreach ($transaksi->detailTransactions as $detail)
                                    <tr>
                                        <td class="px-4 py-2">
                                            {{ $detail->product->nama ?? 'Produk dihapus' }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $detail->qty }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            Rp{{ number_format($detail->total_harga, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ $detail->catatan ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-right mt-4 font-bold text-blue-700">
                            Total: Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}
                        </div>

                        @if ($transaksi->status === 'siap diambil')
                            <div class="mt-4 text-sm text-gray-600 italic text-center">
                                🛈 Tunjukkan invoice ini saat mengambil barang.
                            </div>
                        @endif

                        @if (in_array($transaksi->status, ['pending', 'proses']))
                            <div class="mt-4 text-right">
                                <button onclick="batalkanPesanan('{{ route('pesanan.customer.batal', $transaksi->id) }}')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                                    Batalkan Pesanan
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function batalkanPesanan(url) {
            Swal.fire({
                title: 'Batalkan Pesanan?',
                text: "Apakah kamu yakin ingin membatalkan pesanan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'PATCH';

                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
