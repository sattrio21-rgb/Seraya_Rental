@extends('admin.layouts.app')

@section('title', 'Detail Booking #' . ($booking->booking_code ?? ''))
@section('header', 'Detail Booking')
@section('subtitle', 'Informasi lengkap pemesanan #' . ($booking->booking_code ?? ''))

@section('breadcrumb')
    <span class="mx-1">/</span>
    <a href="{{ route('admin.bookings.index') }}" class="hover:text-primary-600">Booking</a>
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">#{{ $booking->booking_code ?? '' }}</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h3 class="text-xl font-bold text-gray-800">Booking #{{ $booking->booking_code ?? '' }}</h3>
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
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$booking->status ?? 'pending'] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $statusLabels[$booking->status ?? 'pending'] ?? $booking->status }}
                </span>
            </div>
            <p class="text-sm text-gray-500">Dibuat pada {{ $booking->created_at ? \Carbon\Carbon::parse($booking->created_at)->format('d M Y H:i') : '-' }}</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Customer Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-primary-600"></i>
                    Data Pelanggan
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nama Lengkap</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $booking->user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Telepon</p>
                        <p class="text-sm text-gray-800">{{ $booking->user->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Email</p>
                        <p class="text-sm text-gray-800">{{ $booking->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">NIK / KTP</p>
                        <p class="text-sm text-gray-800">{{ $booking->user->nik ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Rental Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="car" class="w-5 h-5 text-primary-600"></i>
                    Detail Sewa
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Mobil</p>
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
                                <p class="text-sm font-semibold text-gray-800">{{ $booking->car->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $booking->car->plate_number ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Lokasi Pengambilan</p>
                        <p class="text-sm text-gray-800">{{ $booking->pickup_location ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Mulai</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d M Y H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Selesai</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->format('d M Y H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Durasi</p>
                        <p class="text-sm text-gray-800">{{ $booking->start_date && $booking->end_date ? \Carbon\Carbon::parse($booking->start_date)->diffInDays(\Carbon\Carbon::parse($booking->end_date)) . ' hari' : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Driver</p>
                        <p class="text-sm text-gray-800">{{ $booking->rental_type == 'with_driver' ? 'Ya' : 'Tidak' }}</p>
                    </div>
                </div>
                @if($booking->notes ?? false)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">Catatan</p>
                    <p class="text-sm text-gray-800">{{ $booking->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-5 h-5 text-primary-600"></i>
                    Rincian Pembayaran
                </h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Tipe Sewa</span>
                        <span class="text-sm text-gray-800">{{ $booking->rental_type == 'with_driver' ? 'Dengan Driver' : 'Lepas Kunci' }}</span>
                    </div>
                    @if(($booking->discount_amount ?? 0) > 0)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Diskon Promo</span>
                        <span class="text-sm text-green-600">- Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <span class="text-base font-bold text-gray-800">Total</span>
                        <span class="text-lg font-bold text-primary-600">Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">

            <!-- Status Update -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-5 h-5 text-primary-600"></i>
                    Update Status
                </h4>
                <form action="{{ route('admin.bookings.status', $booking->id ?? 0) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="pending" {{ ($booking->status ?? '') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="confirmed" {{ ($booking->status ?? '') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="active" {{ ($booking->status ?? '') == 'active' ? 'selected' : '' }}>Aktif (Sedang Disewa)</option>
                        <option value="completed" {{ ($booking->status ?? '') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ ($booking->status ?? '') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <textarea name="status_notes" rows="3" placeholder="Catatan perubahan status (opsional)"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                    <button type="submit" class="w-full px-4 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                        <i data-lucide="check-circle" class="w-4 h-4 inline mr-1"></i> Perbarui Status
                    </button>
                </form>
            </div>

            <!-- Payment Status -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="wallet" class="w-5 h-5 text-primary-600"></i>
                    Status Pembayaran
                </h4>
                @php
                    $paymentColors = [
                        'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        'paid' => 'bg-green-100 text-green-700 border-green-200',
                        'partial' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'refunded' => 'bg-orange-100 text-orange-700 border-orange-200',
                        'failed' => 'bg-red-100 text-red-700 border-red-200',
                    ];
                @endphp
                @php
                    $latestPayment = $booking->payments->first();
                    $paymentStatus = $latestPayment ? $latestPayment->status : 'pending';
                @endphp
                <div class="p-3 rounded-xl border {{ $paymentColors[$paymentStatus] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                    <div class="flex items-center gap-2">
                        <i data-lucide="{{ $paymentStatus == 'verified' ? 'check-circle' : 'clock' }}" class="w-5 h-5"></i>
                        <span class="text-sm font-semibold capitalize">{{ $paymentStatus == 'verified' ? 'Terverifikasi' : ($paymentStatus == 'rejected' ? 'Ditolak' : 'Menunggu') }}</span>
                    </div>
                </div>
                @if($latestPayment)
                <div class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Metode</span>
                        <span class="text-gray-800">{{ $latestPayment->payment_method ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal</span>
                        <span class="text-gray-800">{{ $latestPayment->verified_at ? \Carbon\Carbon::parse($latestPayment->verified_at)->format('d M Y') : '-' }}</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Status History -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="history" class="w-5 h-5 text-primary-600"></i>
                    Riwayat Status
                </h4>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-3 h-3 bg-primary-500 rounded-full mt-1.5"></div>
                            <div class="w-px flex-1 bg-gray-200"></div>
                        </div>
                        <div class="pb-4">
                            <p class="text-sm font-semibold text-gray-800">{{ $statusLabels[$booking->status] ?? $booking->status }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->created_at ? \Carbon\Carbon::parse($booking->created_at)->format('d M Y H:i') : '' }}</p>
                            @if($booking->cancelled_reason)
                                <p class="text-xs text-gray-500 mt-1">{{ $booking->cancelled_reason }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
