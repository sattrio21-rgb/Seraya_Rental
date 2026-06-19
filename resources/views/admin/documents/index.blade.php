@extends('admin.layouts.app')

@section('title', 'Verifikasi Dokumen')
@section('header', 'Verifikasi Dokumen')
@section('subtitle', 'Kelola verifikasi dokumen pelanggan')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Dokumen</span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Verifikasi Dokumen</h3>
            <p class="text-sm text-gray-500">Tinjau dan verifikasi dokumen pelanggan</p>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-1.5 flex flex-wrap gap-1.5">
        <a href="{{ route('admin.documents.index') }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ !request('status') ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Semua
        </a>
        <a href="{{ route('admin.documents.index', ['status' => 'pending']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Menunggu
        </a>
        <a href="{{ route('admin.documents.index', ['status' => 'approved']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') == 'approved' ? 'bg-green-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Disetujui
        </a>
        <a href="{{ route('admin.documents.index', ['status' => 'rejected']) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ request('status') == 'rejected' ? 'bg-red-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Ditolak
        </a>
    </div>

    <!-- Documents List -->
    <div class="space-y-4">
        @forelse($documents ?? [] as $document)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">

                <!-- Customer Info -->
                <div class="flex items-center gap-3 lg:w-64 flex-shrink-0">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                        <span class="text-lg font-bold text-primary-600">{{ strtoupper(substr($document->user->name ?? '?', 0, 1)) }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $document->user->name ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $document->user->email ?? '-' }}</p>
                    </div>
                </div>

                <!-- Document Type & File -->
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 uppercase">
                            {{ $document->type ?? '-' }}
                        </span>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'approved' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                            ];
                            $statusLabels = [
                                'pending' => 'Menunggu Verifikasi',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$document->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $statusLabels[$document->status] ?? $document->status }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">Diajukan {{ $document->created_at ? \Carbon\Carbon::parse($document->created_at)->format('d M Y H:i') : '-' }}</p>

                    <!-- Document Preview -->
                    <div class="mt-3">
                        @if($document->file_path)
                            @if(in_array(pathinfo($document->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'webp']))
                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="block w-32 h-20 bg-gray-100 rounded-xl overflow-hidden border border-gray-200 hover:shadow-md transition-shadow">
                                    <img src="{{ asset('storage/' . $document->file_path) }}" class="w-full h-full object-cover" alt="{{ $document->type }}">
                                </a>
                            @else
                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-gray-50 text-gray-700 text-sm rounded-xl hover:bg-gray-100 transition-colors">
                                    <i data-lucide="file" class="w-4 h-4"></i> Lihat Dokumen
                                </a>
                            @endif
                        @else
                            <span class="text-sm text-gray-500">File tidak tersedia</span>
                        @endif
                    </div>

                    @if($document->rejection_reason)
                    <div class="mt-3 p-3 bg-red-50 rounded-xl">
                        <p class="text-xs font-semibold text-red-700 mb-1">Alasan Penolakan:</p>
                        <p class="text-sm text-red-600">{{ $document->rejection_reason }}</p>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if(($document->status ?? 'pending') == 'pending')
                    <form action="{{ route('admin.documents.verify', $document->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition-colors">
                            <i data-lucide="check" class="w-4 h-4"></i> Setujui
                        </button>
                    </form>
                    <form action="{{ route('admin.documents.reject', $document->id) }}" method="POST" class="inline" onsubmit="var r=prompt('Alasan penolakan:'); if(r===null)return false; this.rejection_reason.value=r; return true;">
                        @csrf
                        <input type="hidden" name="rejection_reason" value="">
                        <button type="submit" class="inline-flex items-center gap-1 px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i> Tolak
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-16 text-center">
            <i data-lucide="file-check" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
            <p class="text-lg font-semibold text-gray-600 mb-2">Belum ada dokumen</p>
            <p class="text-sm text-gray-500">Dokumen yang diajukan pelanggan akan muncul di sini</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($documents) && $documents->hasPages())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4">
        {{ $documents->links() }}
    </div>
    @endif

</div>
@endsection
