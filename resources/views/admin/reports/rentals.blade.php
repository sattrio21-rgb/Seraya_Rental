@extends('admin.layouts.app')

@section('title', 'Laporan Sewa')
@section('header', 'Laporan Sewa')
@section('subtitle', 'Ringkasan aktivitas sewa rental mobil')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <a href="{{ route('admin.reports.revenue') }}" class="hover:text-primary-600">Laporan</a>
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Sewa</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Laporan Sewa</h3>
            <p class="text-sm text-gray-500">Detail aktivitas penyewaan kendaraan</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak
            </button>
            <a href="{{ route('admin.reports.rentals.excel') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                <i data-lucide="download" class="w-4 h-4"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
        <form action="{{ route('admin.reports.rentals') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Status</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                Tampilkan
            </button>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="calendar-check" class="w-5 h-5 text-primary-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Total Booking</p>
                    <p class="text-xl font-bold text-gray-800">{{ $totalBookings ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Selesai</p>
                    <p class="text-xl font-bold text-gray-800">{{ $completedBookings ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="car" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Rata-rata Hari</p>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($avgRentalDays ?? 0, 1) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Dibatalkan</p>
                    <p class="text-xl font-bold text-gray-800">{{ $cancelledBookings ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="star" class="w-5 h-5 text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Rating Rata-rata</p>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($avgRating ?? 0, 1) }}/5</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Booking Trend -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="trending-up" class="w-5 h-5 text-primary-600"></i>
                Tren Booking Harian
            </h4>
            <div class="relative" style="height: 250px;">
                <canvas id="bookingTrendChart"></canvas>
            </div>
        </div>

        <!-- Rental Duration Distribution -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="clock" class="w-5 h-5 text-primary-600"></i>
                Distribusi Durasi Sewa
            </h4>
            <div class="relative" style="height: 250px;">
                <canvas id="durationChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Most Rented Cars -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h4 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i data-lucide="trophy" class="w-5 h-5 text-primary-600"></i>
            Mobil Paling Sering Disewa
        </h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($mostRentedCars ?? [] as $index => $car)
            <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">
                <div class="w-12 h-12 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                    @if($car->image ?? false)
                        <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="car" class="w-5 h-5 text-gray-400"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        @if($index < 3)
                            <span class="w-6 h-6 bg-yellow-100 text-yellow-700 rounded-full flex items-center justify-center text-xs font-bold">{{ $index + 1 }}</span>
                        @else
                            <span class="w-6 h-6 bg-gray-100 text-gray-500 rounded-full flex items-center justify-center text-xs font-bold">{{ $index + 1 }}</span>
                        @endif
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $car->name }}</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $car->total_rentals ?? 0 }} kali disewa</p>
                </div>
                <span class="text-sm font-bold text-primary-600 flex-shrink-0">Rp {{ number_format($car->total_revenue ?? 0, 0, ',', '.') }}</span>
            </div>
            @empty
            <div class="col-span-full text-center py-8">
                <i data-lucide="car" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="text-sm text-gray-500">Belum ada data sewa</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Detailed Rental List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i data-lucide="list" class="w-5 h-5 text-primary-600"></i>
                Daftar Detail Sewa
            </h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Kode</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Pelanggan</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Mobil</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Periode</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Hari</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($rentalDetails ?? [] as $rental)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-6">
                            <a href="{{ route('admin.bookings.show', $rental->id) }}" class="text-sm font-semibold text-primary-600 hover:underline">#{{ $rental->booking_code }}</a>
                        </td>
                        <td class="py-3 px-6">
                            <p class="text-sm text-gray-800">{{ $rental->customer->name ?? '-' }}</p>
                        </td>
                        <td class="py-3 px-6 hidden md:table-cell">
                            <p class="text-sm text-gray-800">{{ $rental->car->name ?? '-' }}</p>
                        </td>
                        <td class="py-3 px-6 hidden lg:table-cell">
                            <p class="text-xs text-gray-600">{{ $rental->start_date ? \Carbon\Carbon::parse($rental->start_date)->format('d/m') : '' }} - {{ $rental->end_date ? \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') : '' }}</p>
                        </td>
                        <td class="py-3 px-6">
                            @php
                                $days = ($rental->start_date && $rental->end_date) ? \Carbon\Carbon::parse($rental->start_date)->diffInDays(\Carbon\Carbon::parse($rental->end_date)) : 0;
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-primary-50 text-primary-700">{{ $days }} hari</span>
                        </td>
                        <td class="py-3 px-6">
                            @php
                                $statusColors = ['pending' => 'bg-yellow-100 text-yellow-700', 'confirmed' => 'bg-blue-100 text-blue-700', 'active' => 'bg-green-100 text-green-700', 'completed' => 'bg-gray-100 text-gray-700', 'cancelled' => 'bg-red-100 text-red-700'];
                                $statusLabels = ['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'active' => 'Aktif', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$rental->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$rental->status] ?? $rental->status }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-right">
                            <span class="text-sm font-bold {{ ($rental->status ?? '') == 'cancelled' ? 'text-red-500' : 'text-primary-600' }}">Rp {{ number_format($rental->total_price ?? 0, 0, ',', '.') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center">
                            <i data-lucide="file-text" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                            <p class="text-lg font-semibold text-gray-600 mb-2">Belum ada data sewa</p>
                            <p class="text-sm text-gray-500">Data akan muncul setelah ada booking</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($rentalDetails) && $rentalDetails->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $rentalDetails->links() }}
        </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
    // Booking Trend Line Chart
    const trendCtx = document.getElementById('bookingTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($trendLabels ?? []) !!},
                datasets: [{
                    label: 'Booking',
                    data: {!! json_encode($trendData ?? []) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#f8fafc',
                        bodyColor: '#cbd5e1',
                        cornerRadius: 8,
                        padding: 12,
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 11 } } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { size: 11 }, stepSize: 1 } }
                }
            }
        });
    }

    // Duration Distribution Bar Chart
    const durCtx = document.getElementById('durationChart');
    if (durCtx) {
        new Chart(durCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($durationLabels ?? ['1 hari', '2-3 hari', '4-7 hari', '8-14 hari', '15+ hari']) !!},
                datasets: [{
                    label: 'Jumlah Sewa',
                    data: {!! json_encode($durationData ?? [0, 0, 0, 0, 0]) !!},
                    backgroundColor: ['#60a5fa', '#34d399', '#fbbf24', '#f87171', '#a78bfa'],
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#f8fafc',
                        bodyColor: '#cbd5e1',
                        cornerRadius: 8,
                        callbacks: {
                            label: (ctx) => ctx.parsed.y + ' booking'
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 11 } } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { size: 11 }, stepSize: 1 } }
                }
            }
        });
    }
</script>
@endpush
