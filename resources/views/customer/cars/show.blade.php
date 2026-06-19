@extends('customer.layouts.app')

@section('title', $car->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('cars.index') }}" class="text-primary-600 hover:text-primary-700">&larr; Kembali ke Daftar Mobil</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Car Info --}}
    <div class="lg:col-span-2">
        <div class="card">
            <img src="{{ $car->image ? asset('storage/' . $car->image) : 'https://via.placeholder.com/800x400?text=Mobil' }}" alt="{{ $car->name }}" class="w-full h-64 md:h-96 object-cover rounded-t-xl">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $car->name }}</h1>
                        <p class="text-gray-500">{{ $car->brand }} {{ $car->model }} • {{ $car->year }}</p>
                    </div>
                    <span class="badge-{{ $car->status === 'available' ? 'success' : 'warning' }} text-sm">
                        {{ $car->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-sm text-gray-500">Transmisi</div>
                        <div class="font-semibold">{{ ucfirst($car->transmission) }}</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-sm text-gray-500">Bahan Bakar</div>
                        <div class="font-semibold">{{ ucfirst($car->fuel_type) }}</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-sm text-gray-500">Kapasitas</div>
                        <div class="font-semibold">{{ $car->capacity }} kursi</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-sm text-gray-500">Warna</div>
                        <div class="font-semibold">{{ $car->color }}</div>
                    </div>
                </div>

                <h3 class="font-bold text-lg mb-2">Deskripsi</h3>
                <p class="text-gray-600 mb-6">{{ $car->description ?? 'Tidak ada deskripsi' }}</p>

                @if($car->features)
                    <h3 class="font-bold text-lg mb-2">Fitur</h3>
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach($car->features as $feature)
                            <span class="bg-primary-100 text-primary-700 px-3 py-1 rounded-full text-sm">{{ $feature }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-sm text-gray-500">Harga Sewa</span>
                            <div class="text-2xl font-bold text-primary-600">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}<span class="text-sm font-normal text-gray-500">/hari</span></div>
                        </div>
                        @if($car->price_with_driver)
                            <div class="text-right">
                                <span class="text-sm text-gray-500">Dengan Driver</span>
                                <div class="text-2xl font-bold text-primary-600">Rp {{ number_format($car->price_with_driver, 0, ',', '.') }}<span class="text-sm font-normal text-gray-500">/hari</span></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Reviews --}}
        <div class="card mt-6 p-6">
            <h2 class="text-xl font-bold mb-4">Ulasan Pelanggan</h2>
            @forelse($car->reviews->where('is_approved', true) as $review)
                <div class="border-b last:border-0 py-4">
                    <div class="flex items-center mb-2">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-700">{{ $review->user->name }}</span>
                        <span class="ml-2 text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-gray-600">{{ $review->comment }}</p>
                    @if($review->admin_reply)
                        <div class="mt-2 bg-primary-50 p-3 rounded-lg">
                            <p class="text-sm font-medium text-primary-700">Balasan Admin:</p>
                            <p class="text-sm text-gray-600">{{ $review->admin_reply }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Belum ada ulasan</p>
            @endforelse
        </div>
    </div>

    {{-- Booking Sidebar --}}
    <div class="lg:col-span-1">
        <div class="card p-6 sticky top-24">
            <h3 class="font-bold text-lg mb-4">Pesan Mobil Ini</h3>

            {{-- Availability Check --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" id="start_date" min="{{ date('Y-m-d') }}" class="input-field mb-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" id="end_date" min="{{ date('Y-m-d') }}" class="input-field mb-2">
                <button onclick="checkAvailability()" class="btn-secondary w-full text-sm mb-4">Cek Ketersediaan</button>
                <div id="availability-result" class="hidden mb-4"></div>
            </div>

            @auth
                @if($car->status === 'available')
                    <a href="{{ route('bookings.create', ['car_id' => $car->id]) }}" class="btn-primary w-full text-center block">
                        Booking Sekarang
                    </a>
                @else
                    <button disabled class="w-full bg-gray-300 text-gray-500 px-4 py-2 rounded-lg font-medium cursor-not-allowed">
                        Tidak Tersedia
                    </button>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-primary w-full text-center block">
                    Login untuk Booking
                </a>
            @endauth

            <div class="mt-4 pt-4 border-t">
                <h4 class="font-medium text-gray-700 mb-2">Kontak WhatsApp</h4>
                <a href="https://wa.me/6281234567890?text=Halo, saya tertarik dengan {{ $car->name }}" target="_blank" class="inline-flex items-center bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 w-full justify-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Chat WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function checkAvailability() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        if (!startDate || !endDate) {
            alert('Mohon pilih tanggal mulai dan selesai');
            return;
        }
        fetch('{{ route('cars.availability') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ car_id: {{ $car->id }}, start_date: startDate, end_date: endDate })
        })
        .then(r => r.json())
        .then(data => {
            const el = document.getElementById('availability-result');
            el.classList.remove('hidden');
            if (data.available) {
                el.innerHTML = '<div class="bg-green-100 text-green-700 p-3 rounded-lg text-sm">✅ Mobil tersedia untuk tanggal ini</div>';
            } else {
                el.innerHTML = '<div class="bg-red-100 text-red-700 p-3 rounded-lg text-sm">❌ Mobil tidak tersedia untuk tanggal ini</div>';
            }
        });
    }
</script>
@endpush
@endsection
