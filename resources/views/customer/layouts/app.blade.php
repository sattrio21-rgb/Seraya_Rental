<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Seraya') }} - @yield('title', 'Sewa Mobil Terpercaya')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd',
                            400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8',
                            800: '#1e40af', 900: '#1e3a8a', 950: '#172554',
                        },
                    },
                },
            },
        }
    </script>
    <style>
        .btn-primary { @apply bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors duration-200 font-medium; }
        .btn-secondary { @apply bg-white text-primary-600 border border-primary-600 px-4 py-2 rounded-lg hover:bg-primary-50 transition-colors duration-200 font-medium; }
        .btn-danger { @apply bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium; }
        .card { @apply bg-white rounded-xl shadow-md overflow-hidden; }
        .input-field { @apply w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500; }
        .badge-success { @apply bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium; }
        .badge-warning { @apply bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium; }
        .badge-danger { @apply bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium; }
        .badge-info { @apply bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium; }
    </style>
    @stack('styles')
</head>
<body class="h-full">
    {{-- Navbar --}}
    <nav class="bg-primary-600 shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('storage/logo/seraya-logo.png') }}" alt="Seraya" class="h-10" onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                        <span class="text-2xl font-bold text-white" style="display:none;">Seraya</span>
                    </a>
                    <div class="hidden md:flex md:ml-10 md:space-x-8">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-white hover:text-primary-200">Beranda</a>
                        <a href="{{ route('cars.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-primary-100 hover:text-white">Daftar Mobil</a>
                        <a href="{{ route('promos.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-primary-100 hover:text-white">Promo</a>
                        <a href="{{ route('contact') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-primary-100 hover:text-white">Kontak</a>
                    </div>
                </div>
                <div class="flex items-center">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm font-medium text-white hover:text-primary-200">
                                <span class="mr-2">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                                <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Riwayat Booking</a>
                                <a href="{{ route('documents.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dokumen</a>
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Panel</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 mr-4">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary text-sm">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg mb-4">
                {{ session('warning') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">
                        <img src="{{ asset('storage/logo/seraya-logo.png') }}" alt="Seraya" class="h-8 inline" onerror="this.style.display='none';this.nextElementSibling.style.display='inline';">
                        <span style="display:none;">Seraya</span>
                    </h3>
                    <p class="text-gray-400 text-sm">Sewa mobil mudah, aman, dan terpercaya. Harga terjangkau dengan pelayanan terbaik.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Menu</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white">Beranda</a></li>
                        <li><a href="{{ route('cars.index') }}" class="hover:text-white">Daftar Mobil</a></li>
                        <li><a href="{{ route('promos.index') }}" class="hover:text-white">Promo</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>📍 Jl. Contoh No. 123, Jakarta</li>
                        <li>📞 081234567890</li>
                        <li>✉️ info@seraya.com</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">WhatsApp</h4>
                    <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20menyewa%20mobil" target="_blank" class="inline-flex items-center bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Chat WhatsApp
                    </a>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
                &copy; {{ date('Y') }} Seraya. All rights reserved.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
