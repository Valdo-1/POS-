<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB_ALDO_POS - Point of Sales PPKD Jakarta Pusat</title>

    <!-- SEO Optimization -->
    <meta name="description" content="WEB_ALDO_POS - Sistem Informasi Point of Sales  PPKD Jakarta Pusat">
    <meta name="author" content="WEB_ALDO_POS Team">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.ico">

    <!-- Local Third-Party Libraries (100% Offline Compatible) -->
    <link rel="stylesheet" href="{{asset('assets/libs/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/libs/bootstrap-icons/bootstrap-icons.css')}}">


    <!-- Main Design System & Custom Stylesheet -->
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
    @include('inc.css')
</head>

<body>

    <!-- ==========================================
         START: Sidebar Component
         Highly polished, dark-green navigation
         ========================================== -->
    <div class="sidebar-wrapper" id="sidebar">
        <!-- Brand Logo / Identity -->
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <i class="bi bi-cup-hot-fill text-success"></i>
            <span>WEB_ALDO_POS</span>
        </a>

        <!-- Navigation Menu -->
        <div class="flex-grow-1 overflow-y-auto">
            <!-- Group: Menu Utama -->
            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Main Menu</div>
                <ul class="sidebar-menu-list">
                    <li class="sidebar-menu-item">
                        <a href="{{ route('dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" id="menu-overview" title="Dashboard">
                            <i class="bi bi-grid-fill"></i>
                            <span>DASHBOARD</span>
                        </a>
                    </li>
                    {{-- menu transaksi POS kasir --}}
                    @if(Auth::check() && Auth::user()->hasPermission('pos'))
                    <li class="sidebar-menu-item">
                        <a href="{{ route('transactions.index') }}" class="sidebar-menu-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" id="menu-pos" title="POS Kasir">
                            <i class="bi bi-cart-check-fill text-success"></i>
                            <span class="fw-bold">PESANAN DISINI</span>
                        </a>
                    </li>
                    @endif
                    {{-- menu stok produk --}}
                    @if(Auth::check() && Auth::user()->hasPermission('stock'))
                    <li class="sidebar-menu-item">
                        <a href="{{ route('stock.index') }}" class="sidebar-menu-link {{ request()->routeIs('stock.*') ? 'active' : '' }}" id="menu-stock" title="Monitoring Stok">
                            <i class="bi bi-boxes"></i>
                            <span>STOK PRODUK</span>
                        </a>
                    </li>
                    @endif
                    {{-- laporan omzet dan penjualan --}}
                    @if(Auth::check() && Auth::user()->hasPermission('reports'))
                    <li class="sidebar-menu-item">
                        <a href="{{ route('reports.index') }}" class="sidebar-menu-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" id="menu-reports" title="Laporan Penjualan">
                            <i class="bi bi-graph-up-arrow"></i>
                            <span>LAPORAN PENJUALAN</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

            {{-- grup master data nampil kalau user punya minimal 1 izin master data --}}
            @if(Auth::check() && (Auth::user()->hasPermission('roles') || Auth::user()->hasPermission('users') || Auth::user()->hasPermission('categories') || Auth::user()->hasPermission('products')))
            <!-- Group: Master Data -->
            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Master Data</div>
                <ul class="sidebar-menu-list">
                    @if(Auth::user()->hasPermission('roles'))
                    <li class="sidebar-menu-item">
                        <a href="{{ route('roles.index') }}" class="sidebar-menu-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" id="menu-roles" title="Roles">
                            <i class="bi bi-shield-lock"></i>
                            <span>Roles</span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->hasPermission('users'))
                    <li class="sidebar-menu-item">
                        <a href="{{ route('users.index') }}" class="sidebar-menu-link {{ request()->routeIs('users.*') ? 'active' : '' }}" id="menu-users" title="Users">
                            <i class="bi bi-people"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->hasPermission('categories'))
                    <li class="sidebar-menu-item">
                        <a href="{{ route('categories.index') }}" class="sidebar-menu-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" id="menu-categories" title="Categories">
                            <i class="bi bi-tags"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->hasPermission('products'))
                    <li class="sidebar-menu-item">
                        <a href="{{ route('products.index') }}" class="sidebar-menu-link {{ request()->routeIs('products.*') ? 'active' : '' }}" id="menu-products" title="Products">
                            <i class="bi bi-box-seam"></i>
                            <span>Products</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
            @endif


        </div>

        <!-- Sidebar Profile Card (Dynamic Footer) -->
        <div class="sidebar-profile">
            <div class="d-flex align-items-center">
                <img src="{{ Auth::user()->avatar_url }}" alt="Profile Image" class="rounded-circle me-2 border shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                <div class="sidebar-profile-info">
                    <div class="sidebar-profile-name fw-bold text-truncate" style="max-width: 140px;">{{ Auth::user()->name ?? 'User' }}</div>
                    <div class="small text-muted">{{ Auth::user()->role->name ?? 'Role' }}</div>
                </div>
            </div>
        </div>
    </div>
    <!-- ==========================================
         END: Sidebar Component
         ========================================== -->


    <!-- ==========================================
         START: Main Content Area
         ========================================== -->
    <div class="main-wrapper">

        <!-- START: Top Navbar Component -->
        <header class="navbar-custom">
            <div class="navbar-left d-flex align-items-center">
                <!-- Desktop sidebar toggle (visible on large screens only) -->
                <button class="btn-desktop-toggle d-none d-xl-flex align-items-center justify-content-center me-3"
                    id="desktop-sidebar-toggle" aria-label="Minimize Sidebar">
                    <i class="bi bi-chevron-bar-left"></i>
                </button>
                <!-- Mobile sidebar toggle -->
                <button class="sidebar-toggle-btn me-2" id="sidebar-toggle" aria-label="Toggle Navigation">
                    <i class="bi bi-list"></i>
                </button>

                <!-- Resto Location & Live Operational Clock -->
                <div class="d-none d-md-flex align-items-center bg-white px-3 py-1.5 rounded-pill border shadow-sm me-3">
                    <i class="bi bi-shop text-success me-2 fs-6"></i>
                    <span class="fw-semibold text-dark me-2 small">Coffe Shop PPKD Jakarta Pusat</span>
                    <span class="text-muted small me-2">|</span>
                    <i class="bi bi-clock text-muted me-1 small"></i>
                    <span id="live-resto-clock" class="fw-bold text-success small">--:--:-- WIB</span>
                </div>
            </div>

            <!-- Right actions -->
            <div class="navbar-actions ms-auto d-flex align-items-center">
                <!-- Active Shift / Connection Status -->
                <div class="d-none d-lg-flex align-items-center me-3">
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1.5 small d-inline-flex align-items-center">
                        <span class="status-dot-active me-1.5"></span> Shift Operasional Aktif
                    </span>
                </div>

                <!-- Fullscreen Toggle -->
                <button class="navbar-action-btn me-2" aria-label="Toggle Fullscreen" id="btn-fullscreen" title="Mode Layar Penuh">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>

                <!-- Profile Dropdown (Positioned on Far Right End) -->
                <div class="dropdown">
                    <button class="navbar-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false" id="profile-dropdown">
                        <img src="{{ Auth::user()->avatar_url }}" alt="Profile Image" class="navbar-profile-img">
                        <span class="navbar-profile-name d-none d-md-inline">{{ Auth::user()->name ?? 'User' }}</span>
                        <i class="bi bi-chevron-down navbar-profile-caret"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profile" aria-labelledby="profile-dropdown">
                        <li class="dropdown-header">Halo, {{ Auth::user()->name ?? 'User' }}!</li>
                        <li><span class="dropdown-item-text small text-muted"><i class="bi bi-shield me-1"></i> Level: {{ Auth::user()->role->name ?? '-' }}</span></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">
                                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
        <!-- END: Top Navbar Component -->

        <!-- START: Page Header Banner -->
        @yield('header')
        <!-- END: Page Header Banner -->

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- START: Main Content Area -->
        @yield('content')
        <!-- END: Main Content Area -->

        <!-- START: Footer Component -->
        @include('inc.footer')
        <!-- END: Footer Component -->

    </div>
    <!-- ==========================================
         END: Main Content Area
         ========================================== -->

    <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
   
    @include('inc.js')
    <script>
        function updateRestoClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const day = days[now.getDay()];
            const date = now.getDate();
            const month = months[now.getMonth()];
            const year = now.getFullYear();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const clockEl = document.getElementById('live-resto-clock');
            if (clockEl) {
                clockEl.innerText = `${day}, ${date} ${month} ${year} | ${hours}:${minutes}:${seconds} WIB`;
            }
        }
        setInterval(updateRestoClock, 1000);
        updateRestoClock();
    </script>
</body>

</html>