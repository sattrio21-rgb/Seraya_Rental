@extends('customer.layouts.app')

@section('title', 'Booking Mobil')

@section('content')
<div class="mb-6">
    <a href="{{ route('cars.show', $car) }}" class="text-primary-600 hover:text-primary-700">&larr; Kembali ke {{ $car->name }}</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="card p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Form Booking</h1>

            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="car_id" value="{{ $car->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}" class="input-field" required onchange="calculateTotal()">
                        @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" min="{{ date('Y-m-d') }}" value="{{ old('end_date') }}" class="input-field" required onchange="calculateTotal()">
                        @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Pengambilan</label>
                        <input type="text" name="pickup_location" value="{{ old('pickup_location') }}" class="input-field" placeholder="Alamat pengambilan" required>
                        @error('pickup_location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Pengembalian</label>
                        <input type="text" name="return_location" value="{{ old('return_location') }}" class="input-field" placeholder="Alamat pengembalian" required>
                        @error('return_location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Sewa</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="border-2 rounded-lg p-4 cursor-pointer {{ old('rental_type') == 'without_driver' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="rental_type" value="without_driver" {{ old('rental_type', 'without_driver') == 'without_driver' ? 'checked' : '' }} class="hidden" onchange="calculateTotal()">
                            <div class="text-center">
                                <div class="text-2xl mb-1">🚗</div>
                                <div class="font-medium">Lepas Kunci</div>
                                <div class="text-sm text-gray-500">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}/hari</div>
                            </div>
                        </label>
                        @if($car->price_with_driver)
                        <label class="border-2 rounded-lg p-4 cursor-pointer {{ old('rental_type') == 'with_driver' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="rental_type" value="with_driver" {{ old('rental_type') == 'with_driver' ? 'checked' : '' }} class="hidden" onchange="calculateTotal()">
                            <div class="text-center">
                                <div class="text-2xl mb-1">👨‍✈️</div>
                                <div class="font-medium">Dengan Driver</div>
                                <div class="text-sm text-gray-500">Rp {{ number_format($car->price_with_driver, 0, ',', '.') }}/hari</div>
                            </div>
                        </label>
                        @endif
                    </div>
                    @error('rental_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn-primary w-full text-lg py-3">Buat Booking</button>
            </form>
        </div>
    </div>

    {{-- Price Summary --}}
    <div class="lg:col-span-1">
        <div class="card p-6 sticky top-24">
            <h3 class="font-bold text-lg mb-4">Ringkasan Harga</h3>
            <div class="flex items-center space-x-4 mb-4 pb-4 border-b">
                <img src="{{ $car->image ? asset('storage/' . $car->image) : 'https://via.placeholder.com/60x40?text=Mobil' }}" alt="{{ $car->car->name }}" class="w-16 h-12 object-cover rounded">
                <div>
                    <div class="font-medium">{{ $car->name }}</div>
                    <div class="text-sm text-gray-500">{{ $car->brand }} • {{ ucfirst($car->transmission) }}</div>
                </div>
            </div>
            <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Harga/Hari</span>
                    <span id="daily-price">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Lama Sewa</span>
                    <span id="duration">-</span>
                </div>
            </div>
            <div class="flex justify-between text-lg font-bold border-t pt-4">
                <span>Total</span>
                <span id="total-price" class="text-primary-600">Rp 0</span>
            </div>

            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <h4 class="font-medium text-blue-800 mb-2">💡 Info Penting</h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Deposit jaminan: Rp 500.000</li>
                    <li>• Pembayaran dilakukan setelah booking dikonfirmasi</li>
                    <li>• Denda keterlambatan: 10% per hari</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const pricePerDay = {{ $car->price_per_day }};
    const priceWithDriver = {{ $car->price_with_driver ?? 0 }};

    function calculateTotal() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const rentalType = document.querySelector('input[name="rental_type"]:checked')?.value;

        if (startDate && endDate && rentalType) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
            if (days > 0) {
                const dailyPrice = rentalType === 'with_driver' && priceWithDriver ? priceWithDriver : pricePerDay;
                const total = dailyPrice * days;
                document.getElementById('daily-price').textContent = 'Rp ' + dailyPrice.toLocaleString('id-ID');
                document.getElementById('duration').textContent = days + ' hari';
                document.getElementById('total-price').textContent = 'Rp ' + total.toLocaleString('id-ID');
            }
        }
    }
</script>
@endpush
@endsection
