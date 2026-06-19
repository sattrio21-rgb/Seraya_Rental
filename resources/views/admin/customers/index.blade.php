@extends('admin.layouts.app')

@section('title', 'Manajemen Pelanggan')
@section('header', 'Manajemen Pelanggan')
@section('subtitle', 'Kelola data pelanggan rental mobil')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Pelanggan</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Daftar Pelanggan</h3>
            <p class="text-sm text-gray-500">Total {{ $customers->total() ?? 0 }} pelanggan terdaftar</p>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, telepon, NIK..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                Cari
            </button>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Pelanggan</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Kontak</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">NIK</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Total Booking</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($customers ?? [] as $customer)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm font-bold text-primary-600">{{ strtoupper(substr($customer->name ?? '?', 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $customer->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $customer->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 hidden md:table-cell">
                            <p class="text-sm text-gray-800">{{ $customer->phone ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $customer->email ?? '' }}</p>
                        </td>
                        <td class="py-4 px-6 hidden lg:table-cell">
                            <p class="text-sm text-gray-800 font-mono">{{ $customer->nik ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-6 hidden lg:table-cell">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary-50 text-primary-700">
                                {{ $customer->bookings_count ?? 0 }} booking
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            @if($customer->is_active ?? true)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="p-2 text-gray-500 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <i data-lucide="users" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                            <p class="text-lg font-semibold text-gray-600 mb-2">Belum ada pelanggan</p>
                            <p class="text-sm text-gray-500">Pelanggan yang mendaftar akan muncul di sini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($customers) && $customers->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $customers->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
