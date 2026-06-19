@extends('admin.layouts.app')

@section('title', 'Laporan Pendapatan')
@section('header', 'Laporan Pendapatan')
@section('subtitle', 'Analisis pendapatan rental mobil')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <a href="{{ route('admin.reports.revenue') }}" class="hover:text-primary-600">Laporan</a>
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Pendapatan</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Laporan Pendapatan</h3>
            <p class="text-sm text-gray-500">Ringkasan dan analisis pendapatan rental</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak
            </button>
            <a href="{{ route('admin.reports.revenue.excel') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                <i data-lucide="download" class="w-4 h-4"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
        <form action="{{ route('admin.reports.revenue') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
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
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.revenue', ['start_date' => now()->startOfWeek()->format('Y-m-d'), 'end_date' => now()->endOfWeek()->format('Y-m-d')]) }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors whitespace-nowrap">Minggu Ini</a>
                <a href="{{ route('admin.reports.revenue', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors whitespace-nowrap">Bulan Ini</a>
                <a href="{{ route('admin.reports.revenue', ['start_date' => now()->startOfYear()->format('Y-m-d'), 'end_date' => now()->endOfYear()->format('Y-m-d')]) }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors whitespace-nowrap">Tahun Ini</a>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                Tampilkan
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="banknote" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pendapatan Rata-rata/Hari</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($avgDailyRevenue ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalTransactions ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="receipt" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pendapatan Tertinggi</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $peakDay ?? '-' }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="calendar" class="w-6 h-6 text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i data-lucide="bar-chart-3" class="w-5 h-5 text-primary-600"></i>
            Grafik Pendapatan Harian
        </h4>
        <div class="relative" style="height: 350px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Revenue by Category -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- By Car Category -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="car" class="w-5 h-5 text-primary-600"></i>
                Pendapatan per Kategori
            </h4>
            <div class="relative" style="height: 250px;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Top Revenue Days -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="calendar" class="w-5 h-5 text-primary-600"></i>
                Top 5 Hari Terlaris
            </h4>
            <div class="space-y-3">
                @forelse($topDays ?? [] as $index => $day)
                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                    <span class="text-sm font-bold text-gray-400 w-6">{{ $index + 1 }}</span>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ $day->date ? \Carbon\Carbon::parse($day->date)->format('d M Y') : '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $day->bookings_count ?? 0 }} booking</p>
                    </div>
                    <span class="text-sm font-bold text-green-600">Rp {{ number_format($day->total ?? 0, 0, ',', '.') }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Revenue Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i data-lucide="table" class="w-5 h-5 text-primary-600"></i>
                Detail Pendapatan Harian
            </h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Booking</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Sewa Mobil</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Driver</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($dailyRevenues ?? [] as $daily)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-6">
                            <p class="text-sm font-medium text-gray-800">{{ $daily->date ? \Carbon\Carbon::parse($daily->date)->format('d M Y') : '-' }}</p>
                        </td>
                        <td class="py-3 px-6">
                            <span class="text-sm text-gray-800">{{ $daily->bookings_count ?? 0 }}</span>
                        </td>
                        <td class="py-3 px-6 hidden md:table-cell">
                            <span class="text-sm text-gray-800">Rp {{ number_format($daily->rental_revenue ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-3 px-6 hidden md:table-cell">
                            <span class="text-sm text-gray-800">Rp {{ number_format($daily->driver_revenue ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-3 px-6">
                            <span class="text-sm font-bold text-primary-600">Rp {{ number_format($daily->total ?? 0, 0, ',', '.') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <p class="text-sm text-gray-500">Belum ada data pendapatan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50 border-t border-gray-100">
                    <tr>
                        <td class="py-3 px-6 font-bold text-gray-800">Total</td>
                        <td class="py-3 px-6 font-bold text-gray-800">{{ $totalTransactions ?? 0 }}</td>
                        <td class="py-3 px-6 font-bold text-gray-800 hidden md:table-cell">Rp {{ number_format($totalRentalRevenue ?? 0, 0, ',', '.') }}</td>
                        <td class="py-3 px-6 font-bold text-gray-800 hidden md:table-cell">Rp {{ number_format($totalDriverRevenue ?? 0, 0, ',', '.') }}</td>
                        <td class="py-3 px-6 font-bold text-primary-600">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    // Revenue Line Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels ?? []) !!},
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($chartData ?? []) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
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
                        callbacks: {
                            label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 11 } } },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11 },
                            callback: (v) => 'Rp ' + (v / 1000000).toFixed(0) + 'jt'
                        }
                    }
                }
            }
        });
    }

    // Category Doughnut Chart
    const catCtx = document.getElementById('categoryChart');
    if (catCtx) {
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryLabels ?? []) !!},
                datasets: [{
                    data: {!! json_encode($categoryData ?? []) !!},
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, pointStyle: 'circle', font: { size: 12 } } },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#f8fafc',
                        bodyColor: '#cbd5e1',
                        cornerRadius: 8,
                        callbacks: {
                            label: (ctx) => 'Rp ' + ctx.parsed.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
