@extends('customer.layouts.app')

@section('title', 'Beranda')

@section('content')
{{-- Hero Section --}}
<div class="relative bg-primary-600 rounded-2xl overflow-hidden mb-12">
    <div class="absolute inset-0 bg-gradient-to-r from-primary-700 to-primary-500 opacity-90"></div>
    <div class="relative px-8 py-16 md:py-24 text-center">
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">Sewa Mobil Mudah & Terpercaya</h1>
        <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">Pilihan armada lengkap dengan harga terjangkau. Booking online dalam hitungan menit!</p>
        <a href="{{ route('cars.index') }}" class="inline-flex items-center bg-white text-primary-600 px-8 py-3 rounded-lg font-bold hover:bg-primary-50 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Cari Mobil
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
    <div class="bg-white rounded-xl shadow-md p-6 text-center">
        <div class="text-3xl font-bold text-primary-600">{{ $totalCars ?? 20 }}+</div>
        <div class="text-gray-500">Armada Mobil</div>
    </div>
    <div class="bg-white rounded-xl shadow-md p-6 text-center">
        <div class="text-3xl font-bold text-primary-600">{{ $totalCustomers ?? 500 }}+</div>
        <div class="text-gray-500">Pelanggan Puas</div>
    </div>
    <div class="bg-white rounded-xl shadow-md p-6 text-center">
        <div class="text-3xl font-bold text-primary-600">24/7</div>
        <div class="text-gray-500">Layanan</div>
    </div>
    <div class="bg-white rounded-xl shadow-md p-6 text-center">
        <div class="text-3xl font-bold text-primary-600">4.8⭐</div>
        <div class="text-gray-500">Rating</div>
    </div>
</div>

{{-- Featured Cars --}}
<section class="mb-12">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Mobil Tersedia</h2>
        <a href="{{ route('cars.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">Lihat Semua →</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($featuredCars ?? [] as $car)
            <div class="card hover:shadow-lg transition-shadow">
                <img src="{{ $car->image ? asset('storage/' . $car->image) : 'https://via.placeholder.com/400x250?text=Mobil' }}" alt="{{ $car->name }}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg">{{ $car->name }}</h3>
                        <span class="badge-info text-xs">{{ ucfirst($car->transmission) }}</span>
                    </div>
                    <p class="text-gray-500 text-sm mb-2">{{ $car->brand }} {{ $car->model }} • {{ $car->year }}</p>
                    <div class="flex items-center mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= ($car->reviews->avg('rating') ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                        <span class="text-sm text-gray-500 ml-1">({{ $car->reviews->count() }})</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-xl font-bold text-primary-600">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}</span>
                            <span class="text-gray-500 text-sm">/hari</span>
                        </div>
                        <a href="{{ route('cars.show', $car) }}" class="btn-primary text-sm">Detail</a>
                    </div>
                </div>
            </div>
        @empty
            @for($i = 0; $i < 3; $i++)
                <div class="card">
                    <img src="https://via.placeholder.com/400x250?text=Mobil+{{ $i+1 }}" alt="Mobil" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2">Toyota Avanza</h3>
                        <p class="text-gray-500 text-sm mb-2">Toyota Avanza • 2023</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-primary-600">Rp 350.000<span class="text-gray-500 text-sm">/hari</span></span>
                            <a href="{{ route('cars.index') }}" class="btn-primary text-sm">Detail</a>
                        </div>
                    </div>
                </div>
            @endfor
        @endforelse
    </div>
</section>

{{-- Why Choose Us --}}
<section class="mb-12">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-8">Mengapa Pilih Kami?</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="font-bold text-lg mb-2">Harga Terjangkau</h3>
            <p class="text-gray-500 text-sm">Harga sewa kompetitif tanpa biaya tersembunyi</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <h3 class="font-bold text-lg mb-2">Armada Lengkap</h3>
            <p class="text-gray-500 text-sm">Berbagai pilihan mobil sesuai kebutuhan Anda</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h3 class="font-bold text-lg mb-2">Driver Berpengalaman</h3>
            <p class="text-gray-500 text-sm">Driver profesional dan berpengalaman</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="font-bold text-lg mb-2">Layanan 24 Jam</h3>
            <p class="text-gray-500 text-sm">Siap melayani Anda kapan saja</p>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-primary-600 rounded-2xl p-8 md:p-12 text-center text-white">
    <h2 class="text-2xl md:text-3xl font-bold mb-4">Siap Untuk Menyewa Mobil?</h2>
    <p class="text-primary-100 mb-6 max-w-xl mx-auto">Booking sekarang dan dapatkan harga terbaik untuk perjalanan Anda!</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('cars.index') }}" class="bg-white text-primary-600 px-8 py-3 rounded-lg font-bold hover:bg-primary-50 transition-colors">Booking Sekarang</a>
        <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20menyewa%20mobil" target="_blank" class="bg-green-500 text-white px-8 py-3 rounded-lg font-bold hover:bg-green-600 transition-colors">Chat WhatsApp</a>
    </div>
</section>
@endsection
