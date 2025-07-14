<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'ATK Online' }}</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }

        footer {
            flex-shrink: 0;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 font-sans leading-relaxed">

    <header class="bg-white shadow sticky top-0 z-50 transition duration-300 ease-in-out">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="text-xl font-bold text-blue-600">CV.JAYA COPIER (ATK)</div>

            <nav class="hidden md:flex gap-6 text-sm">
                @auth
                    <a href="{{ route('produk.customer') }}"
                        class="{{ request()->routeIs('produk.customer') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600 transition' }}">
                        Produk
                    </a>
                    <a href="{{ route('keranjang.index') }}"
                        class="{{ request()->routeIs('keranjang.index') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600 transition' }}">
                        Keranjang
                    </a>
                    <a href="{{ route('pesanan.customer') }}"
                        class="{{ request()->routeIs('pesanan.customer') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600 transition' }}">
                        Pesanan Saya
                    </a>
                    <a href="{{ route('profile.edit') }}"
                        class="{{ request()->routeIs('profile.edit') ? 'text-blue-600 font-semibold' : 'hover:text-blue-600 transition' }}">
                        Profil
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="hover:text-red-600 text-sm transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-blue-600 transition">Login</a>
                @endauth
            </nav>

            <button id="menu-toggle" class="md:hidden text-gray-600 focus:outline-none" aria-label="Toggle Menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div id="mobile-menu" class="md:hidden hidden px-4 pb-4 space-y-2">
            @auth
                <a href="{{ route('produk.customer') }}"
                    class="block py-2 {{ request()->routeIs('produk.customer') ? 'text-blue-600 font-semibold' : '' }}">
                    Produk
                </a>
                <a href="{{ route('keranjang.index') }}"
                    class="block py-2 {{ request()->routeIs('keranjang.index') ? 'text-blue-600 font-semibold' : '' }}">
                    Keranjang
                </a>
                <a href="{{ route('pesanan.customer') }}"
                    class="block py-2 {{ request()->routeIs('pesanan.customer') ? 'text-blue-600 font-semibold' : '' }}">
                    Pesanan Saya
                </a>
                <a href="{{ route('profile.edit') }}"
                    class="block py-2 {{ request()->routeIs('profile.edit') ? 'text-blue-600 font-semibold' : '' }}">
                    Profil
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="block w-full text-left py-2 text-red-600 hover:underline">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block py-2">Login</a>
            @endauth
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-6 w-full">
        @yield('content')
    </main>

    @auth
        @if (session('cart_total') && session('cart_qty'))
            <div class="fixed bottom-4 left-1/2 -translate-x-1/2 bg-blue-600 text-white px-6 py-3 rounded-full shadow-lg flex items-center gap-4 hover:bg-blue-700 cursor-pointer transition"
                onclick="window.location='{{ route('keranjang.index') }}'">
                🛒 {{ session('cart_qty') }} item &bull;
                <strong>Rp{{ number_format(session('cart_total'), 0, ',', '.') }}</strong>
                <span class="ml-2">Lihat Keranjang →</span>
            </div>
        @endif
    @endauth

    <footer class="bg-white border-t mt-10 w-full">
        <div class="max-w-7xl mx-auto px-4 py-6 text-sm text-gray-500 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-700 font-semibold mb-2">Hubungi Kami</p>
                <p>📧 Email:
                    <a href="mailto:admin@atk-online.test" class="text-blue-600 hover:underline">
                        admin@atk-online.test
                    </a>
                </p>
                <p>📞 WhatsApp:
                    <a href="https://wa.me/6281319691096" target="_blank" class="text-blue-600 hover:underline">
                        +62 813-1969-1096
                    </a>
                </p>
                <p>📸 Instagram:
                    <a href="https://instagram.com/jaya_copier" target="_blank" class="text-blue-600 hover:underline">
                        @jaya_copier
                    </a>
                </p>
            </div>
            <div class="md:text-right">
                <p class="text-gray-600">© {{ date('Y') }} ATK Online</p>
                <p class="text-xs mt-1">Dibuat dengan ❤️ untuk keperluan UMKM Indonesia.</p>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('menu-toggle')?.addEventListener('click', () => {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            duration: 800
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')

</body>

</html>
