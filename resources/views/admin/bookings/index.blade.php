@extends('admin.layouts.app')

@section('title', 'Manajemen Booking')
@section('header', 'Manajemen Booking')
@section('subtitle', 'Kelola seluruh pemesanan rental mobil')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Booking</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Daftar Booking</h3>
            <p class="text-sm text-gray-500">Total {{ $bookings->total() ?? 0 }} booking</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.bookings.calendar') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                <i data-lucide="calendar" class="w-4 h-4"></i> Kalender
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak
            </button>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-1.5 flex flex-wrap gap-1.5">
        <a href="{{ route('admin.bookings.index') }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ !request('status') ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Semua
        </a>
        <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Menunggu
        </a>
        <a href="{{ route('admin.bookings.index', ['status' => 'confirmed']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') == 'confirmed' ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Dikonfirmasi
        </a>
        <a href="{{ route('admin.bookings.index', ['status' => 'active']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') == 'active' ? 'bg-green-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Aktif
        </a>
        <a href="{{ route('admin.bookings.index', ['status' => 'completed']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') == 'completed' ? 'bg-gray-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Selesai
        </a>
        <a href="{{ route('admin.bookings.index', ['status' => 'cancelled']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') == 'cancelled' ? 'bg-red-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Dibatalkan
        </a>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <div class="flex-1 relative">
                <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode booking, nama pelanggan..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                Cari
            </button>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Kode</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Pelanggan</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Mobil</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Tanggal Sewa</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Total</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($bookings ?? [] as $booking)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6">
                            <span class="text-sm font-semibold text-primary-600">#{{ $booking->booking_code }}</span>
                            <p class="text-xs text-gray-500">{{ $booking->created_at ? \Carbon\Carbon::parse($booking->created_at)->format('d M Y') : '' }}</p>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-sm font-medium text-gray-800">{{ $booking->user->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->user->phone ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                    @if($booking->car->image ?? false)
                                        <img src="{{ asset('storage/' . $booking->car->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i data-lucide="car" class="w-4 h-4 text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm text-gray-800">{{ $booking->car->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $booking->car->plate_number ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 hidden md:table-cell">
                            <p class="text-sm text-gray-800">{{ $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d M Y') : '-' }}</p>
                            <p class="text-xs text-gray-500">s/d {{ $booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->format('d M Y') : '-' }}</p>
                        </td>
                        <td class="py-4 px-6 hidden lg:table-cell">
                            <p class="text-sm font-semibold text-gray-800">Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</p>
                        </td>
                        <td class="py-4 px-6">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'confirmed' => 'bg-blue-100 text-blue-700',
                                    'active' => 'bg-green-100 text-green-700',
                                    'completed' => 'bg-gray-100 text-gray-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                                $statusLabels = [
                                    'pending' => 'Menunggu',
                                    'confirmed' => 'Dikonfirmasi',
                                    'active' => 'Aktif',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$booking->status] ?? $booking->status }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="p-2 text-gray-500 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center">
                            <i data-lucide="calendar-x" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                            <p class="text-lg font-semibold text-gray-600 mb-2">Belum ada booking</p>
                            <p class="text-sm text-gray-500">Booking dari pelanggan akan muncul di sini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($bookings) && $bookings->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
