@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('subtitle', 'Selamat datang di panel administrator')

@section('content')
<div class="space-y-6">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">

        <!-- Total Mobil -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Mobil</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalCars ?? 0 }}</p>
                    <p class="text-xs text-green-600 mt-2">
                        <span class="inline-flex items-center gap-1">
                            <i data-lucide="trending-up" class="w-3 h-3"></i>
                            +{{ $carGrowth ?? 0 }} bulan ini
                        </span>
                    </p>
                </div>
                <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center">
                    <i data-lucide="car" class="w-7 h-7 text-primary-600"></i>
                </div>
            </div>
        </div>

        <!-- Booking Aktif -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Booking Aktif</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $activeBookings ?? 0 }}</p>
                    <p class="text-xs text-blue-600 mt-2">
                        <span class="inline-flex items-center gap-1">
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            {{ $pendingBookings ?? 0 }} menunggu
                        </span>
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center">
                    <i data-lucide="calendar-check" class="w-7 h-7 text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Pendapatan Bulanan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pendapatan Bulanan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs {{ ($revenueGrowth ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                        <span class="inline-flex items-center gap-1">
                            <i data-lucide="{{ ($revenueGrowth ?? 0) >= 0 ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i>
                            {{ ($revenueGrowth ?? 0) >= 0 ? '+' : '' }}{{ $revenueGrowth ?? 0 }}% dari bulan lalu
                        </span>
                    </p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center">
                    <i data-lucide="banknote" class="w-7 h-7 text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Pelanggan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Pelanggan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalCustomers ?? 0 }}</p>
                    <p class="text-xs text-purple-600 mt-2">
                        <span class="inline-flex items-center gap-1">
                            <i data-lucide="users" class="w-3 h-3"></i>
                            {{ $newCustomersThisMonth ?? 0 }} baru bulan ini
                        </span>
                    </p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center">
                    <i data-lucide="users" class="w-7 h-7 text-purple-600"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Charts and Recent Bookings -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Pendapatan</h3>
                    <p class="text-sm text-gray-500">Grafik pendapatan 6 bulan terakhir</p>
                </div>
                <select class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option>6 Bulan Terakhir</option>
                    <option>1 Tahun Terakhir</option>
                </select>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Top Cars -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">Mobil Terpopuler</h3>
                <a href="{{ route('admin.cars.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @forelse($topCars ?? [] as $index => $car)
                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                    <span class="text-sm font-bold text-gray-400 w-6">{{ $index + 1 }}</span>
                    <div class="w-12 h-12 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                        @if($car->image)
                            <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i data-lucide="car" class="w-6 h-6 text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $car->name }}</p>
                        <p class="text-xs text-gray-500">{{ $car->bookings_count ?? 0 }} booking</p>
                    </div>
                    <span class="text-sm font-bold text-primary-600">Rp {{ number_format($car->price_per_day ?? 0, 0, ',', '.') }}/hr</span>
                </div>
                @empty
                <div class="text-center py-8">
                    <i data-lucide="car" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                    <p class="text-sm text-gray-500">Belum ada data mobil</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Recent Bookings -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Booking Terbaru</h3>
                <p class="text-sm text-gray-500">Daftar booking yang baru masuk</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-xl hover:bg-primary-700 transition-colors">
                <i data-lucide="eye" class="w-4 h-4"></i>
                Lihat Semua
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">ID Booking</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Pelanggan</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Mobil</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase hidden sm:table-cell">Tanggal</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentBookings ?? [] as $booking)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <span class="text-sm font-semibold text-primary-600">#{{ $booking->booking_code }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <p class="text-sm font-medium text-gray-800">{{ $booking->user->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->user->phone ?? '-' }}</p>
                        </td>
                        <td class="py-3 px-4">
                            <p class="text-sm text-gray-800">{{ $booking->car->name ?? '-' }}</p>
                        </td>
                        <td class="py-3 px-4 hidden sm:table-cell">
                            <p class="text-sm text-gray-800">{{ $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d M Y') : '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('H:i') : '' }}</p>
                        </td>
                        <td class="py-3 px-4">
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
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary-50 text-primary-600 text-xs font-medium rounded-lg hover:bg-primary-100 transition-colors">
                                <i data-lucide="eye" class="w-3 h-3"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <i data-lucide="calendar-x" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-sm text-gray-500">Belum ada booking terbaru</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Revenue Chart
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!},
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($chartData ?? [0, 0, 0, 0, 0, 0]) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
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
                        borderColor: '#334155',
                        borderWidth: 1,
                        cornerRadius: 8,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 12 } }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 12 },
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
