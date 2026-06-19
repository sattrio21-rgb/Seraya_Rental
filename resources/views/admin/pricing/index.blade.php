@extends('admin.layouts.app')

@section('title', 'Manajemen Harga')
@section('header', 'Manajemen Harga')
@section('subtitle', 'Kelola harga sewa mobil')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Harga</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Daftar Harga</h3>
            <p class="text-sm text-gray-500">Kelola harga sewa per hari dan dengan driver</p>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
        <form action="{{ route('admin.pricing.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama mobil, brand..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                Cari
            </button>
        </form>
    </div>

    <!-- Pricing Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Mobil</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Brand</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Harga/Hari (Tanpa Driver)</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Harga/Hari (Dengan Driver)</th>
                        <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($cars ?? [] as $car)
                    <form action="{{ route('admin.pricing.update', $car->id) }}" method="POST">
                        @csrf @method('PUT')
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6">
                            <p class="text-sm font-semibold text-gray-800">{{ $car->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $car->plate_number ?? '' }}</p>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-50 text-primary-700">
                                {{ $car->brand ?? '-' }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <input type="number" name="price_per_day" value="{{ $car->price_per_day ?? 0 }}" min="0"
                                   class="w-32 px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </td>
                        <td class="py-4 px-6">
                            <input type="number" name="price_with_driver" value="{{ $car->price_with_driver ?? 0 }}" min="0"
                                   class="w-32 px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary-600 text-white text-xs font-semibold rounded-lg hover:bg-primary-700 transition-colors">
                                <i data-lucide="save" class="w-3 h-3"></i> Simpan
                            </button>
                        </td>
                    </tr>
                    </form>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <i data-lucide="banknote" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                            <p class="text-lg font-semibold text-gray-600 mb-2">Belum ada data harga</p>
                            <p class="text-sm text-gray-500">Data harga akan muncul setelah ada mobil</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($cars) && $cars->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $cars->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
