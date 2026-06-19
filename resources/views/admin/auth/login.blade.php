<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - Seraya</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd',
                            400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8',
                            800: '#1e40af', 900: '#1e3a8a',
                        },
                    },
                },
            },
        }
    </script>
</head>
<body class="h-full">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-600 to-primary-800 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-white">🚗 Seraya</h1>
                <h2 class="mt-6 text-2xl font-bold text-white">Login Admin</h2>
                <p class="mt-2 text-sm text-primary-200">Masukkan akun admin untuk mengakses panel</p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8">
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6">
                        @foreach($errors->all() as $error)
                            <p class="text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6">
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" name="email" type="email" required autofocus
                               class="mt-1 block w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 px-4 py-3"
                               placeholder="admin@seraya.com" value="{{ old('email') }}">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" required
                               class="mt-1 block w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 px-4 py-3"
                               placeholder="••••••••">
                    </div>

                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                    </div>

                    <button type="submit" class="w-full bg-primary-600 text-white px-4 py-3 rounded-lg font-medium hover:bg-primary-700 transition-colors">
                        Login Admin
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-primary-600 hover:text-primary-700">
                        Login sebagai Customer →
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
