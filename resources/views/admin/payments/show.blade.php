@extends('admin.layouts.app')

@section('title', 'Detail Pembayaran #' . ($payment->payment_code ?? $payment->id ?? ''))
@section('header', 'Detail Pembayaran')
@section('subtitle', 'Informasi lengkap transaksi pembayaran')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <a href="{{ route('admin.payments.index') }}" class="hover:text-primary-600">Pembayaran</a>
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">#{{ $payment->payment_code ?? $payment->id ?? '' }}</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h3 class="text-xl font-bold text-gray-800">Pembayaran #{{ $payment->payment_code ?? $payment->id ?? '' }}</h3>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'verified' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-700',
                    ];
                    $statusLabels = [
                        'pending' => 'Menunggu',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$payment->status ?? 'pending'] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $statusLabels[$payment->status ?? 'pending'] ?? $payment->status }}
                </span>
            </div>
            <p class="text-sm text-gray-500">{{ $payment->created_at ? \Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i') : '-' }}</p>
        </div>
        <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Payment Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-5 h-5 text-primary-600"></i>
                    Informasi Pembayaran
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Kode Pembayaran</p>
                        <p class="text-sm font-semibold text-gray-800 font-mono">{{ $payment->payment_code ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Metode Pembayaran</p>
                        <div class="flex items-center gap-2">
                            <i data-lucide="{{ ($payment->payment_method ?? '') == 'bank_transfer' ? 'building-2' : (($payment->payment_method ?? '') == 'cash' ? 'banknote' : 'credit-card') }}" class="w-5 h-5 text-gray-400"></i>
                            <span class="text-sm font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? '-')) }}</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Jumlah Pembayaran</p>
                        <p class="text-2xl font-bold text-primary-600">Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Pembayaran</p>
                        <p class="text-sm text-gray-800">{{ $payment->verified_at ? \Carbon\Carbon::parse($payment->verified_at)->format('d M Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Booking Reference -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="calendar-check" class="w-5 h-5 text-primary-600"></i>
                    Informasi Booking
                </h4>
                @if($payment->booking ?? false)
                <div class="p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <a href="{{ route('admin.bookings.show', $payment->booking_id) }}" class="text-sm font-semibold text-primary-600 hover:underline">#{{ $payment->booking->booking_code }}</a>
                            <p class="text-xs text-gray-500">{{ $payment->booking->car->name ?? '-' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-800">Rp {{ number_format($payment->booking->total_price ?? 0, 0, ',', '.') }}</p>
                            @php
                                $bookingStatusColors = ['pending' => 'bg-yellow-100 text-yellow-700', 'confirmed' => 'bg-blue-100 text-blue-700', 'active' => 'bg-green-100 text-green-700', 'completed' => 'bg-gray-100 text-gray-700', 'cancelled' => 'bg-red-100 text-red-700'];
                                $bookingStatusLabels = ['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'active' => 'Aktif', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
                            @endphp
                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $bookingStatusColors[$payment->booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $bookingStatusLabels[$payment->booking->status] ?? $payment->booking->status }}
                            </span>
                        </div>
                    </div>
                </div>
                @else
                <p class="text-sm text-gray-500">Data booking tidak tersedia</p>
                @endif
            </div>

            <!-- Proof of Payment -->
            @if($payment->payment_proof ?? false)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="image" class="w-5 h-5 text-primary-600"></i>
                    Bukti Pembayaran
                </h4>
                <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank" class="block w-full max-w-md bg-gray-100 rounded-xl overflow-hidden border border-gray-200 hover:shadow-md transition-shadow">
                    <img src="{{ asset('storage/' . $payment->payment_proof) }}" alt="Bukti Pembayaran" class="w-full h-auto">
                </a>
            </div>
            @endif

            <!-- Notes -->
            @if($payment->notes ?? false)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-5 h-5 text-primary-600"></i>
                    Catatan
                </h4>
                <p class="text-sm text-gray-600">{{ $payment->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">

            <!-- Action Panel -->
            @if(($payment->status ?? '') == 'pending')
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-5 h-5 text-primary-600"></i>
                    Verifikasi Pembayaran
                </h4>
                <p class="text-sm text-gray-500 mb-4">Tinjau bukti pembayaran dan verifikasi transaksi ini.</p>
                <div class="space-y-3">
                    <form action="{{ route('admin.payments.verify', $payment->id ?? 0) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition-colors">
                            <i data-lucide="check-circle" class="w-5 h-5"></i> Verifikasi & Setujui
                        </button>
                    </form>
                    <form action="{{ route('admin.payments.reject', $payment->id ?? 0) }}" method="POST">
                        @csrf
                        <textarea name="reason" rows="2" placeholder="Alasan penolakan (opsional)"
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 mb-3"></textarea>
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition-colors">
                            <i data-lucide="x-circle" class="w-5 h-5"></i> Tolak Pembayaran
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Payment History -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="history" class="w-5 h-5 text-primary-600"></i>
                    Riwayat
                </h4>
                <div class="space-y-4">
                    @forelse($payment->history ?? [] as $history)
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-3 h-3 bg-primary-500 rounded-full mt-1.5"></div>
                            <div class="w-px flex-1 bg-gray-200"></div>
                        </div>
                        <div class="pb-4">
                            <p class="text-sm font-semibold text-gray-800">{{ $history->action ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $history->created_at ? \Carbon\Carbon::parse($history->created_at)->format('d M Y H:i') : '' }}</p>
                            @if($history->notes)
                                <p class="text-xs text-gray-500 mt-1">{{ $history->notes }}</p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">Belum ada riwayat</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
