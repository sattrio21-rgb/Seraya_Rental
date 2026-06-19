@extends('admin.layouts.app')

@section('title', 'Detail Dokumen')
@section('header', 'Detail Dokumen')
@section('subtitle', 'Informasi lengkap dokumen pelanggan')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <a href="{{ route('admin.documents.index') }}" class="hover:text-primary-600">Dokumen</a>
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">{{ $document->type ?? 'Detail' }}</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h3 class="text-xl font-bold text-gray-800">Dokumen: {{ $document->type ?? '-' }}</h3>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'verified' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-700',
                    ];
                    $statusLabels = [
                        'pending' => 'Menunggu Verifikasi',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$document->status ?? 'pending'] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $statusLabels[$document->status ?? 'pending'] ?? $document->status }}
                </span>
            </div>
            <p class="text-sm text-gray-500">Diajukan {{ $document->created_at ? \Carbon\Carbon::parse($document->created_at)->format('d M Y H:i') : '-' }}</p>
        </div>
        <a href="{{ route('admin.documents.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Customer Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-primary-600"></i>
                    Data Pelanggan
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nama Lengkap</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $document->user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Email</p>
                        <p class="text-sm text-gray-800">{{ $document->user->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Document Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="file-check" class="w-5 h-5 text-primary-600"></i>
                    Informasi Dokumen
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tipe Dokumen</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 uppercase">
                            {{ $document->type ?? '-' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Upload</p>
                        <p class="text-sm text-gray-800">{{ $document->created_at ? \Carbon\Carbon::parse($document->created_at)->format('d M Y H:i') : '-' }}</p>
                    </div>
                    @if($document->verified_at)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Verifikasi</p>
                        <p class="text-sm text-gray-800">{{ \Carbon\Carbon::parse($document->verified_at)->format('d M Y H:i') }}</p>
                    </div>
                    @endif
                    @if($document->verifier)
                    <div>
                        <p class="text-xs text-gray-500 mb-1>Diverifikasi Oleh</p>
                        <p class="text-sm text-gray-800">{{ $document->verifier->name ?? '-' }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Document Preview -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="image" class="w-5 h-5 text-primary-600"></i>
                    Preview Dokumen
                </h4>
                @if($document->file_path)
                    @if(in_array(pathinfo($document->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'webp']))
                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="block bg-gray-100 rounded-xl overflow-hidden border border-gray-200 hover:shadow-md transition-shadow">
                            <img src="{{ asset('storage/' . $document->file_path) }}" class="w-full h-auto max-h-96 object-contain" alt="{{ $document->type }}">
                        </a>
                    @else
                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-3 bg-gray-50 text-gray-700 text-sm rounded-xl hover:bg-gray-100 transition-colors">
                            <i data-lucide="file" class="w-5 h-5"></i> Lihat Dokumen ({{ strtoupper(pathinfo($document->file_path, PATHINFO_EXTENSION)) }})
                        </a>
                    @endif
                @else
                    <p class="text-sm text-gray-500">File tidak tersedia</p>
                @endif
            </div>

            <!-- Rejection Reason -->
            @if($document->rejection_reason)
            <div class="bg-red-50 rounded-2xl border border-red-200 p-6">
                <h4 class="text-lg font-bold text-red-800 mb-2 flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                    Alasan Penolakan
                </h4>
                <p class="text-sm text-red-700">{{ $document->rejection_reason }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">

            <!-- Action Panel -->
            @if(($document->status ?? '') == 'pending')
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-5 h-5 text-primary-600"></i>
                    Verifikasi Dokumen
                </h4>
                <p class="text-sm text-gray-500 mb-4">Tinjau dokumen dan lakukan verifikasi.</p>
                <div class="space-y-3">
                    <form action="{{ route('admin.documents.verify', $document->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition-colors">
                            <i data-lucide="check-circle" class="w-5 h-5"></i> Verifikasi & Setujui
                        </button>
                    </form>
                    <form action="{{ route('admin.documents.reject', $document->id) }}" method="POST" onsubmit="var r=prompt('Alasan penolakan:'); if(r===null)return false; this.rejection_reason.value=r; return true;">
                        @csrf
                        <input type="hidden" name="rejection_reason" value="">
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition-colors">
                            <i data-lucide="x-circle" class="w-5 h-5"></i> Tolak Dokumen
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection
