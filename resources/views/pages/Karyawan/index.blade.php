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
            <h1 class="text-xl font-semibold">Data Karyawan</h1>
            @auth
                @if (auth()->user()->role->nama === 'Admin')
                    <a href="{{ route('karyawan.create') }}"
                        class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded shadow">
                        + Tambah Karyawan
                    </a>
                @endif
            @endauth
        </div>

        <div class="overflow-x-auto bg-gray-100 shadow-md rounded-xl">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-300 text-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-center">Nama</th>
                        <th class="px-6 py-3 text-center">Email</th>
                        <th class="px-6 py-3 text-center">No HP</th>
                        <th class="px-6 py-3 text-center">Alamat</th>
                        <th class="px-6 py-3 text-center">Role</th>
                        @auth
                            @if (auth()->user()->role->nama === 'Admin')
                                <th class="px-6 py-3 text-center">Aksi</th>
                            @endif
                        @endauth
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($karyawans as $index => $karyawan)
                        <tr class="hover:bg-gray-100 transition duration-200">
                            <td class="px-6 py-4 text-center">{{ $karyawan->nama }}</td>
                            <td class="px-6 py-4 text-center">{{ $karyawan->user->email }}</td>
                            <td class="px-6 py-4 text-center">{{ $karyawan->no_hp }}</td>
                            <td class="px-6 py-4 text-center">{{ $karyawan->alamat }}</td>
                            <td class="px-6 py-4 text-center">{{ $karyawan->user->role->nama ?? '-' }}</td>
                            @auth
                                @if (auth()->user()->role->nama === 'Admin')
                                    <td class="px-6 py-4 text-center space-x-2">
                                        <a href="{{ route('karyawan.edit', $karyawan->id) }}"
                                            class="text-yellow-600 hover:text-yellow-700">✏️</a>
                                        <button class="text-red-600 hover:text-red-700 delete-button"
                                            data-id="{{ $karyawan->id }}">🗑️</button>
                                    </td>
                                @endif
                            @endauth
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">Tidak ada data karyawan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
                    text: "Data karyawan tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('delete-form');
                        form.action = `/karyawan/${id}`;
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
