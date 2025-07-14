@extends('layouts.customer')

@section('content')
    <div class="max-w-md mx-auto p-6 bg-white rounded shadow mt-10">

        <form id="profileForm" action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" name="nama" required value="{{ old('nama', $customer->nama) }}"
                    class="w-full border px-3 py-2 rounded focus:outline-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                <input type="text" name="no_hp" required value="{{ old('no_hp', $customer->no_hp) }}"
                    pattern="^[0-9]{10,15}$" title="Masukkan nomor HP antara 10-15 digit"
                    class="w-full border px-3 py-2 rounded focus:outline-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="alamat" rows="3" required class="w-full border px-3 py-2 rounded focus:outline-blue-500">{{ old('alamat', $customer->alamat) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required value="{{ old('email', $user->email) }}"
                    class="w-full border px-3 py-2 rounded focus:outline-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak ingin mengubah"
                        class="w-full border px-3 py-2 rounded focus:outline-blue-500 pr-10">
                    <button type="button" onclick="togglePassword('password')"
                        class="absolute inset-y-0 right-2 flex items-center text-gray-600">
                        👁️
                    </button>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full border px-3 py-2 rounded focus:outline-blue-500 pr-10">
                    <button type="button" onclick="togglePassword('password_confirmation')"
                        class="absolute inset-y-0 right-2 flex items-center text-gray-600">
                        👁️
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700 transition">
                Simpan Perubahan
            </button>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === "password" ? "text" : "password";
        }

        document.getElementById('profileForm')?.addEventListener('submit', function (e) {
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('password_confirmation').value.trim();

            if (confirmPassword && !password) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: 'Password baru wajib diisi jika ingin mengisi konfirmasi password.',
                });
                return;
            }

            if (password && confirmPassword && password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Password Tidak Cocok',
                    text: 'Password dan konfirmasi tidak sama.',
                });
            }
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                timer: 3000
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'Tutup'
            });
        @endif
    </script>
@endpush
