@extends('admin.layouts.app')

@section('title', 'Edit Promo')
@section('header', 'Edit Promo')
@section('subtitle', 'Perbarui data promo')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <a href="{{ route('admin.promos.index') }}" class="hover:text-primary-600">Promo</a>
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Edit</span>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Edit: {{ $promo->name ?? 'Promo' }}</h3>
            <p class="text-sm text-gray-500">Perbarui informasi promo</p>
        </div>
        <a href="{{ route('admin.promos.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.promos.update', $promo->id ?? 0) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="info" class="w-5 h-5 text-primary-600"></i>
                Informasi Promo
            </h4>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Promo <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $promo->name ?? '') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                           placeholder="Contoh: Diskon Awal Tahun">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kode Promo <span class="text-red-500">*</span></label>
                        <input type="text" name="code" value="{{ old('code', $promo->code ?? '') }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent uppercase"
                               placeholder="Contoh: DISKON20">
                        <p class="text-xs text-gray-500 mt-1">Kode unik untuk pelanggan masukkan saat booking</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe Diskon <span class="text-red-500">*</span></label>
                        <select name="discount_type" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="percentage" {{ old('discount_type', $promo->discount_type ?? '') == 'percentage' ? 'selected' : '' }}>Persen (%)</option>
                            <option value="fixed" {{ old('discount_type', $promo->discount_type ?? '') == 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nilai Diskon <span class="text-red-500">*</span></label>
                        <input type="number" name="discount_value" value="{{ old('discount_value', $promo->discount_value ?? '') }}" required min="1"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               placeholder="Contoh: 10">
                        <p class="text-xs text-gray-500 mt-1" id="discountHint">Masukkan persen diskon</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Minimal Sewa (hari)</label>
                        <input type="number" name="min_booking_amount" value="{{ old('min_booking_amount', $promo->min_booking_amount ?? 1) }}" min="1"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               placeholder="Contoh: 2">
                    </div>
                </div>
            </div>
        </div>

        <!-- Period & Limits -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="calendar" class="w-5 h-5 text-primary-600"></i>
                Periode & Batasan
            </h4>
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="valid_from" value="{{ old('valid_from', $promo->valid_from ? \Carbon\Carbon::parse($promo->valid_from)->format('Y-m-d') : '') }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Berakhir <span class="text-red-500">*</span></label>
                        <input type="date" name="valid_until" value="{{ old('valid_until', $promo->valid_until ? \Carbon\Carbon::parse($promo->valid_until)->format('Y-m-d') : '') }}" required
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Maksimum Penggunaan</label>
                        <input type="number" name="max_uses" value="{{ old('max_uses', $promo->max_uses ?? '') }}" min="1"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               placeholder="Kosongkan untuk tak terbatas">
                        <p class="text-xs text-gray-500 mt-1">Berapa kali promo ini bisa digunakan</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Maksimum Diskon (Rp)</label>
                        <input type="number" name="max_discount_amount" value="{{ old('max_discount_amount', $promo->max_discount_amount ?? '') }}" min="0"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               placeholder="Kosongkan untuk tak terbatas">
                        <p class="text-xs text-gray-500 mt-1">Batas maksimal nominal diskon</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="file-text" class="w-5 h-5 text-primary-600"></i>
                Deskripsi
            </h4>
            <textarea name="description" rows="4"
                      class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                      placeholder="Deskripsi promo (opsional)...">{{ old('description', $promo->description ?? '') }}</textarea>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.promos.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 shadow-sm transition-all">
                <i data-lucide="save" class="w-4 h-4 inline mr-1"></i>
                Perbarui Promo
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    // Update discount hint based on type
    document.querySelector('select[name="discount_type"]').addEventListener('change', function() {
        const hint = document.getElementById('discountHint');
        hint.textContent = this.value === 'percentage' ? 'Masukkan persen diskon' : 'Masukkan nominal diskon (Rp)';
    });
</script>
@endpush
