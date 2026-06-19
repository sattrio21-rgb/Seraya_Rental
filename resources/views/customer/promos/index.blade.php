@extends('customer.layouts.app')

@section('title', 'Promo & Voucher')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Promo & Voucher</h1>
    <p class="text-gray-500">Manfaatkan promo menarik untuk penyewaan Anda</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($promos as $promo)
        <div class="card overflow-hidden hover:shadow-lg transition-shadow">
            <div class="bg-primary-600 text-white p-4 text-center">
                <div class="text-3xl font-bold">{{ $promo->discount_type === 'percentage' ? $promo->discount_value . '%' : 'Rp ' . number_format($promo->discount_value, 0, ',', '.') }}</div>
                <div class="text-primary-100">Diskon</div>
            </div>
            <div class="p-4">
                <h3 class="font-bold text-lg mb-2">{{ $promo->name }}</h3>
                <p class="text-gray-500 text-sm mb-3">{{ $promo->description }}</p>
                <div class="space-y-1 text-sm mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Kode:</span>
                        <span class="font-mono font-bold text-primary-600">{{ $promo->code }}</span>
                    </div>
                    @if($promo->min_booking_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Min. Booking:</span>
                        <span>Rp {{ number_format($promo->min_booking_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">Berlaku hingga:</span>
                        <span>{{ $promo->valid_until->format('d M Y') }}</span>
                    </div>
                </div>
                <button onclick="copyPromo('{{ $promo->code }}')" class="btn-secondary w-full text-sm">
                    Salin Kode
                </button>
            </div>
        </div>
    @empty
        <div class="col-span-3 text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            <h3 class="text-xl font-medium text-gray-500">Belum ada promo tersedia</h3>
            <p class="text-gray-400">Cek kembali nanti untuk promo menarik</p>
        </div>
    @endforelse
</div>

@push('scripts')
<script>
    function copyPromo(code) {
        navigator.clipboard.writeText(code);
        alert('Kode promo ' + code + ' berhasil disalin!');
    }
</script>
@endpush
@endsection
