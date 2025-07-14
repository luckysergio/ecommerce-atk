@extends('layouts.customer')

@section('content')
    <div class="max-w-5xl mx-auto py-8 px-4" data-aos="fade-up">
        @if (empty($cart) || count($cart) === 0)
            <div class="bg-white p-6 rounded shadow text-center text-gray-500">
                Keranjang kamu kosong.
                <br>
                <a href="{{ route('produk.customer') }}" class="text-blue-600 hover:underline">Belanja sekarang</a>
            </div>
        @else
            <div class="bg-white rounded-xl shadow overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-600 uppercase tracking-wide">
                        <tr>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3 text-center">Harga</th>
                            <th class="px-4 py-3 text-center">Jumlah</th>
                            <th class="px-4 py-3 text-center">Subtotal</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-gray-700" id="cart-body">
                        @php $total = 0; @endphp
                        @foreach ($cart as $id => $item)
                            @php
                                $subtotal = $item['harga'] * $item['qty'];
                                $total += $subtotal;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-4 flex items-center gap-3">
                                    @if ($item['foto'])
                                        <img src="{{ asset('storage/' . $item['foto']) }}" alt="{{ $item['nama'] }}"
                                            class="w-12 h-12 object-cover rounded shadow">
                                    @endif
                                    <div>
                                        <div class="font-medium">{{ $item['nama'] }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="harga" data-harga="{{ $item['harga'] }}" data-id="{{ $id }}">
                                        Rp{{ number_format($item['harga'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <input type="number" name="qty[{{ $id }}]" min="1"
                                        value="{{ $item['qty'] }}" class="qty border rounded w-16 text-center bg-gray-50"
                                        data-id="{{ $id }}" data-nama="{{ $item['nama'] }}">
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="subtotal font-semibold" id="subtotal-{{ $id }}">
                                        Rp{{ number_format($subtotal, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <button onclick="confirmHapus('{{ route('keranjang.hapus', $id) }}')"
                                        class="text-red-500 hover:text-red-700">🗑️</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50 font-bold text-blue-700">
                            <td colspan="3" class="px-4 py-3 text-right">Total:</td>
                            <td colspan="2" class="px-4 py-3 text-left" id="grand-total">
                                Rp{{ number_format($total, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4">
                <a href="{{ route('produk.customer') }}" class="text-blue-600 hover:underline text-sm">← Lanjut Belanja</a>
                <button onclick="checkoutConfirm()"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded shadow transition">
                    Checkout
                </button>
            </div>

            <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST" class="hidden">
                @csrf
            </form>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        const formatter = new Intl.NumberFormat('id-ID');

        document.querySelectorAll('input.qty').forEach(input => {
            input.addEventListener('change', function() {
                const id = this.dataset.id;
                const qty = this.value;

                fetch("{{ route('keranjang.update.ajax') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id,
                            qty
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`subtotal-${id}`).textContent = 'Rp' + formatter
                                .format(data.subtotal);
                            document.getElementById('grand-total').textContent = 'Rp' + formatter
                                .format(data.grand_total);
                        }
                    });
            });
        });

        function confirmHapus(url) {
            Swal.fire({
                title: 'Hapus Produk?',
                text: 'Produk ini akan dihapus dari keranjang.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function checkoutConfirm() {
            const inputs = document.querySelectorAll('input.qty');
            let detail = '';
            let total = 0;

            inputs.forEach(input => {
                const id = input.dataset.id;
                const nama = input.dataset.nama;
                const qty = parseInt(input.value);
                const harga = parseInt(document.querySelector(`.harga[data-id="${id}"]`)?.dataset.harga ?? 0);
                const subtotal = qty * harga;
                total += subtotal;
                detail += `${nama} (${qty}x) = Rp${formatter.format(subtotal)}<br>`;
            });

            Swal.fire({
                title: 'Konfirmasi Checkout',
                html: `
                <p class="mb-2">Apakah kamu yakin ingin melanjutkan pembelian ini?</p>
                <div class="text-left font-medium">${detail}</div>
                <div class="mt-2 font-bold text-blue-600">Total: Rp${formatter.format(total)}</div>
            `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Checkout!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById('checkout-form').submit();
                }
            });
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                timer: 2000,
                showConfirmButton: false
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: @json($errors->first()),
            });
        @endif
    </script>
@endpush
