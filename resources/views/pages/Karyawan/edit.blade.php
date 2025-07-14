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
            <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Nama</label>
                    <input type="text" name="nama" class="w-full border rounded px-3 py-2" value="{{ $karyawan->nama }}"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Email</label>
                    <input type="email" name="email" class="w-full border rounded px-3 py-2"
                        value="{{ $karyawan->user->email }}" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Password (Opsional)</label>
                    <div class="relative">
                        <input type="password" name="password" id="passwordInput"
                            class="w-full border rounded px-3 py-2 pr-10" placeholder="Kosongkan jika tidak ingin diubah">
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-2 top-2 text-sm text-gray-600 hover:text-gray-800">
                            👁️
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">No HP</label>
                    <input type="text" name="no_hp" class="w-full border rounded px-3 py-2"
                        value="{{ $karyawan->no_hp }}" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Alamat</label>
                    <input type="text" name="alamat" class="w-full border rounded px-3 py-2"
                        value="{{ $karyawan->alamat }}" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Role</label>
                    <select name="id_role" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ $karyawan->user->id_role == $role->id ? 'selected' : '' }}>
                                {{ $role->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-center mt-6">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
@endsection
