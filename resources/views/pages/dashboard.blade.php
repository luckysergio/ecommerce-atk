@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <a href="{{ route('admin.transaksi.by_status', 'pending') }}"
                class="relative group flex flex-col items-center justify-center 
                    bg-gradient-to-br from-[#2c3e50] to-[#3498db] text-white 
                    p-6 rounded-2xl shadow-lg hover:shadow-xl transition-transform duration-200 hover:-translate-y-1">

                @if ($countPending > 0)
                    <span class="absolute top-3 right-4 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                        {{ $countPending }}
                    </span>
                @endif

                <i class="fas fa-cart-plus fa-3x mb-4"></i>
                <h3 class="text-base font-semibold">Order Baru</h3>
            </a>

            <a href="{{ route('admin.transaksi.by_status', 'proses') }}"
                class="relative group flex flex-col items-center justify-center 
                    bg-gradient-to-br from-[#2c3e50] to-[#3498db] text-white 
                    p-6 rounded-2xl shadow-lg hover:shadow-xl transition-transform duration-200 hover:-translate-y-1">

                @if ($countProses > 0)
                    <span class="absolute top-3 right-4 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                        {{ $countProses }}
                    </span>
                @endif

                <i class="fas fa-spinner fa-3x mb-4 animate-spin-slow"></i>
                <h3 class="text-base font-semibold">Order Proses</h3>
            </a>

            <a href="{{ route('admin.transaksi.by_status', 'siap diambil') }}"
                class="relative group flex flex-col items-center justify-center 
                    bg-gradient-to-br from-[#2c3e50] to-[#3498db] text-white 
                    p-6 rounded-2xl shadow-lg hover:shadow-xl transition-transform duration-200 hover:-translate-y-1">

                @if ($countSiap > 0)
                    <span class="absolute top-3 right-4 bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                        {{ $countSiap }}
                    </span>
                @endif

                <i class="fas fa-box-open fa-3x mb-4"></i>
                <h3 class="text-base font-semibold">Order Siap</h3>
            </a>

            <a href="{{ route('admin.transaksi.by_status', 'selesai') }}"
                class="relative group flex flex-col items-center justify-center 
                    bg-gradient-to-br from-[#2c3e50] to-[#3498db] text-white 
                    p-6 rounded-2xl shadow-lg hover:shadow-xl transition-transform duration-200 hover:-translate-y-1">

                @if ($countSelesai > 0)
                    <span class="absolute top-3 right-4 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                        {{ $countSelesai }}
                    </span>
                @endif

                <i class="fas fa-clipboard-check fa-3x mb-4"></i>
                <h3 class="text-base font-semibold">Order Selesai</h3>
            </a>

        </div>
    </div>
@endsection
