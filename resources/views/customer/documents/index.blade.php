@extends('customer.layouts.app')

@section('title', 'Dokumen Saya')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Dokumen Saya</h1>
    <p class="text-gray-500">Upload dan kelola dokumen identitas Anda</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- Upload Form --}}
    <div class="card p-6">
        <h2 class="text-xl font-bold mb-4">Upload Dokumen Baru</h2>
        <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Dokumen</label>
                <select name="type" class="input-field" required>
                    <option value="ktp">KTP</option>
                    <option value="sim">SIM</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">File Dokumen</label>
                <input type="file" name="file" accept="image/*" class="input-field" required>
                <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG (Maks 5MB)</p>
            </div>
            <button type="submit" class="btn-primary">Upload Dokumen</button>
        </form>
    </div>

    {{-- Document List --}}
    <div class="card p-6">
        <h2 class="text-xl font-bold mb-4">Dokumen Tersimpan</h2>
        @forelse($documents as $document)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                        <span class="text-primary-600 font-bold text-sm">{{ strtoupper($document->type) }}</span>
                    </div>
                    <div>
                        <div class="font-medium">{{ strtoupper($document->type) }}</div>
                        <div class="text-sm text-gray-500">{{ $document->created_at->format('d M Y') }}</div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    @if($document->status === 'pending')
                        <span class="badge-warning">Menunggu</span>
                    @elseif($document->status === 'verified')
                        <span class="badge-success">Terverifikasi</span>
                    @else
                        <span class="badge-danger">Ditolak</span>
                    @endif
                    <form action="{{ route('documents.destroy', $document) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center py-4">Belum ada dokumen</p>
        @endforelse
    </div>
</div>
@endsection
