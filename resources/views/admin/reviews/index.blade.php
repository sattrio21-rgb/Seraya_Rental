@extends('admin.layouts.app')

@section('title', 'Manajemen Ulasan')
@section('header', 'Manajemen Ulasan')
@section('subtitle', 'Kelola ulasan dari pelanggan')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Ulasan</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Daftar Ulasan</h3>
            <p class="text-sm text-gray-500">Total {{ $totalReviews ?? 0 }} ulasan</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Rating Rata-rata</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ number_format($averageRating ?? 0, 1) }}/5</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="star" class="w-6 h-6 text-yellow-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Disetujui</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $approvedReviews ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Menunggu</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $pendingReviews ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pelanggan, nama mobil..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                Cari
            </button>
        </form>
    </div>

    <!-- Reviews List -->
    <div class="space-y-4">
        @forelse($reviews ?? [] as $review)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                <!-- User Info -->
                <div class="flex items-center gap-3 sm:w-48 flex-shrink-0">
                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                        <span class="text-sm font-bold text-primary-600">{{ strtoupper(substr($review->user->name ?? '?', 0, 1)) }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $review->user->name ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $review->created_at ? \Carbon\Carbon::parse($review->created_at)->format('d M Y') : '' }}</p>
                    </div>
                </div>

                <!-- Review Content -->
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <i data-lucide="star" class="w-4 h-4 {{ $i <= ($review->rating ?? 0) ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                        <span class="text-xs text-gray-500">untuk</span>
                        <span class="text-xs font-semibold text-primary-600">{{ $review->car->name ?? '-' }}</span>
                        @if($review->is_approved ?? false)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Disetujui</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Menunggu</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600">{{ $review->comment ?? '-' }}</p>

                    <!-- Admin Reply -->
                    @if($review->admin_reply)
                    <div class="mt-3 p-3 bg-gray-50 rounded-xl">
                        <p class="text-xs font-semibold text-gray-500 mb-1">Balasan Admin:</p>
                        <p class="text-sm text-gray-700">{{ $review->admin_reply }}</p>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if(!($review->is_approved ?? false))
                    <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-600 text-xs font-medium rounded-lg hover:bg-green-100 transition-colors">
                            <i data-lucide="check" class="w-3 h-3"></i> Setujui
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 text-xs font-medium rounded-lg hover:bg-red-100 transition-colors">
                            <i data-lucide="x" class="w-3 h-3"></i> Tolak
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('admin.reviews.reply', $review->id) }}" method="POST" class="inline">
                        @csrf
                        <input type="text" name="admin_reply" placeholder="Balas..." value="{{ $review->admin_reply ?? '' }}"
                               class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-primary-500 w-32">
                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary-50 text-primary-600 text-xs font-medium rounded-lg hover:bg-primary-100 transition-colors">
                            <i data-lucide="send" class="w-3 h-3"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-16 text-center">
            <i data-lucide="star" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
            <p class="text-lg font-semibold text-gray-600 mb-2">Belum ada ulasan</p>
            <p class="text-sm text-gray-500">Ulasan dari pelanggan akan muncul di sini</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($reviews) && $reviews->hasPages())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4">
        {{ $reviews->links() }}
    </div>
    @endif

</div>
@endsection
