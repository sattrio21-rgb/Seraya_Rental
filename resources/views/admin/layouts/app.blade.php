<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Rental Mobil</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Sidebar transition */
        .sidebar { transition: transform 0.3s ease-in-out; }
        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
        }

        /* Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 40;
        }
        .sidebar-overlay.active { display: block; }

        /* Active nav */
        .nav-link.active {
            background-color: rgba(59, 130, 246, 0.15);
            color: #2563eb;
            font-weight: 600;
        }
        .nav-link.active .nav-icon {
            color: #2563eb;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar fixed top-0 left-0 z-50 h-full w-64 bg-white border-r border-gray-200 shadow-lg lg:shadow-md flex flex-col">
        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100">
            <div class="w-9 h-9 bg-primary-600 rounded-lg flex items-center justify-center">
                <i data-lucide="car" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-primary-700">Rental Mobil</h1>
                <p class="text-xs text-gray-400">Panel Administrator</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Utama</p>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 nav-icon"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.cars.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors {{ request()->routeIs('admin.cars.*') ? 'active' : '' }}">
                <i data-lucide="car" class="w-5 h-5 nav-icon"></i>
                Mobil
            </a>

            <a href="{{ route('admin.bookings.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i data-lucide="calendar-check" class="w-5 h-5 nav-icon"></i>
                Booking
            </a>

            <a href="{{ route('admin.customers.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i data-lucide="users" class="w-5 h-5 nav-icon"></i>
                Pelanggan
            </a>

            <p class="px-3 mt-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Manajemen</p>

            <a href="{{ route('admin.promos.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors {{ request()->routeIs('admin.promos.*') ? 'active' : '' }}">
                <i data-lucide="tag" class="w-5 h-5 nav-icon"></i>
                Promo
            </a>

            <a href="{{ route('admin.documents.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                <i data-lucide="file-check" class="w-5 h-5 nav-icon"></i>
                Dokumen
            </a>

            <a href="{{ route('admin.payments.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i data-lucide="credit-card" class="w-5 h-5 nav-icon"></i>
                Pembayaran
            </a>

            <a href="{{ route('admin.deposits.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors {{ request()->routeIs('admin.deposits.*') ? 'active' : '' }}">
                <i data-lucide="wallet" class="w-5 h-5 nav-icon"></i>
                Deposit
            </a>

            <a href="{{ route('admin.reviews.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i data-lucide="star" class="w-5 h-5 nav-icon"></i>
                Ulasan
            </a>

            <p class="px-3 mt-4 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Lainnya</p>

            <a href="{{ route('admin.reports.revenue') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i data-lucide="bar-chart-3" class="w-5 h-5 nav-icon"></i>
                Laporan
            </a>

            <a href="{{ route('admin.notifications.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <i data-lucide="bell" class="w-5 h-5 nav-icon"></i>
                Notifikasi
                @if(isset($unreadNotifications) && $unreadNotifications > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $unreadNotifications }}</span>
                @endif
            </a>
        </nav>

        <!-- User Section -->
        <div class="border-t border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-primary-100 rounded-full flex items-center justify-center">
                    <i data-lucide="user" class="w-5 h-5 text-primary-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-400">Administrator</p>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors" title="Keluar">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen flex flex-col">

        <!-- Top Navbar -->
        <header class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
            <div class="flex items-center justify-between px-4 py-3">
                <!-- Left: Hamburger + Page Title -->
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">@yield('header', 'Dashboard')</h2>
                        @hasSection('subtitle')
                            <p class="text-xs text-gray-500">@yield('subtitle')</p>
                        @endif
                    </div>
                </div>

                <!-- Right: Actions -->
                <div class="flex items-center gap-2">
                    <!-- Notifications -->
                    <a href="{{ route('admin.notifications.index') }}" class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        @if(isset($unreadNotifications) && $unreadNotifications > 0)
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        @endif
                    </a>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                <i data-lucide="user" class="w-4 h-4 text-primary-600"></i>
                            </div>
                            <span class="hidden sm:inline text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                        </button>

                        <div x-show="open" x-transition @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-gray-400">{{ Auth::user()->email ?? 'admin@email.com' }}</p>
                            </div>
                            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">
                                <i data-lucide="settings" class="w-4 h-4"></i> Pengaturan
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Breadcrumb -->
            @hasSection('breadcrumb')
            <div class="px-4 pb-3">
                <nav class="flex items-center gap-1 text-xs text-gray-500">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary-600">Beranda</a>
                    @yield('breadcrumb')
                </nav>
            </div>
            @endif
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 sm:p-6">
            <!-- Flash Messages -->
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500 flex-shrink-0"></i>
                <span class="text-sm">{{ session('success') }}</span>
                <button @click="show = false" class="ml-auto text-green-500 hover:text-green-700">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 flex-shrink-0"></i>
                <span class="text-sm">{{ session('error') }}</span>
                <button @click="show = false" class="ml-auto text-red-500 hover:text-red-700">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                    <span class="font-semibold text-sm">Terjadi kesalahan:</span>
                </div>
                <ul class="list-disc list-inside text-sm space-y-1 ml-7">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 px-6 py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-400">
                <p>&copy; {{ date('Y') }} Rental Mobil. Hak cipta dilindungi.</p>
                <p>Versi 1.0.0</p>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        // Close sidebar on route change (mobile)
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth < 1024 && sidebar.classList.contains('active')) {
                if (!sidebar.contains(e.target) && !e.target.closest('[onclick*="toggleSidebar"]')) {
                    toggleSidebar();
                }
            }
        });
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')
</body>
</html>
