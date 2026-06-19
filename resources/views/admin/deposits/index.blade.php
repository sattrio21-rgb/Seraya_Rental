@extends('admin.layouts.app')

@section('title', 'Manajemen Deposit')
@section('header', 'Manajemen Deposit')
@section('subtitle', 'Kelola deposit pelanggan')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Deposit</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Daftar Deposit</h3>
            <p class="text-sm text-gray-500">Kelola deposit yang ditahan dari pelanggan</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Ditahan</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">Rp {{ number_format($totalHeld ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="lock" class="w-6 h-6 text-yellow-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Dikembalikan</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($totalReturned ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="rotate-ccw" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Dipotong</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($totalDeducted ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="scissors" class="w-6 h-6 text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
        <form action="{{ route('admin.deposits.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode booking, nama pelanggan..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                Cari
            </button>
        </form>
    </div>

    <!-- Deposits Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Booking</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Pelanggan</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Mobil</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($deposits ?? [] as $deposit)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6">
                            <a href="{{ route('admin.bookings.show', $deposit->booking_id ?? 0) }}" class="text-sm font-semibold text-primary-600 hover:underline">
                                #{{ $deposit->booking->booking_code ?? '-' }}
                            </a>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-sm text-gray-800">{{ $deposit->booking->user->name ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-6 hidden md:table-cell">
                            <p class="text-sm text-gray-800">{{ $deposit->booking->car->name ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-sm font-bold text-gray-800">Rp {{ number_format($deposit->amount ?? 0, 0, ',', '.') }}</p>
                        </td>
                        <td class="py-4 px-6">
                            @php
                                $statusColors = [
                                    'held' => 'bg-yellow-100 text-yellow-700',
                                    'returned' => 'bg-green-100 text-green-700',
                                    'deducted' => 'bg-red-100 text-red-700',
                                ];
                                $statusLabels = [
                                    'held' => 'Ditahan',
                                    'returned' => 'Dikembalikan',
                                    'deducted' => 'Dipotong',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$deposit->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$deposit->status] ?? $deposit->status }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-end gap-2">
                                @if(($deposit->status ?? '') == 'held')
                                <form action="{{ route('admin.deposits.return', $deposit->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin mengembalikan deposit ini?')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-600 text-xs font-medium rounded-lg hover:bg-green-100 transition-colors" title="Kembalikan">
                                        <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Kembalikan
                                    </button>
                                </form>
                                <form action="{{ route('admin.deposits.deduct', $deposit->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin memotong deposit ini?')">
                                    @csrf
                                    <input type="hidden" name="deduction_amount" value="{{ $deposit->amount }}">
                                    <input type="hidden" name="reason" value="Potongan oleh admin">
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 text-xs font-medium rounded-lg hover:bg-red-100 transition-colors" title="Potong">
                                        <i data-lucide="scissors" class="w-3 h-3"></i> Potong
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <i data-lucide="wallet" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                            <p class="text-lg font-semibold text-gray-600 mb-2">Belum ada deposit</p>
                            <p class="text-sm text-gray-500">Deposit dari pelanggan akan muncul di sini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($deposits) && $deposits->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $deposits->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
