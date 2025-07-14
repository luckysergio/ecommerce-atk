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
            <form action="{{ route('customer.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama', $customer->nama) }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email', $customer->user->email) }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Password <span class="text-gray-500 text-sm">(Kosongkan jika tidak diubah)</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="passwordInput"
                            class="w-full border rounded px-3 py-2 pr-10">
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-2 top-2.5 text-gray-500 hover:text-gray-700 text-sm">
                            👁️
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">No HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $customer->no_hp) }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Alamat</label>
                    <input type="text" name="alamat" value="{{ old('alamat', $customer->alamat) }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                {{-- Role (hidden) --}}
                <input type="hidden" name="id_role" value="{{ $roles->first()->id }}">

                <div class="flex justify-center mt-6">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Perbarui</button>
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
    </script>
@endsection
