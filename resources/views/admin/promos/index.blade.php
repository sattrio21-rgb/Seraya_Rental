@extends('admin.layouts.app')

@section('title', 'Manajemen Promo')
@section('header', 'Manajemen Promo')
@section('subtitle', 'Kelola promo dan diskon rental mobil')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Promo</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Daftar Promo</h3>
            <p class="text-sm text-gray-500">Total {{ $promos->total() ?? 0 }} promo aktif</p>
        </div>
        <a href="{{ route('admin.promos.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 shadow-sm transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Promo
        </a>
    </div>

    <!-- Promo Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @forelse($promos ?? [] as $promo)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <!-- Promo Header -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 p-6 text-white">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-white/20 mb-2">
                            {{ $promo->code ?? '-' }}
                        </span>
                        <h4 class="text-lg font-bold">{{ $promo->name ?? '-' }}</h4>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="tag" class="w-6 h-6"></i>
                    </div>
                </div>
            </div>

            <!-- Promo Details -->
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Diskon</span>
                        @if($promo->discount_type == 'percentage')
                            <span class="text-sm font-bold text-primary-600">{{ $promo->discount_value }}%</span>
                        @else
                            <span class="text-sm font-bold text-primary-600">Rp {{ number_format($promo->discount_value ?? 0, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Periode</span>
                        <span class="text-sm text-gray-800">{{ $promo->valid_from ? \Carbon\Carbon::parse($promo->valid_from)->format('d M') : '' }} - {{ $promo->valid_until ? \Carbon\Carbon::parse($promo->valid_until)->format('d M Y') : '' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Penggunaan</span>
                        <span class="text-sm text-gray-800">{{ $promo->used_count ?? 0 }} / {{ $promo->max_uses ?? '∞' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Status</span>
                        @php
                            $isExpired = $promo->valid_until && \Carbon\Carbon::parse($promo->valid_until)->isPast();
                        @endphp
                        @if($isExpired)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Kadaluarsa</span>
                        @elseif($promo->is_active ?? true)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Nonaktif</span>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($promo->description)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500">{{ Str::limit($promo->description, 100) }}</p>
                </div>
                @endif

                <!-- Actions -->
                <div class="mt-4 flex items-center gap-2">
                    <a href="{{ route('admin.promos.edit', $promo->id) }}" class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 bg-gray-100 text-gray-700 text-xs font-medium rounded-xl hover:bg-gray-200 transition-colors">
                        <i data-lucide="pencil" class="w-3 h-3"></i> Edit
                    </a>
                    <form action="{{ route('admin.promos.destroy', $promo->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus promo ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-1 px-3 py-2 bg-red-50 text-red-600 text-xs font-medium rounded-xl hover:bg-red-100 transition-colors">
                            <i data-lucide="trash-2" class="w-3 h-3"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center bg-white rounded-2xl shadow-sm border border-gray-100">
            <i data-lucide="tag" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
            <p class="text-lg font-semibold text-gray-600 mb-2">Belum ada promo</p>
            <p class="text-sm text-gray-500 mb-4">Buat promo baru untuk menarik pelanggan</p>
            <a href="{{ route('admin.promos.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Promo
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($promos) && $promos->hasPages())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4">
        {{ $promos->links() }}
    </div>
    @endif

</div>
@endsection
