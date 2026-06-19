@extends('admin.layouts.app')

@section('title', 'Notifikasi')
@section('header', 'Notifikasi')
@section('subtitle', 'Kelola notifikasi admin')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Notifikasi</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Daftar Notifikasi</h3>
            <p class="text-sm text-gray-500">{{ $unreadCount ?? 0 }} notifikasi belum dibaca</p>
        </div>
        @if(($unreadCount ?? 0) > 0)
        <form action="{{ route('admin.notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                <i data-lucide="check-check" class="w-4 h-4"></i> Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>

    <!-- Notifications List -->
    <div class="space-y-3">
        @forelse($notifications ?? [] as $notification)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 {{ !($notification->is_read ?? true) ? 'border-l-4 border-l-primary-500' : '' }}">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="bell" class="w-5 h-5 text-primary-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800">{{ $notification->title ?? '-' }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $notification->message ?? '-' }}</p>
                    <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at ? \Carbon\Carbon::parse($notification->created_at)->diffForHumans() : '' }}</p>
                </div>
                @if(!($notification->is_read ?? true))
                <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST" class="flex-shrink-0">
                    @csrf
                    <button type="submit" class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Tandai sudah dibaca">
                        <i data-lucide="check" class="w-4 h-4"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-16 text-center">
            <i data-lucide="bell-off" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
            <p class="text-lg font-semibold text-gray-600 mb-2">Belum ada notifikasi</p>
            <p class="text-sm text-gray-500">Notifikasi akan muncul di sini</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($notifications) && $notifications->hasPages())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4">
        {{ $notifications->links() }}
    </div>
    @endif

</div>
@endsection
