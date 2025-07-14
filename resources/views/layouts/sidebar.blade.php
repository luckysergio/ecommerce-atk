<ul class="navbar-nav bg-gradient-to-r from-[#2c3e50] to-[#3498db] text-white sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
        <div class="sidebar-brand-icon">
            <i class="fas fa-home text-white"></i>
        </div>
    </a>

    <hr class="sidebar-divider my-0 border-white/20">

    <li class="nav-item {{ request()->is('dashboard') ? 'bg-white/10 rounded-md' : '' }}">
        <a class="nav-link hover:bg-white/10" href="/dashboard">
            <i class="fas fa-tachometer-alt text-white"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider border-white/20">

    <li class="nav-item {{ request()->is('karyawan*') ? 'bg-white/10 rounded-md' : '' }}">
        <a class="nav-link hover:bg-white/10" href="/karyawan">
            <i class="fas fa-users-cog text-white"></i>
            <span>Karyawan</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('customer') ? 'bg-white/10 rounded-md' : '' }}">
        <a class="nav-link hover:bg-white/10" href="/customer">
            <i class="fas fa-users text-white"></i>
            <span>Customer</span>
        </a>
    </li>
    
    <li class="nav-item {{ request()->is('jenis-merk') ? 'bg-white/10 rounded-md' : '' }}">
        <a class="nav-link hover:bg-white/10" href="/jenis-merk">
            <i class="fas fa-layer-group text-white"></i>
            <span>Jenis dan Merk</span>
        </a>
    </li>


    <li class="nav-item {{ request()->is('produk*') ? 'bg-white/10 rounded-md' : '' }}">
        <a class="nav-link hover:bg-white/10" href="/produk">
            <i class="fas fa-toolbox text-white"></i>
            <span>Inventory</span>
        </a>
    </li>

    <li class="nav-item {{ request()->is('laporan') ? 'bg-white/10 rounded-md' : '' }}">
        <a class="nav-link hover:bg-white/10" href="/laporan">
            <i class="fas fa-file-invoice-dollar text-white"></i>
            <span>Laporan</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block border-white/20">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0 bg-white/30 hover:bg-white/50" id="sidebarToggle"></button>
    </div>
</ul>
