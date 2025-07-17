@extends('layouts.customer')

@section('content')
    <script>
        const isLoggedIn = @json(auth()->check());
    </script>

    <h2 class="text-xl font-bold mb-4">Produk ATK</h2>

    <form method="GET" id="filter-form" class="mb-6 flex flex-wrap justify-center gap-4 text-center">
        <select name="jenis" onchange="this.form.submit()"
            class="border rounded px-3 py-2 w-48 focus:outline-none focus:ring focus:ring-blue-300">
            <option value="">Semua Jenis</option>
            @foreach ($allJenis as $j)
                <option value="{{ $j->id }}" {{ request('jenis') == $j->id ? 'selected' : '' }}>
                    {{ $j->nama }}
                </option>
            @endforeach
        </select>

        <select name="merk" onchange="this.form.submit()"
            class="border rounded px-3 py-2 w-48 focus:outline-none focus:ring focus:ring-blue-300">
            <option value="">Semua Merk</option>
            @foreach ($allMerk as $m)
                <option value="{{ $m->id }}" {{ request('merk') == $m->id ? 'selected' : '' }}>
                    {{ $m->nama }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($products as $index => $product)
            @php
                $aosType = ['fade-up', 'zoom-in', 'fade-left', 'flip-up'];
                $aosEffect = $aosType[$index % count($aosType)];
            @endphp
            <div class="bg-white shadow rounded overflow-hidden hover:shadow-lg transition" data-aos="{{ $aosEffect }}"
                data-aos-delay="500">

                @if ($product->foto)
                    <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama }}"
                        class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                        Tidak ada gambar
                    </div>
                @endif

                <div class="p-4">
                    <h3 class="text-md font-semibold mb-1">{{ $product->nama }}</h3>
                    <p class="text-sm text-gray-500 mb-1">
                        {{ $product->jenis->nama ?? '-' }} • {{ $product->merk->nama ?? '-' }}
                    </p>
                    <p class="text-sm text-gray-600 mb-2">
                        Stok: <span class="font-semibold">{{ $product->qty }}</span> pcs
                    </p>

                    <p class="text-blue-600 font-bold mb-4">
                        Rp{{ number_format($product->harga_jual, 0, ',', '.') }}
                    </p>

                    <div id="keranjang-action-{{ $product->id }}">
                        @php
                            $inCart = session('cart')[$product->id] ?? null;
                        @endphp

                        @if (!$inCart)
                            <button onclick="tambahKeKeranjang({{ $product->id }})"
                                class="bg-blue-600 text-white text-sm px-4 py-2 rounded w-full hover:bg-blue-700 transition">
                                Tambah ke Keranjang
                            </button>
                        @else
                            <div class="text-sm text-green-600 text-center">
                                ✅ Sudah di keranjang.<br>
                                <a href="{{ route('keranjang.index') }}" class="underline hover:text-green-700">
                                    Ubah jumlah di keranjang →
                                </a>
                            </div>
                        @endif

                        <button
                            onclick="beliLangsung({{ $product->id }}, {{ $product->harga_jual }}, {{ $product->qty }})"
                            class="bg-green-600 mt-2 text-white text-sm px-4 py-2 rounded w-full hover:bg-green-700 transition">
                            Beli Sekarang
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center text-gray-500">Tidak ada produk ditemukan.</div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function tambahKeKeranjang(productId) {
            if (!isLoggedIn) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Login Diperlukan',
                    text: 'Silakan login terlebih dahulu untuk menambahkan ke keranjang.',
                    confirmButtonText: 'Login Sekarang',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
                return;
            }

            fetch("{{ route('keranjang.tambah') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const container = document.getElementById(`keranjang-action-${productId}`);
                        container.innerHTML = `
                        <div class="text-sm text-green-600 text-center">
                            ✅ Sudah di keranjang.<br>
                            <a href='{{ route('keranjang.index') }}' class='underline hover:text-green-700'>
                                Ubah jumlah di keranjang →
                            </a>
                        </div>`;

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Produk berhasil ditambahkan ke keranjang.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Gagal', 'Tidak bisa menambahkan ke keranjang.', 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Terjadi kesalahan saat menambahkan.', 'error');
                });
        }

        function beliLangsung(productId, harga, stok) {
            if (!isLoggedIn) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Login Diperlukan',
                    text: 'Silakan login terlebih dahulu.',
                    confirmButtonText: 'Login Sekarang',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
                return;
            }

            Swal.fire({
                title: 'Masukkan Jumlah & Bukti Pembayaran',
                html: `
                    <input id="qty-input" type="number" min="1" max="${stok}" value="1" class="swal2-input" placeholder="Jumlah">
                    <input type="file" id="bukti-input" class="swal2-file" accept="image/*">
                    <p id="total-harga" class="text-sm text-gray-600 mt-2">Total: Rp${harga.toLocaleString('id-ID')}</p>
                `,
                focusConfirm: false,
                preConfirm: () => {
                    const qty = parseInt(document.getElementById('qty-input').value);
                    const buktiFile = document.getElementById('bukti-input').files[0];

                    if (!qty || qty <= 0 || qty > stok) {
                        Swal.showValidationMessage('Jumlah tidak valid atau melebihi stok');
                        return false;
                    }

                    if (!buktiFile) {
                        Swal.showValidationMessage('Silakan upload bukti pembayaran.');
                        return false;
                    }

                    const formData = new FormData();
                    formData.append('product_id', productId);
                    formData.append('qty', qty);
                    formData.append('bukti_pembayaran', buktiFile);

                    return fetch("{{ route('keranjang.beliLangsung') }}", {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status !== 'success') {
                                throw new Error(data.message);
                            }
                            return data;
                        })
                        .catch(err => {
                            Swal.showValidationMessage(err.message);
                        });
                },
                didOpen: () => {
                    const input = document.getElementById('qty-input');
                    const label = document.getElementById('total-harga');
                    input.addEventListener('input', () => {
                        const val = parseInt(input.value) || 0;
                        const total = harga * val;
                        label.textContent = `Total: Rp${total.toLocaleString('id-ID')}`;
                    });
                },
                showCancelButton: true,
                confirmButtonText: 'Beli Sekarang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed && result.value?.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: result.value.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = result.value.redirect ?? "{{ route('pesanan.customer') }}";
                    });
                }
            });
        }
    </script>
@endpush
