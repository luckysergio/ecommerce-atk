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
            <form action="{{ route('karyawan.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="w-full border rounded px-3 py-2"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded px-3 py-2"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="passwordInput"
                            class="w-full border rounded px-3 py-2 pr-10" required>
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-2 top-2.5 text-gray-500 hover:text-gray-700 text-sm">
                            👁️
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">No HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="w-full border rounded px-3 py-2"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Alamat</label>
                    <input type="text" name="alamat" value="{{ old('alamat') }}" class="w-full border rounded px-3 py-2"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Role</label>
                    <div class="flex gap-2 items-center">
                        <select name="id_role" id="roleSelect" class="flex-1 border rounded px-3 py-2" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('id_role') == $role->id ? 'selected' : '' }}>
                                    {{ $role->nama }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" onclick="openRoleModal()"
                            class="bg-gray-700 hover:bg-gray-800 text-white px-3 py-1 rounded text-sm">
                            + Role
                        </button>
                    </div>
                </div>

                <div class="flex justify-center mt-6">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="roleModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center invisible opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-sm shadow-xl">
            <h2 class="text-lg font-semibold mb-4" id="roleModalTitle">Tambah Role Baru</h2>
            <form method="POST" id="roleForm" action="{{ route('role.store') }}">
                @csrf
                <input type="hidden" name="_method" id="roleMethod" value="POST">
                <input type="hidden" name="role_id" id="roleId">

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Nama Role</label>
                    <input type="text" name="nama" id="roleNama"
                        class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring" required>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRoleModal()"
                        class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Batal</button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = event.currentTarget;
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = '🙈';
            } else {
                input.type = 'password';
                icon.textContent = '👁️';
            }
        }

        function openRoleModal(edit = false, roleId = null, roleNama = '') {
            const modal = document.getElementById('roleModal');
            const title = document.getElementById('roleModalTitle');
            const form = document.getElementById('roleForm');
            const methodInput = document.getElementById('roleMethod');
            const roleIdInput = document.getElementById('roleId');
            const roleNamaInput = document.getElementById('roleNama');

            if (edit) {
                title.innerText = 'Edit Role';
                form.action = `/role/${roleId}`;
                methodInput.value = 'PUT';
                roleIdInput.value = roleId;
                roleNamaInput.value = roleNama;
            } else {
                title.innerText = 'Tambah Role Baru';
                form.action = `{{ route('role.store') }}`;
                methodInput.value = 'POST';
                roleIdInput.value = '';
                roleNamaInput.value = '';
            }

            modal.classList.remove('invisible', 'opacity-0', 'pointer-events-none');
            modal.classList.add('visible', 'opacity-100');
        }

        function closeRoleModal() {
            const modal = document.getElementById('roleModal');
            modal.classList.add('invisible', 'opacity-0', 'pointer-events-none');
            modal.classList.remove('visible', 'opacity-100');
        }
    </script>
@endsection
