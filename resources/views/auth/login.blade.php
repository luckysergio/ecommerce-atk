@extends('layouts.customer')

@section('content')
<div class="max-w-md mx-auto p-6 bg-white rounded shadow mt-10">
    <h2 class="text-2xl font-bold mb-4">Login</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" required value="{{ old('email') }}"
                class="w-full border px-3 py-2 rounded focus:outline-blue-500">
            @error('email')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
                <input type="password" name="password" id="password" required
                    class="w-full border px-3 py-2 rounded focus:outline-blue-500 pr-10">
                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-2 flex items-center text-gray-600">
                    👁️
                </button>
            </div>
            @error('password')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Login
        </button>

        <p class="text-sm text-center mt-4">Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Daftar di sini</a>
        </p>
    </form>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function togglePassword() {
            const passInput = document.getElementById("password");
            passInput.type = passInput.type === "password" ? "text" : "password";
        }

        @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK'
        });
        @endif

        @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal Login',
            text: '{{ session('error') }}',
            confirmButtonText: 'Coba Lagi'
        });
        @endif

        @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonText: 'OK'
        });
        @endif
    </script>
@endpush
