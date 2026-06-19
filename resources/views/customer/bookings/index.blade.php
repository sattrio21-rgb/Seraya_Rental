@extends('customer.layouts.app')

@section('title', 'Riwayat Booking')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Riwayat Booking</h1>
    <p class="text-gray-500">Kelola semua pemesanan Anda</p>
</div>

{{-- Status Tabs --}}
<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('bookings.index') }}" class="px-4 py-2 rounded-lg {{ !request('status') ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }}">Semua</a>
    <a href="{{ route('bookings.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }}">Menunggu</a>
    <a href="{{ route('bookings.index', ['status' => 'confirmed']) }}" class="px-4 py-2 rounded-lg {{ request('status') == 'confirmed' ? 'bg-blue-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }}">Dikonfirmasi</a>
    <a href="{{ route('bookings.index', ['status' => 'active']) }}" class="px-4 py-2 rounded-lg {{ request('status') == 'active' ? 'bg-green-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }}">Aktif</a>
    <a href="{{ route('bookings.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-lg {{ request('status') == 'completed' ? 'bg-gray-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }}">Selesai</a>
</div>

<div class="space-y-4">
    @forelse($bookings as $booking)
        <div class="card p-6 hover:shadow-lg transition-shadow">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-start space-x-4">
                    <img src="{{ $booking->car->image ? asset('storage/' . $booking->car->image) : 'https://via.placeholder.com/100x60?text=Mobil' }}" alt="{{ $booking->car->name }}" class="w-20 h-14 object-cover rounded-lg">
                    <div>
                        <h3 class="font-bold text-lg">{{ $booking->car->name }}</h3>
                        <p class="text-sm text-gray-500">Kode: {{ $booking->booking_code }}</p>
                        <p class="text-sm text-gray-500">{{ $booking->start_date->format('d M Y') }} - {{ $booking->end_date->format('d M Y') }}</p>
                        <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $booking->rental_type)) }}</p>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 text-right">
                    <span class="badge-{{ $booking->getStatusColor() }}">{{ $booking->getStatusLabel() }}</span>
                    <div class="mt-2 text-lg font-bold text-primary-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                    <a href="{{ route('bookings.show', $booking) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">Lihat Detail →</a>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <h3 class="text-xl font-medium text-gray-500">Belum ada booking</h3>
            <a href="{{ route('cars.index') }}" class="btn-primary mt-4 inline-block">Sewa Mobil Sekarang</a>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $bookings->withQueryString()->links() }}
</div>
@endsection
