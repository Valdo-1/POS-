<!DOCTYPE html>
<html lang="id" class="h-full bg-[#080604] text-stone-100 antialiased selection:bg-amber-900/40 selection:text-amber-200">

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

<body class="min-h-screen flex flex-col font-sans ambient-coffee-bg relative text-stone-200 antialiased selection:bg-amber-900/50 overflow-x-hidden">

    <!-- Ambient Amber Glow Orbs -->
    <div class="fixed -top-32 -left-32 w-[30rem] h-[30rem] bg-amber-800/15 rounded-full blur-[100px] pointer-events-none warm-orb z-0"></div>
    <div class="fixed -bottom-36 right-10 w-[32rem] h-[32rem] bg-amber-950/25 rounded-full blur-[110px] pointer-events-none warm-orb z-0" style="animation-delay: 4s;"></div>

    <!-- ==========================================
         START: Sidebar Component (Dark Warm Espresso)
         ========================================== -->
    <aside class="sidebar-wrapper fixed inset-y-0 left-0 w-64 bg-[#0d0a08]/95 backdrop-blur-2xl text-stone-200 flex flex-col z-30 shadow-2xl transition-transform duration-300 border-r border-amber-900/30 -translate-x-full xl:translate-x-0" id="sidebar">
        <!-- Brand Logo / Identity -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 px-6 py-5 border-b border-amber-900/30 hover:bg-amber-950/40 transition-colors">
            <div class="relative w-9 h-9 rounded-xl bg-gradient-to-tr from-amber-700 via-amber-600 to-amber-400 p-[1px] shadow-glow-amber shrink-0">
                <div class="w-full h-full bg-[#100b08] rounded-[11px] flex items-center justify-center">
                    <i data-lucide="coffee" class="w-4 h-4 text-amber-300"></i>
                </div>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-base font-black tracking-wider text-amber-100 uppercase">KETARA</span>
                    <span class="text-[9px] font-mono tracking-widest px-1.5 py-0.5 rounded bg-amber-900/40 text-amber-300 border border-amber-700/40 font-semibold uppercase">POS</span>
                </div>
                <p class="text-[10px] text-stone-400 font-medium tracking-wide">PPKD Jakarta Pusat</p>
            </div>
        </a>

        <!-- Navigation Menu -->
        <div class="flex-grow overflow-y-auto px-4 py-5 space-y-6">
            <!-- Group: Menu Utama -->
            <div>
                <div class="px-3 text-[10px] font-mono font-bold text-amber-400/80 uppercase tracking-widest mb-2.5">Main Navigation</div>
                <ul class="space-y-1.5">
                    <li>
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-amber-900/40 border border-amber-600/40 text-amber-200 shadow-sm' : 'text-stone-400 hover:text-amber-200 hover:bg-stone-900/60' }}">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-amber-300' : 'text-stone-500' }}"></i>
                            <span>DASHBOARD</span>
                        </a>
                    </li>
                    
                    {{-- menu transaksi POS kasir --}}
                    @if(Auth::check() && Auth::user()->hasPermission('pos'))
                    <li>
                        <a href="{{ route('transactions.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('transactions.*') ? 'bg-gradient-to-r from-amber-700 to-amber-600 text-amber-50 shadow-glow-amber border border-amber-500/40' : 'text-amber-300 hover:bg-amber-900/30 border border-amber-800/30' }}">
                            <i data-lucide="shopping-bag" class="w-4 h-4 text-amber-300"></i>
                            <span>PESANAN DISINI (POS)</span>
                        </a>
                    </li>
                    @endif

                    {{-- menu stok produk --}}
                    @if(Auth::check() && Auth::user()->hasPermission('stock'))
                    <li>
                        <a href="{{ route('stock.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('stock.*') ? 'bg-amber-900/40 border border-amber-600/40 text-amber-200 shadow-sm' : 'text-stone-400 hover:text-amber-200 hover:bg-stone-900/60' }}">
                            <i data-lucide="boxes" class="w-4 h-4 {{ request()->routeIs('stock.*') ? 'text-amber-300' : 'text-stone-500' }}"></i>
                            <span>STOK PRODUK</span>
                        </a>
                    </li>
                    @endif

                    {{-- laporan omzet dan penjualan --}}
                    @if(Auth::check() && Auth::user()->hasPermission('reports'))
                    <li>
                        <a href="{{ route('reports.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-amber-900/40 border border-amber-600/40 text-amber-200 shadow-sm' : 'text-stone-400 hover:text-amber-200 hover:bg-stone-900/60' }}">
                            <i data-lucide="trending-up" class="w-4 h-4 {{ request()->routeIs('reports.*') ? 'text-amber-300' : 'text-stone-500' }}"></i>
                            <span>LAPORAN PENJUALAN</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

            {{-- grup master data --}}
            @if(Auth::check() && (Auth::user()->hasPermission('roles') || Auth::user()->hasPermission('users') || Auth::user()->hasPermission('categories') || Auth::user()->hasPermission('products')))
            <div>
                <div class="px-3 text-[10px] font-mono font-bold text-amber-400/80 uppercase tracking-widest mb-2.5">Master Controls</div>
                <ul class="space-y-1.5">
                    @if(Auth::user()->hasPermission('roles'))
                    <li>
                        <a href="{{ route('roles.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('roles.*') ? 'bg-amber-900/40 border border-amber-600/40 text-amber-200 shadow-sm' : 'text-stone-400 hover:text-amber-200 hover:bg-stone-900/60' }}">
                            <i data-lucide="shield-check" class="w-4 h-4 {{ request()->routeIs('roles.*') ? 'text-amber-300' : 'text-stone-500' }}"></i>
                            <span>Roles & Izin</span>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->hasPermission('users'))
                    <li>
                        <a href="{{ route('users.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-amber-900/40 border border-amber-600/40 text-amber-200 shadow-sm' : 'text-stone-400 hover:text-amber-200 hover:bg-stone-900/60' }}">
                            <i data-lucide="users" class="w-4 h-4 {{ request()->routeIs('users.*') ? 'text-amber-300' : 'text-stone-500' }}"></i>
                            <span>Kelola User</span>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->hasPermission('categories'))
                    <li>
                        <a href="{{ route('categories.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('categories.*') ? 'bg-amber-900/40 border border-amber-600/40 text-amber-200 shadow-sm' : 'text-stone-400 hover:text-amber-200 hover:bg-stone-900/60' }}">
                            <i data-lucide="tags" class="w-4 h-4 {{ request()->routeIs('categories.*') ? 'text-amber-300' : 'text-stone-500' }}"></i>
                            <span>Kategori Menu</span>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->hasPermission('products'))
                    <li>
                        <a href="{{ route('products.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 {{ request()->routeIs('products.*') ? 'bg-amber-900/40 border border-amber-600/40 text-amber-200 shadow-sm' : 'text-stone-400 hover:text-amber-200 hover:bg-stone-900/60' }}">
                            <i data-lucide="package" class="w-4 h-4 {{ request()->routeIs('products.*') ? 'text-amber-300' : 'text-stone-500' }}"></i>
                            <span>Katalog Produk</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
            @endif
        </div>

        <!-- Sidebar Profile Card (Dynamic Footer) -->
        <div class="p-4 border-t border-amber-900/30 bg-[#080604]/80">
            <div class="flex items-center gap-3 bg-stone-900/70 p-3 rounded-2xl border border-amber-900/30">
                <img src="{{ Auth::user()->avatar_url }}" alt="Profile Image" class="w-9 h-9 rounded-xl object-cover border border-amber-500/40 shadow-sm shrink-0">
                <div class="overflow-hidden min-w-0">
                    <div class="font-bold text-xs text-amber-100 truncate">{{ Auth::user()->name ?? 'User' }}</div>
                    <div class="text-[10px] font-mono text-amber-400/80 truncate uppercase tracking-wider">{{ Auth::user()->role->name ?? 'Role' }}</div>
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
    <div class="main-wrapper xl:pl-64 flex flex-col min-h-screen z-10 transition-all duration-300">

        <!-- START: Top Navbar Component -->
        <header class="sticky top-0 z-20 bg-[#0c0907]/85 backdrop-blur-md border-b border-amber-900/20 px-4 lg:px-8 h-16 flex items-center justify-between shrink-0 shadow-sm">
            <div class="flex items-center gap-3">
                <!-- Mobile sidebar toggle -->
                <button class="xl:hidden w-9 h-9 rounded-xl bg-stone-900 border border-amber-900/30 text-amber-300 flex items-center justify-center hover:bg-stone-800 transition" 
                        id="sidebar-toggle" aria-label="Toggle Navigation">
                    <i data-lucide="menu" class="w-4 h-4"></i>
                </button>

                <!-- Resto Location & Live Operational Clock -->
                <div class="flex items-center gap-3 text-xs font-mono">
                    <div class="hidden md:flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-stone-900/70 border border-amber-900/30 text-stone-300">
                        <i data-lucide="store" class="w-3.5 h-3.5 text-amber-400"></i>
                        <span class="font-semibold text-[11px]">KETARA PPKD Jakarta Pusat</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-stone-900/70 border border-amber-900/30 text-stone-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <span id="live-resto-clock" class="tabular-nums font-medium text-[11px] text-amber-200/90">--:--:-- WIB</span>
                    </div>
                </div>
            </div>

            <!-- Right actions -->
            <div class="flex items-center gap-3">
                <!-- Active User Badge -->
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-stone-900/70 border border-amber-900/30 text-amber-300 font-mono text-[11px] font-semibold uppercase">
                    <i data-lucide="user-check" class="w-3.5 h-3.5 text-amber-400"></i>
                    <span>{{ Auth::user()->role->name ?? 'POS' }}: {{ Auth::user()->name }}</span>
                </div>

                <!-- Fullscreen Toggle -->
                <button class="w-9 h-9 rounded-xl bg-stone-900/80 border border-amber-900/30 text-stone-400 hover:text-amber-200 flex items-center justify-center hover:bg-stone-800 transition" 
                        aria-label="Toggle Fullscreen" id="btn-fullscreen" title="Mode Layar Penuh">
                    <i data-lucide="maximize-2" class="w-4 h-4"></i>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative" id="profile-dropdown-container">
                    <button class="flex items-center gap-2.5 p-1 pr-3 rounded-xl bg-stone-900/80 hover:bg-stone-800 transition border border-amber-900/30 cursor-pointer" 
                            type="button" id="profile-dropdown-btn">
                        <img src="{{ Auth::user()->avatar_url }}" alt="Profile Image" class="w-7 h-7 rounded-lg object-cover border border-amber-500/40">
                        <span class="hidden md:inline font-bold text-xs text-amber-100">{{ Auth::user()->name ?? 'User' }}</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-stone-400"></i>
                    </button>
                    
                    <div class="hidden absolute right-0 mt-2 w-56 glass-espresso rounded-2xl shadow-2xl border border-amber-700/30 py-2 z-50" id="profile-dropdown-menu">
                        <div class="px-4 py-2.5 border-b border-amber-900/30">
                            <div class="font-bold text-xs text-amber-100">Halo, {{ Auth::user()->name ?? 'User' }}!</div>
                            <div class="text-[10px] font-mono text-amber-400 font-semibold flex items-center gap-1 mt-0.5 uppercase">
                                <i data-lucide="shield" class="w-3 h-3 me-1 text-amber-400"></i> Level: {{ Auth::user()->role->name ?? '-' }}
                            </div>
                        </div>
                        <div class="py-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 text-left text-xs font-semibold text-rose-400 hover:bg-rose-950/40 flex items-center gap-2 transition cursor-pointer">
                                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i> Logout Aplikasi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- END: Top Navbar Component -->

        <!-- START: Main Body Container -->
        <main class="flex-grow p-4 lg:p-7 max-w-7xl w-full mx-auto space-y-6">
            <!-- Page Header Banner -->
            @yield('header')

            @if(session('success'))
            <div class="p-4 rounded-2xl bg-amber-950/40 border border-amber-600/40 text-amber-200 text-xs flex items-center justify-between shadow-lg backdrop-blur-md">
                <div class="flex items-center gap-2.5">
                    <i data-lucide="check-circle" class="w-4 h-4 text-amber-400 shrink-0"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" class="text-stone-400 hover:text-amber-200" onclick="this.parentElement.remove()"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
            @endif

            @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-950/40 border border-rose-600/40 text-rose-200 text-xs flex items-center justify-between shadow-lg backdrop-blur-md">
                <div class="flex items-center gap-2.5">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-400 shrink-0"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button type="button" class="text-stone-400 hover:text-rose-200" onclick="this.parentElement.remove()"><i data-lucide="x" class="w-4 h-4"></i></button>
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
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const clockEl = document.getElementById('live-resto-clock');
                if (clockEl) {
                    clockEl.innerText = `${hours}:${minutes}:${seconds} WIB`;
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

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>

</html>