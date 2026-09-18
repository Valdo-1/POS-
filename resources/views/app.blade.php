<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KETARA - Point of Sales PPKD Jakarta Pusat</title>

    <!-- SEO Optimization -->
    <meta name="description" content="KETARA - Sistem Informasi Point of Sales PPKD Jakarta Pusat">
    <meta name="author" content="KETARA Team">

    <base href="{{ asset('assets/assets') }}/">
    @include('inc.css')
</head>

<body class="bg-slate-100 text-slate-900 font-sans min-h-screen antialiased">

    <!-- ==========================================
         START: Sidebar Component (KETARA Emerald Dark Mode)
         ========================================== -->
    <aside class="sidebar-wrapper fixed inset-y-0 left-0 w-64 bg-emerald-950 text-white flex flex-col z-30 shadow-2xl transition-transform duration-300 border-r border-emerald-900/60" id="sidebar">
        <!-- Brand Logo / Identity -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-6 py-5 border-b border-emerald-900/80 hover:bg-emerald-900/40 transition-colors">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-emerald-400 flex items-center justify-center text-white shadow-md shadow-emerald-950/40">
                <i class="bi bi-cup-hot-fill text-xl"></i>
            </div>
            <div>
                <span class="font-black text-xl tracking-wider text-white bg-clip-text text-transparent bg-gradient-to-r from-emerald-200 via-white to-teal-200 block">KETARA</span>
                <span class="text-[10px] text-emerald-400 font-medium tracking-wide uppercase block -mt-1">Point of Sales</span>
            </div>
        </a>

        <!-- Navigation Menu -->
        <div class="flex-grow overflow-y-auto px-4 py-4 space-y-6">
            <!-- Group: Menu Utama -->
            <div>
                <div class="px-3 text-[11px] font-bold text-emerald-400/80 uppercase tracking-wider mb-2">Main Menu</div>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/50 font-bold' : 'text-emerald-100/80 hover:bg-emerald-900/60 hover:text-white' }}">
                            <i class="bi bi-grid-fill text-lg {{ request()->routeIs('dashboard') ? 'text-white' : 'text-emerald-400' }}"></i>
                            <span>DASHBOARD</span>
                        </a>
                    </li>
                    
                    {{-- menu transaksi POS kasir --}}
                    @if(Auth::check() && Auth::user()->hasPermission('pos'))
                    <li>
                        <a href="{{ route('transactions.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('transactions.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/50 font-bold' : 'text-emerald-100/80 hover:bg-emerald-900/60 hover:text-white' }}">
                            <i class="bi bi-cart-check-fill text-lg {{ request()->routeIs('transactions.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                            <span class="font-bold">PESANAN DISINI</span>
                        </a>
                    </li>
                    @endif

                    {{-- menu stok produk --}}
                    @if(Auth::check() && Auth::user()->hasPermission('stock'))
                    <li>
                        <a href="{{ route('stock.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('stock.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/50 font-bold' : 'text-emerald-100/80 hover:bg-emerald-900/60 hover:text-white' }}">
                            <i class="bi bi-boxes text-lg {{ request()->routeIs('stock.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                            <span>STOK PRODUK</span>
                        </a>
                    </li>
                    @endif

                    {{-- laporan omzet dan penjualan --}}
                    @if(Auth::check() && Auth::user()->hasPermission('reports'))
                    <li>
                        <a href="{{ route('reports.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/50 font-bold' : 'text-emerald-100/80 hover:bg-emerald-900/60 hover:text-white' }}">
                            <i class="bi bi-graph-up-arrow text-lg {{ request()->routeIs('reports.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                            <span>LAPORAN PENJUALAN</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

            {{-- grup master data --}}
            @if(Auth::check() && (Auth::user()->hasPermission('roles') || Auth::user()->hasPermission('users') || Auth::user()->hasPermission('categories') || Auth::user()->hasPermission('products')))
            <div>
                <div class="px-3 text-[11px] font-bold text-emerald-400/80 uppercase tracking-wider mb-2">Master Data</div>
                <ul class="space-y-1">
                    @if(Auth::user()->hasPermission('roles'))
                    <li>
                        <a href="{{ route('roles.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('roles.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/50 font-bold' : 'text-emerald-100/80 hover:bg-emerald-900/60 hover:text-white' }}">
                            <i class="bi bi-shield-lock text-lg {{ request()->routeIs('roles.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                            <span>Roles</span>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->hasPermission('users'))
                    <li>
                        <a href="{{ route('users.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/50 font-bold' : 'text-emerald-100/80 hover:bg-emerald-900/60 hover:text-white' }}">
                            <i class="bi bi-people text-lg {{ request()->routeIs('users.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->hasPermission('categories'))
                    <li>
                        <a href="{{ route('categories.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('categories.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/50 font-bold' : 'text-emerald-100/80 hover:bg-emerald-900/60 hover:text-white' }}">
                            <i class="bi bi-tags text-lg {{ request()->routeIs('categories.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->hasPermission('products'))
                    <li>
                        <a href="{{ route('products.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 {{ request()->routeIs('products.*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/50 font-bold' : 'text-emerald-100/80 hover:bg-emerald-900/60 hover:text-white' }}">
                            <i class="bi bi-box-seam text-lg {{ request()->routeIs('products.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                            <span>Products</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
            @endif
        </div>

        <!-- Sidebar Profile Card (Dynamic Footer) -->
        <div class="p-4 border-t border-emerald-900/80 bg-emerald-950/80">
            <div class="flex items-center gap-3 bg-emerald-900/50 p-2.5 rounded-xl border border-emerald-800/50">
                <img src="{{ Auth::user()->avatar_url }}" alt="Profile Image" class="w-10 h-10 rounded-full object-cover border-2 border-emerald-500 shadow-sm">
                <div class="overflow-hidden">
                    <div class="font-bold text-sm text-white truncate max-w-[130px]">{{ Auth::user()->name ?? 'User' }}</div>
                    <div class="text-xs text-emerald-300/80 font-medium truncate">{{ ucfirst(Auth::user()->role->name ?? 'Role') }}</div>
                </div>
            </div>
        </div>
    </aside>
    <!-- ==========================================
         END: Sidebar Component
         ========================================== -->


    <!-- ==========================================
         START: Main Content Area
         ========================================== -->
    <div class="main-wrapper xl:pl-64 flex flex-col min-h-screen transition-all duration-300">

        <!-- START: Top Navbar Component -->
        <header class="sticky top-0 z-20 bg-white/90 backdrop-blur-md border-b border-slate-200/80 px-4 lg:px-8 py-3 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <!-- Mobile sidebar toggle -->
                <button class="xl:hidden w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 transition-colors" 
                        id="sidebar-toggle" aria-label="Toggle Navigation">
                    <i class="bi bi-list text-xl"></i>
                </button>

                <!-- Resto Location & Live Operational Clock -->
                <div class="hidden md:flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-full border border-slate-200 text-xs shadow-2xs">
                    <i class="bi bi-shop text-emerald-600 text-sm"></i>
                    <span class="font-semibold text-slate-700">Coffe Shop PPKD Jakarta Pusat</span>
                    <span class="text-slate-300">|</span>
                    <i class="bi bi-clock text-slate-400"></i>
                    <span id="live-resto-clock" class="font-bold text-emerald-700 font-mono">--:--:-- WIB</span>
                </div>
            </div>

            <!-- Right actions -->
            <div class="flex items-center gap-3">
                <!-- Active Shift Status -->
                <div class="hidden lg:flex items-center">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Shift Operasional Aktif
                    </span>
                </div>

                <!-- Fullscreen Toggle -->
                <button class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 transition-colors" 
                        aria-label="Toggle Fullscreen" id="btn-fullscreen" title="Mode Layar Penuh">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative" id="profile-dropdown-container">
                    <button class="flex items-center gap-2.5 p-1.5 pr-3 rounded-full bg-slate-100 hover:bg-emerald-50 transition-colors border border-slate-200 cursor-pointer" 
                            type="button" id="profile-dropdown-btn">
                        <img src="{{ Auth::user()->avatar_url }}" alt="Profile Image" class="w-8 h-8 rounded-full object-cover border border-emerald-500">
                        <span class="hidden md:inline font-bold text-xs text-slate-800">{{ Auth::user()->name ?? 'User' }}</span>
                        <i class="bi bi-chevron-down text-xs text-slate-500"></i>
                    </button>
                    
                    <div class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50" id="profile-dropdown-menu">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <div class="font-bold text-sm text-slate-800">Halo, {{ Auth::user()->name ?? 'User' }}!</div>
                            <div class="text-xs text-emerald-600 font-semibold flex items-center gap-1 mt-0.5">
                                <i class="bi bi-shield me-1"></i> Level: {{ ucfirst(Auth::user()->role->name ?? '-') }}
                            </div>
                        </div>
                        <div class="py-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 text-left text-sm text-rose-600 hover:bg-rose-50 flex items-center gap-2 transition-colors cursor-pointer">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- END: Top Navbar Component -->

        <!-- START: Main Body Container -->
        <main class="flex-grow p-4 lg:p-8 max-w-7xl w-full mx-auto">
            <!-- Page Header Banner -->
            @yield('header')

            @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <i class="bi bi-check-circle-fill text-emerald-600 text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="text-emerald-600 hover:text-emerald-800" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-rose-600 text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="text-rose-600 hover:text-rose-800" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>
            </div>
            @endif

            <!-- Main Page Content -->
            @yield('content')
        </main>
        <!-- END: Main Body Container -->

        <!-- Footer -->
        <div class="px-4 lg:px-8 max-w-7xl w-full mx-auto">
            @include('inc.footer')
        </div>

    </div>
    <!-- ==========================================
         END: Main Content Area
         ========================================== -->

    @include('inc.js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Live Resto Clock
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

            // Mobile Sidebar Toggle
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                });
            }

            // Profile Dropdown Toggle
            const dropdownBtn = document.getElementById('profile-dropdown-btn');
            const dropdownMenu = document.getElementById('profile-dropdown-menu');
            if (dropdownBtn && dropdownMenu) {
                dropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('hidden');
                });
                document.addEventListener('click', function() {
                    dropdownMenu.classList.add('hidden');
                });
            }

            // Fullscreen Toggle
            const btnFullscreen = document.getElementById('btn-fullscreen');
            if (btnFullscreen) {
                btnFullscreen.addEventListener('click', function() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(err => {});
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>