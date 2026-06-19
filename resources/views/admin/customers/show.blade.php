@extends('admin.layouts.app')

@section('title', 'Detail Pelanggan')
@section('header', 'Detail Pelanggan')
@section('subtitle', 'Informasi lengkap pelanggan')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <a href="{{ route('admin.customers.index') }}" class="hover:text-primary-600">Pelanggan</a>
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">{{ $customer->name ?? 'Detail' }}</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center">
                <span class="text-2xl font-bold text-primary-600">{{ strtoupper(substr($customer->name ?? '?', 0, 1)) }}</span>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">{{ $customer->name ?? '-' }}</h3>
                <p class="text-sm text-gray-500">Bergabung {{ $customer->created_at ? \Carbon\Carbon::parse($customer->created_at)->format('d M Y') : '-' }}</p>
            </div>
        </div>
        <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Personal Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-primary-600"></i>
                    Data Pribadi
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nama Lengkap</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $customer->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Email</p>
                        <p class="text-sm text-gray-800">{{ $customer->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Telepon</p>
                        <p class="text-sm text-gray-800">{{ $customer->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">NIK / KTP</p>
                        <p class="text-sm text-gray-800 font-mono">{{ $customer->nik ?? '-' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-500 mb-1">Alamat</p>
                        <p class="text-sm text-gray-800">{{ $customer->address ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Lahir</p>
                        <p class="text-sm text-gray-800">{{ $customer->date_of_birth ? \Carbon\Carbon::parse($customer->date_of_birth)->format('d M Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Sim</p>
                        <p class="text-sm text-gray-800">{{ $customer->license_number ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="file-check" class="w-5 h-5 text-primary-600"></i>
                    Dokumen
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($customer->documents ?? [] as $document)
                    <div class="p-3 rounded-xl border border-gray-100">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="file" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-xs font-semibold text-gray-600 uppercase">{{ $document->type ?? '-' }}</span>
                        </div>
                        @if($document->file_path)
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-sm text-primary-600 hover:underline">Lihat Dokumen</a>
                        @else
                            <span class="text-sm text-gray-500">Belum diunggah</span>
                        @endif
                    </div>
                    @empty
                    <div class="col-span-2 text-center py-4">
                        <i data-lucide="file-x" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                        <p class="text-sm text-gray-500">Belum ada dokumen</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Booking History -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="history" class="w-5 h-5 text-primary-600"></i>
                    Riwayat Booking
                </h4>
                <div class="space-y-3">
                    @forelse($customer->bookings ?? [] as $booking)
                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors border border-gray-100">
                        <div class="w-10 h-10 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                            @if($booking->car->image ?? false)
                                <img src="{{ asset('storage/' . $booking->car->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i data-lucide="car" class="w-4 h-4 text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800">#{{ $booking->booking_code }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->car->name ?? '-' }} &middot; {{ $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d M Y') : '' }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            @php
                                $statusColors = ['pending' => 'bg-yellow-100 text-yellow-700', 'confirmed' => 'bg-blue-100 text-blue-700', 'active' => 'bg-green-100 text-green-700', 'completed' => 'bg-gray-100 text-gray-700', 'cancelled' => 'bg-red-100 text-red-700'];
                                $statusLabels = ['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'active' => 'Aktif', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$booking->status] ?? $booking->status }}
                            </span>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-8">
                        <i data-lucide="calendar-x" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                        <p class="text-sm text-gray-500">Belum ada riwayat booking</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">

            <!-- Stats -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-primary-600"></i>
                    Statistik
                </h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Booking</span>
                        <span class="text-sm font-bold text-gray-800">{{ $totalBookings ?? $customer->bookings_count ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Pengeluaran</span>
                        <span class="text-sm font-bold text-primary-600">Rp {{ number_format($totalSpent ?? $customer->total_spent ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Booking Aktif</span>
                        <span class="text-sm font-bold text-green-600">{{ $customer->active_bookings_count ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Deposit -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="wallet" class="w-5 h-5 text-primary-600"></i>
                    Saldo Deposit
                </h4>
                <div class="text-center py-4">
                    <p class="text-3xl font-bold text-primary-600">Rp {{ number_format($customer->deposit_balance ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Saldo tersedia</p>
                </div>
            </div>

            <!-- Contact -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="phone" class="w-5 h-5 text-primary-600"></i>
                    Kontak
                </h4>
                <div class="space-y-3">
                    <a href="tel:{{ $customer->phone }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">
                        <i data-lucide="phone" class="w-5 h-5 text-green-600"></i>
                        <span class="text-sm text-gray-800">{{ $customer->phone ?? '-' }}</span>
                    </a>
                    <a href="mailto:{{ $customer->email }}" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">
                        <i data-lucide="mail" class="w-5 h-5 text-primary-600"></i>
                        <span class="text-sm text-gray-800">{{ $customer->email ?? '-' }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
