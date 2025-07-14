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

    <div class="max-w-xl mx-auto px-4 py-6">
        <div class="bg-white p-6 rounded shadow-lg">

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Nama Produk</label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Jenis</label>
                    <select name="id_jenis" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach ($jenis as $j)
                            <option value="{{ $j->id }}" {{ old('id_jenis') == $j->id ? 'selected' : '' }}>
                                {{ $j->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Merk</label>
                    <select name="id_merk" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Pilih Merk --</option>
                        @foreach ($merks as $m)
                            <option value="{{ $m->id }}" {{ old('id_merk') == $m->id ? 'selected' : '' }}>
                                {{ $m->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Harga Beli</label>
                    <input type="text" name="harga_beli" id="harga_beli"
                        class="w-full border rounded px-3 py-2" value="{{ old('harga_beli') }}" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Harga Jual</label>
                    <input type="text" name="harga_jual" id="harga_jual"
                        class="w-full border rounded px-3 py-2" value="{{ old('harga_jual') }}" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Stok</label>
                    <input type="number" name="qty" value="{{ old('qty') }}"
                        class="w-full border rounded px-3 py-2" min="0" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Status</label>
                    <select name="status" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="kosong" {{ old('status') == 'kosong' ? 'selected' : '' }}>Kosong</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Foto Produk</label>
                    <input type="file" name="foto" accept="image/*" class="w-full border rounded px-3 py-2">
                </div>

                <div class="flex justify-center mt-4 gap-2">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function formatRupiah(input) {
            input.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                this.value = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(value).replace('Rp', 'Rp');
            });
        }

        formatRupiah(document.getElementById('harga_beli'));
        formatRupiah(document.getElementById('harga_jual'));
    </script>
@endsection
