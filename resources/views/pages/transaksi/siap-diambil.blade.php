@extends('layouts.app')

@section('content')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                confirmButtonColor: '#2563eb',
            });
        </script>
    @endif

    {{-- @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                confirmButtonColor: '#2563eb',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/transaksi/status/selesai';
                }
            });
        </script>
    @endif --}}

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

    <div class="max-w-7xl mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Transaksi Siap Diambil</h2>

        @if ($transactions->isEmpty())
            <div class="bg-white p-6 rounded shadow text-center text-gray-500">
                Tidak ada transaksi yang siap diambil.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($transactions as $transaksi)
                    <div class="bg-gradient-to-br from-[#2c3e50] to-[#3498db] text-white p-6 rounded-xl shadow-lg">
                        <div class="mb-3">
                            <h3 class="text-lg font-bold">ID: ATK-2025-{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}
                            </h3>
                            <p class="text-sm text-white/70">Tanggal: {{ $transaksi->created_at->format('d M Y') }}</p>
                            <p class="text-sm">
                                <span class="font-semibold">
                                    {{ $transaksi->customer ? $transaksi->customer->nama . ' - ' . $transaksi->customer->no_hp : '-' }}
                                </span>
                            </p>
                        </div>

                        <ul class="text-sm space-y-2 mb-3">
                            @foreach ($transaksi->detailTransactions as $detail)
                                <li class="border-b border-white/30 pb-1">
                                    {{ $detail->product->nama ?? 'Produk dihapus' }} - {{ $detail->qty }} pcs
                                    <br>
                                    <span class="text-xs">Subtotal:
                                        Rp{{ number_format($detail->total_harga, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="text-right font-semibold text-lg mb-4">
                            Total: Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}
                        </div>

                        @auth
                            @if (auth()->user()->role->nama === 'Admin')
                                <div class="flex justify-end">
                                    <button
                                        onclick="confirmStatus('{{ route('admin.transaksi.updateStatus', [$transaksi->id, 'selesai']) }}', 'Selesai')"
                                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                                        Konfirmasi pembayaran
                                    </button>
                                </div>
                            @endif
                        @endauth
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmStatus(url, action) {
            Swal.fire({
                title: `${action}?`,
                text: `Apakah kamu yakin ingin mengubah status transaksi menjadi "${action}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal'
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
