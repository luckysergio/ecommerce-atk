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
        @auth
            @if (auth()->user()->role->nama === 'Admin')
                <div class="flex flex-wrap gap-4 mb-6 justify-between">
                    <button onclick="openModal('jenis')"
                        class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded shadow">
                        + Tambah Jenis
                    </button>
                    <button onclick="openModal('merk')" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded shadow">
                        + Tambah Merk
                    </button>
                </div>
            @endif
        @endauth

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="overflow-x-auto bg-gray-100 shadow-md rounded-xl">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-300 text-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-center">Nama Jenis</th>
                                @auth
                                    @if (auth()->user()->role->nama === 'Admin')
                                        <th class="px-6 py-3 text-center">Aksi</th>
                                    @endif
                                @endauth
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($jenis as $index => $item)
                                <tr class="hover:bg-gray-100 transition duration-200">
                                    <td class="px-6 py-4 text-center">{{ $item->nama }}</td>
                                    @auth
                                        @if (auth()->user()->role->nama === 'Admin')
                                            <td class="px-6 py-4 text-center space-x-2">
                                                <button
                                                    onclick="openEditModal('jenis', {{ $item->id }}, '{{ $item->nama }}')"
                                                    class="text-yellow-600 hover:text-yellow-700">✏️</button>
                                                <button class="text-red-600 hover:text-red-700 delete-button" data-type="jenis"
                                                    data-id="{{ $item->id }}">🗑️</button>
                                            </td>
                                        @endif
                                    @endauth
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <div class="overflow-x-auto bg-gray-100 shadow-md rounded-xl">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-300 text-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-center">Nama Merk</th>
                                @auth
                                    @if (auth()->user()->role->nama === 'Admin')
                                        <th class="px-6 py-3 text-center">Aksi</th>
                                    @endif
                                @endauth
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($merks as $index => $item)
                                <tr class="hover:bg-gray-100 transition duration-200">
                                    <td class="px-6 py-4 text-center">{{ $item->nama }}</td>
                                    @auth
                                        @if (auth()->user()->role->nama === 'Admin')
                                            <td class="px-6 py-4 text-center space-x-2">
                                                <button
                                                    onclick="openEditModal('merk', {{ $item->id }}, '{{ $item->nama }}')"
                                                    class="text-yellow-600 hover:text-yellow-700">✏️</button>
                                                <button class="text-red-600 hover:text-red-700 delete-button" data-type="merk"
                                                    data-id="{{ $item->id }}">🗑️</button>
                                            </td>
                                        @endif
                                    @endauth
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modalForm"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center invisible opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl">
            <h2 id="modalTitle" class="text-lg font-semibold mb-4">Tambah Data</h2>
            <form id="modalFormElement" method="POST" action="{{ route('jenis-merk.store') }}">
                @csrf
                <input type="hidden" id="typeInput" name="type">
                <input type="hidden" id="editId">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nama</label>
                    <input type="text" name="nama" id="namaInput"
                        class="w-full border border-gray-300 rounded px-4 py-2 focus:ring focus:outline-none" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">Batal</button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <form id="delete-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function openModal(type) {
            document.getElementById('modalTitle').innerText = 'Tambah ' + (type === 'merk' ? 'Merk' : 'Jenis');
            document.getElementById('typeInput').value = type;
            document.getElementById('namaInput').value = '';
            document.getElementById('editId').value = '';
            document.getElementById('modalFormElement').action = "{{ route('jenis-merk.store') }}";

            const modal = document.getElementById('modalForm');
            modal.classList.remove('invisible', 'opacity-0', 'pointer-events-none');
            modal.classList.add('visible', 'opacity-100');

            const methodInput = document.querySelector('#modalFormElement input[name="_method"]');
            if (methodInput) methodInput.remove();
        }

        function openEditModal(type, id, nama) {
            document.getElementById('modalTitle').innerText = 'Edit ' + (type === 'merk' ? 'Merk' : 'Jenis');
            document.getElementById('typeInput').value = type;
            document.getElementById('editId').value = id;
            document.getElementById('namaInput').value = nama;
            document.getElementById('modalFormElement').action = `/jenis-merk/${type}/${id}`;

            let methodInput = document.querySelector('#modalFormElement input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                document.getElementById('modalFormElement').appendChild(methodInput);
            }

            const modal = document.getElementById('modalForm');
            modal.classList.remove('invisible', 'opacity-0', 'pointer-events-none');
            modal.classList.add('visible', 'opacity-100');
        }

        function closeModal() {
            const modal = document.getElementById('modalForm');
            modal.classList.add('invisible', 'opacity-0', 'pointer-events-none');
            modal.classList.remove('visible', 'opacity-100');

            const methodInput = document.querySelector('#modalFormElement input[name="_method"]');
            if (methodInput) methodInput.remove();
        }

        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const type = this.dataset.type;
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('delete-form');
                        form.action = `/jenis-merk/${type}/${id}`;
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
