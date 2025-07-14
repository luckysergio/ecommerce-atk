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

    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-semibold">Data Produk</h1>
            @auth
                @if (auth()->user()->role->nama === 'Admin')
                    <a href="{{ route('products.create') }}"
                        class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded shadow">
                        + Tambah Produk
                    </a>
                @endif
            @endauth
        </div>

        <form method="GET" id="filter-form" class="mb-6">
            <div class="flex justify-center flex-wrap items-end gap-4">
                <div>
                    <select name="jenis" onchange="document.getElementById('filter-form').submit();"
                        class="border px-3 py-2 rounded w-48">
                        <option value="">Semua Jenis</option>
                        @foreach ($allJenis as $j)
                            <option value="{{ $j->id }}" {{ request('jenis') == $j->id ? 'selected' : '' }}>
                                {{ $j->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="merk" onchange="document.getElementById('filter-form').submit();"
                        class="border px-3 py-2 rounded w-48">
                        <option value="">Semua Merk</option>
                        @foreach ($allMerk as $m)
                            <option value="{{ $m->id }}" {{ request('merk') == $m->id ? 'selected' : '' }}>
                                {{ $m->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto bg-gray-100 shadow-md rounded-xl">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-300 text-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-center">Nama Produk</th>
                        <th class="px-6 py-3 text-center">Jenis</th>
                        <th class="px-6 py-3 text-center">Merk</th>
                        <th class="px-6 py-3 text-center">Harga Beli</th>
                        <th class="px-6 py-3 text-center">Harga Jual</th>
                        <th class="px-6 py-3 text-center">Stok</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        @auth
                            @if (auth()->user()->role->nama === 'Admin')
                                <th class="px-6 py-3 text-center">Aksi</th>
                            @endif
                        @endauth
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($products as $produk)
                        <tr class="hover:bg-gray-100 transition duration-200">
                            <td class="px-6 py-4 text-center">{{ $produk->nama }}</td>
                            <td class="px-6 py-4 text-center">{{ $produk->jenis->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">{{ $produk->merk->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">Rp{{ number_format($produk->harga_beli, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">Rp{{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">{{ $produk->qty }}</td>
                            <td class="px-6 py-4 text-center">
                                @if ($produk->status == 'tersedia')
                                    <span class="text-green-600 font-semibold bg-green-100 px-2 py-1 rounded">
                                        Tersedia
                                    </span>
                                @else
                                    <span class="text-red-600 font-semibold bg-red-100 px-2 py-1 rounded">
                                        Kosong
                                    </span>
                                @endif
                            </td>
                            @auth
                                @if (auth()->user()->role->nama === 'Admin')
                                    <td class="px-6 py-4 text-center space-x-2">
                                        <a href="{{ route('products.edit', $produk->id) }}"
                                            class="text-yellow-600 hover:text-yellow-700">✏️</a>
                                        <button class="text-red-600 hover:text-red-700 delete-button"
                                            data-id="{{ $produk->id }}">🗑️</button>
                                    </td>
                                @endif
                            @endauth
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500 italic">
                                Tidak ada data produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>

    <form id="delete-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data produk tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('delete-form');
                        form.action = '{{ route("products.destroy", ":id") }}'.replace(':id', id);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
