@extends('admin.layouts.app')

@section('title', 'Kalender Booking')
@section('header', 'Kalender Booking')
@section('subtitle', 'Lihat jadwal booking dalam tampilan kalender')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <a href="{{ route('admin.bookings.index') }}" class="hover:text-primary-600">Booking</a>
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Kalender</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Kalender Booking</h3>
            <p class="text-sm text-gray-500">Tampilan jadwal sewa mobil dalam format kalender</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
            <i data-lucide="list" class="w-4 h-4"></i> Tampilan Daftar
        </a>
    </div>

    <!-- Legend -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <div class="flex flex-wrap items-center gap-4">
            <span class="text-xs font-semibold text-gray-500">Keterangan:</span>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                <span class="text-xs text-gray-600">Menunggu</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-400"></span>
                <span class="text-xs text-gray-600">Dikonfirmasi</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-green-400"></span>
                <span class="text-xs text-gray-600">Aktif</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                <span class="text-xs text-gray-600">Selesai</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-red-400"></span>
                <span class="text-xs text-gray-600">Dibatalkan</span>
            </div>
        </div>
    </div>

    <!-- Calendar Navigation -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <button id="prevMonth" class="p-2 rounded-xl hover:bg-gray-100 transition-colors">
                <i data-lucide="chevron-left" class="w-5 h-5 text-gray-600"></i>
            </button>
            <h4 id="currentMonth" class="text-lg font-bold text-gray-800"></h4>
            <button id="nextMonth" class="p-2 rounded-xl hover:bg-gray-100 transition-colors">
                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-600"></i>
            </button>
        </div>

        <!-- Day Headers -->
        <div class="grid grid-cols-7 gap-px mb-2">
            <div class="text-center py-2 text-xs font-semibold text-gray-500">Min</div>
            <div class="text-center py-2 text-xs font-semibold text-gray-500">Sen</div>
            <div class="text-center py-2 text-xs font-semibold text-gray-500">Sel</div>
            <div class="text-center py-2 text-xs font-semibold text-gray-500">Rab</div>
            <div class="text-center py-2 text-xs font-semibold text-gray-500">Kam</div>
            <div class="text-center py-2 text-xs font-semibold text-gray-500">Jum</div>
            <div class="text-center py-2 text-xs font-semibold text-gray-500">Sab</div>
        </div>

        <!-- Calendar Grid -->
        <div id="calendarGrid" class="grid grid-cols-7 gap-px bg-gray-200 rounded-xl overflow-hidden">
            <!-- Days will be populated by JavaScript -->
        </div>
    </div>

    <!-- Upcoming Bookings -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i data-lucide="clock" class="w-5 h-5 text-primary-600"></i>
            Booking Mendatang
        </h4>
        <div class="space-y-3">
            @forelse($upcomingBookings ?? [] as $booking)
            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors border border-gray-100">
                <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="car" class="w-5 h-5 text-primary-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800">{{ $booking->car->name ?? '-' }}</p>
                    <p class="text-xs text-gray-500">{{ $booking->user->name ?? '-' }} &middot; #{{ $booking->booking_code }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-semibold text-primary-600">{{ $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d M') : '-' }}</p>
                    <p class="text-xs text-gray-500">s/d {{ $booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->format('d M') : '-' }}</p>
                </div>
            </a>
            @empty
            <div class="text-center py-8">
                <i data-lucide="calendar-check" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="text-sm text-gray-500">Tidak ada booking mendatang</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

<!-- Event Detail Modal -->
<div id="eventModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-lg font-bold text-gray-800">Booking Hari Ini</h5>
            <button onclick="closeModal()" class="p-1 rounded-lg hover:bg-gray-100">
                <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
            </button>
        </div>
        <div id="modalContent" class="space-y-3">
            <!-- Populated by JavaScript -->
        </div>
        <div class="mt-4 flex justify-end">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">Tutup</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const bookingsData = {!! json_encode($calendarBookings ?? []) !!};

    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    function renderCalendar() {
        const grid = document.getElementById('calendarGrid');
        const monthLabel = document.getElementById('currentMonth');
        monthLabel.textContent = monthNames[currentMonth] + ' ' + currentYear;

        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const today = new Date();

        let html = '';
        // Empty cells before first day
        for (let i = 0; i < firstDay; i++) {
            html += '<div class="bg-white p-2 min-h-[80px] sm:min-h-[100px]"></div>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const isToday = day === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear();
            const dateStr = currentYear + '-' + String(currentMonth + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
            const dayBookings = bookingsData.filter(b => b.start_date <= dateStr && b.end_date >= dateStr);

            html += `<div class="bg-white p-2 min-h-[80px] sm:min-h-[100px] ${isToday ? 'ring-2 ring-primary-500 ring-inset' : ''} cursor-pointer hover:bg-gray-50 transition-colors" onclick="showDayDetail('${dateStr}', ${JSON.stringify(dayBookings.map(b => ({id: b.id, code: b.booking_code, customer: b.customer_name, car: b.car_name, status: b.status}))).replace(/"/g, '&quot;')})">`;
            html += `<span class="text-sm font-semibold ${isToday ? 'bg-primary-600 text-white w-6 h-6 rounded-full flex items-center justify-center' : 'text-gray-700'}">${day}</span>`;
            html += '<div class="mt-1 space-y-0.5">';

            const statusColors = {
                pending: 'bg-yellow-400',
                confirmed: 'bg-blue-400',
                active: 'bg-green-400',
                completed: 'bg-gray-400',
                cancelled: 'bg-red-400'
            };

            dayBookings.slice(0, 2).forEach(b => {
                html += `<div class="text-[10px] text-white px-1.5 py-0.5 rounded ${statusColors[b.status] || 'bg-gray-400'} truncate" title="${b.customer} - ${b.car}">${b.car}</div>`;
            });

            if (dayBookings.length > 2) {
                html += `<div class="text-[10px] text-gray-500 px-1">+${dayBookings.length - 2} lagi</div>`;
            }

            html += '</div></div>';
        }

        grid.innerHTML = html;
        lucide.createIcons();
    }

    function showDayDetail(dateStr, bookings) {
        const modal = document.getElementById('eventModal');
        const content = document.getElementById('modalContent');
        const statusLabels = { pending: 'Menunggu', confirmed: 'Dikonfirmasi', active: 'Aktif', completed: 'Selesai', cancelled: 'Dibatalkan' };
        const statusColors = { pending: 'bg-yellow-100 text-yellow-700', confirmed: 'bg-blue-100 text-blue-700', active: 'bg-green-100 text-green-700', completed: 'bg-gray-100 text-gray-700', cancelled: 'bg-red-100 text-red-700' };

        if (bookings.length === 0) {
            content.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Tidak ada booking pada tanggal ini</p>';
        } else {
            let html = '';
            bookings.forEach(b => {
                html += `<a href="/admin/booking/${b.id}" class="block p-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">`;
                html += `<p class="text-sm font-semibold text-gray-800">#${b.code}</p>`;
                html += `<p class="text-xs text-gray-500">${b.customer} - ${b.car}</p>`;
                html += `<span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-semibold ${statusColors[b.status] || 'bg-gray-100 text-gray-700'}">${statusLabels[b.status] || b.status}</span>`;
                html += '</a>';
            });
            content.innerHTML = html;
        }

        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('eventModal').classList.add('hidden');
    }

    document.getElementById('prevMonth').addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) { currentMonth = 11; currentYear--; }
        renderCalendar();
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) { currentMonth = 0; currentYear++; }
        renderCalendar();
    });

    renderCalendar();
</script>
@endpush
