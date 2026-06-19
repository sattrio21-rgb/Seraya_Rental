@extends('admin.layouts.app')

@section('title', 'Manajemen Pembayaran')
@section('header', 'Manajemen Pembayaran')
@section('subtitle', 'Kelola semua transaksi pembayaran')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Pembayaran</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Daftar Pembayaran</h3>
            <p class="text-sm text-gray-500">Total {{ $payments->total() ?? 0 }} transaksi</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak
            </button>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-1.5 flex flex-wrap gap-1.5">
        <a href="{{ route('admin.payments.index') }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ !request('status') ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Semua
        </a>
        <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Menunggu
        </a>
        <a href="{{ route('admin.payments.index', ['status' => 'paid']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') == 'paid' ? 'bg-green-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Dibayar
        </a>
        <a href="{{ route('admin.payments.index', ['status' => 'verified']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') == 'verified' ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Terverifikasi
        </a>
        <a href="{{ route('admin.payments.index', ['status' => 'failed']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') == 'failed' ? 'bg-red-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Gagal
        </a>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
        <form action="{{ route('admin.payments.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="hidden" name="status" value="{{ request('status') }}">
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

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">ID Pembayaran</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Booking</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Pelanggan</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Metode</th>
                        <th class="text-left py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-right py-4 px-6 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($payments ?? [] as $payment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6">
                            <span class="text-sm font-semibold text-primary-600">#{{ $payment->payment_code ?? $payment->id }}</span>
                            <p class="text-xs text-gray-500">{{ $payment->created_at ? \Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i') : '' }}</p>
                        </td>
                        <td class="py-4 px-6">
                            <a href="{{ route('admin.bookings.show', $payment->booking_id ?? 0) }}" class="text-sm font-medium text-primary-600 hover:underline">
                                #{{ $payment->booking->booking_code ?? '-' }}
                            </a>
                        </td>
                        <td class="py-4 px-6 hidden md:table-cell">
                            <p class="text-sm text-gray-800">{{ $payment->booking->user->name ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-6">
                            <p class="text-sm font-bold text-gray-800">Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}</p>
                        </td>
                        <td class="py-4 px-6 hidden lg:table-cell">
                            <span class="inline-flex items-center gap-1 text-sm text-gray-600">
                                <i data-lucide="{{ ($payment->method ?? '') == 'transfer' ? 'building-2' : (($payment->method ?? '') == 'cash' ? 'banknote' : 'credit-card') }}" class="w-4 h-4 text-gray-400"></i>
                                {{ ucfirst($payment->method ?? '-') }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'paid' => 'bg-green-100 text-green-700',
                                    'verified' => 'bg-blue-100 text-blue-700',
                                    'failed' => 'bg-red-100 text-red-700',
                                    'refunded' => 'bg-orange-100 text-orange-700',
                                ];
                                $statusLabels = [
                                    'pending' => 'Menunggu',
                                    'paid' => 'Dibayar',
                                    'verified' => 'Terverifikasi',
                                    'failed' => 'Gagal',
                                    'refunded' => 'Dikembalikan',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$payment->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$payment->status] ?? $payment->status }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="p-2 text-gray-500 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center">
                            <i data-lucide="credit-card" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                            <p class="text-lg font-semibold text-gray-600 mb-2">Belum ada pembayaran</p>
                            <p class="text-sm text-gray-500">Transaksi pembayaran akan muncul di sini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($payments) && $payments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $payments->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
