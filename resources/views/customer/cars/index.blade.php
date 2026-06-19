@extends('customer.layouts.app')

@section('title', 'Daftar Mobil')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Daftar Mobil</h1>
    <p class="text-gray-500">Pilih mobil terbaik untuk perjalanan Anda</p>
</div>

{{-- Search & Filter --}}
<div class="bg-white rounded-xl shadow-md p-6 mb-8">
    <form action="{{ route('cars.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau brand..." class="input-field">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
            <select name="brand" class="input-field">
                <option value="">Semua Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Transmisi</label>
            <select name="transmission" class="input-field">
                <option value="">Semua</option>
                <option value="manual" {{ request('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                <option value="automatic" {{ request('transmission') == 'automatic' ? 'selected' : '' }}>Automatic</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Max</label>
            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Rp Maksimal" class="input-field">
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn-primary w-full">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Cari
            </button>
        </div>
    </form>
</div>

{{-- Car Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($cars as $car)
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
                        <svg class="w-4 h-4 {{ $i <= $car->getAverageRating() ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                    <span class="text-sm text-gray-500 ml-1">({{ $car->getReviewCount() }})</span>
                </div>
                <div class="flex items-center text-sm text-gray-500 mb-3">
                    <span class="mr-3">👤 {{ $car->capacity }} kursi</span>
                    <span>⛽ {{ ucfirst($car->fuel_type) }}</span>
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
        <div class="col-span-3 text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="text-xl font-medium text-gray-500">Tidak ada mobil ditemukan</h3>
            <p class="text-gray-400">Coba ubah filter pencarian Anda</p>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $cars->withQueryString()->links() }}
</div>
@endsection
