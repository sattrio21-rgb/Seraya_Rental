@extends('admin.layouts.app')

@section('title', 'Manajemen Mobil')
@section('header', 'Manajemen Mobil')
@section('subtitle', 'Kelola data armada mobil rental')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Mobil</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Daftar Mobil</h3>
            <p class="text-sm text-gray-500">Total {{ $cars->total() ?? 0 }} mobil terdaftar</p>
        </div>
        <a href="{{ route('admin.cars.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 shadow-sm transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Mobil
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
        <form action="{{ route('admin.cars.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama mobil, plat nomor..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <select name="brand" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Semua Merek</option>
                @foreach($brands ?? [] as $brand)
                    <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                @endforeach
            </select>
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Semua Status</option>
                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                Filter
            </button>
        </form>
    </div>

    <!-- Cars Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Mobil</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Merek</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Harga/Hari</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Kapasitas</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($cars ?? [] as $car)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                                    @if($car->image)
                                        <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i data-lucide="car" class="w-5 h-5 text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $car->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $car->plate_number ?? '-' }} &middot; {{ $car->model ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 hidden md:table-cell">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-50 text-primary-700">
                                {{ $car->brand ?? '-' }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-sm font-semibold text-gray-800">Rp {{ number_format($car->price_per_day ?? 0, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">/hari</p>
                        </td>
                        <td class="py-4 px-6 hidden lg:table-cell">
                            <div class="flex items-center gap-1 text-sm text-gray-600">
                                <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
                                {{ $car->capacity ?? '-' }} orang
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <form action="{{ route('admin.cars.status', $car->id) }}" method="POST" x-data="{ status: '{{ $car->status }}' }">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" :value="status">
                                <select x-model="status" @change="$el.form.submit()" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-gray-700">
                                    <option value="available">Tersedia</option>
                                    <option value="unavailable">Tidak Tersedia</option>
                                </select>
                            </form>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.cars.edit', $car->id) }}" class="p-2 text-gray-500 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Edit">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus mobil ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <i data-lucide="car" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                            <p class="text-lg font-semibold text-gray-600 mb-2">Belum ada data mobil</p>
                            <p class="text-sm text-gray-500 mb-4">Mulai tambahkan mobil ke armada Anda</p>
                            <a href="{{ route('admin.cars.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Mobil
                            </a>
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
