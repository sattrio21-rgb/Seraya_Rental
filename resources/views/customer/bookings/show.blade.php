@extends('customer.layouts.app')

@section('title', 'Detail Booking ' . $booking->booking_code)

@section('content')
<div class="mb-6">
    <a href="{{ route('bookings.index') }}" class="text-primary-600 hover:text-primary-700">&larr; Kembali ke Riwayat Booking</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        {{-- Booking Info --}}
        <div class="card p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Booking {{ $booking->booking_code }}</h1>
                    <p class="text-gray-500">Dibuat {{ $booking->created_at->format('d M Y H:i') }}</p>
                </div>
                <span class="badge-{{ $booking->getStatusColor() }} text-sm">{{ $booking->getStatusLabel() }}</span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500">Mobil</div>
                    <div class="font-semibold">{{ $booking->car->name }}</div>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500">Tipe Sewa</div>
                    <div class="font-semibold">{{ $booking->rental_type == 'with_driver' ? 'Dengan Driver' : 'Lepas Kunci' }}</div>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500">Tanggal</div>
                    <div class="font-semibold">{{ $booking->start_date->format('d M') }} - {{ $booking->end_date->format('d M Y') }}</div>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500">Lama</div>
                    <div class="font-semibold">{{ $booking->getDurationInDays() }} hari</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-sm text-gray-500">Lokasi Pengambilan</div>
                    <div class="font-medium">{{ $booking->pickup_location }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Lokasi Pengembalian</div>
                    <div class="font-medium">{{ $booking->return_location }}</div>
                </div>
            </div>
        </div>

        {{-- Payment --}}
        @if(in_array($booking->status, ['pending', 'confirmed']))
        <div class="card p-6">
            <h2 class="text-xl font-bold mb-4">Upload Bukti Pembayaran</h2>
            @if($booking->payments->count() > 0)
                <div class="space-y-3 mb-4">
                    @foreach($booking->payments as $payment)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <span class="font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                <span class="text-sm text-gray-500"> - {{ $payment->payment_method }}</span>
                            </div>
                            <span class="badge-{{ $payment->getStatusColor() }}">{{ $payment->getStatusLabel() }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('bookings.payment', $booking) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="payment_method" value="QRIS">
                <input type="hidden" name="amount" value="{{ $booking->total_price }}">

                {{-- QRIS Image --}}
                <div class="text-center mb-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-700 mb-3">Scan QRIS untuk melakukan pembayaran</p>
                    <div class="inline-block p-4 bg-white rounded-lg border-2 border-gray-200">
                        <img src="{{ asset('storage/qris/qris-seraya.png') }}" alt="QRIS Seraya" class="w-48 h-48 object-contain">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Total: <span class="font-bold text-blue-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span></p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Pembayaran</label>
                    <input type="file" name="payment_proof" accept="image/*" class="input-field" required>
                    <p class="text-xs text-gray-500 mt-1">Upload screenshot bukti pembayaran QRIS</p>
                </div>
                <button type="submit" class="btn-primary w-full">Upload Bukti Bayar</button>
            </form>
        </div>
        @endif

        {{-- Review Form --}}
        @if($booking->status === 'completed' && !$booking->review)
        <div class="card p-6">
            <h2 class="text-xl font-bold mb-4">Beri Ulasan</h2>
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex space-x-1" id="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" class="text-2xl text-gray-300 hover:text-yellow-400">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="5">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Komentar</label>
                    <textarea name="comment" rows="3" class="input-field" placeholder="Ceritakan pengalaman Anda..."></textarea>
                </div>
                <button type="submit" class="btn-primary">Kirim Ulasan</button>
            </form>
        </div>
        @endif

        @if($booking->review)
        <div class="card p-6">
            <h2 class="text-xl font-bold mb-4">Ulasan Anda</h2>
            <div class="flex items-center mb-2">
                @for($i = 1; $i <= 5; $i++)
                    <span class="text-2xl {{ $i <= $booking->review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                @endfor
            </div>
            <p class="text-gray-600">{{ $booking->review->comment }}</p>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="lg:col-span-1">
        <div class="card p-6 sticky top-24">
            <h3 class="font-bold text-lg mb-4">Ringkasan Harga</h3>
            <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Harga Sewa</span>
                    <span>Rp {{ number_format($booking->total_price + $booking->discount_amount, 0, ',', '.') }}</span>
                </div>
                @if($booking->discount_amount > 0)
                <div class="flex justify-between text-sm text-green-600">
                    <span>Diskon</span>
                    <span>- Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
            <div class="flex justify-between text-lg font-bold border-t pt-4">
                <span>Total</span>
                <span class="text-primary-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>

            @if(in_array($booking->status, ['pending', 'confirmed']))
            <div class="mt-6">
                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                    @csrf
                    <button type="submit" class="btn-danger w-full">Batalkan Booking</button>
                </form>
            </div>
            @endif

            <div class="mt-6 p-4 bg-green-50 rounded-lg">
                <h4 class="font-medium text-green-800 mb-2">Butuh Bantuan?</h4>
                <a href="https://wa.me/6281234567890?text=Halo, saya butuh bantuan terkait booking {{ $booking->booking_code }}" target="_blank" class="inline-flex items-center bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 w-full justify-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Chat WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function setRating(rating) {
        document.getElementById('rating-input').value = rating;
        const stars = document.querySelectorAll('#rating-stars button');
        stars.forEach((star, i) => {
            star.classList.toggle('text-yellow-400', i < rating);
            star.classList.toggle('text-gray-300', i >= rating);
        });
    }
</script>
@endpush
@endsection
