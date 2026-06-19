@extends('admin.layouts.app')

@section('title', 'Tambah Mobil')
@section('header', 'Tambah Mobil Baru')
@section('subtitle', 'Tambahkan mobil baru ke armada rental')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <a href="{{ route('admin.cars.index') }}" class="hover:text-primary-600">Mobil</a>
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Tambah Baru</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Form Tambah Mobil</h3>
            <p class="text-sm text-gray-500">Lengkapi data mobil berikut</p>
        </div>
        <a href="{{ route('admin.cars.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="info" class="w-5 h-5 text-primary-600"></i>
                Informasi Dasar
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Mobil <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                           placeholder="Contoh: Toyota Avanza">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Merek <span class="text-red-500">*</span></label>
                    <input type="text" name="brand" value="{{ old('brand') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                           placeholder="Contoh: Toyota">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tahun <span class="text-red-500">*</span></label>
                    <input type="number" name="year" value="{{ old('year', date('Y')) }}" required min="2000" max="{{ date('Y') + 1 }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Plat Nomor <span class="text-red-500">*</span></label>
                    <input type="text" name="plate_number" value="{{ old('plate_number') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent uppercase"
                           placeholder="Contoh: B 1234 CD">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Model <span class="text-red-500">*</span></label>
                    <input type="text" name="model" value="{{ old('model') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                           placeholder="Contoh: Avanza">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Warna <span class="text-red-500">*</span></label>
                    <input type="text" name="color" value="{{ old('color') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                           placeholder="Contoh: Putih">
                </div>
            </div>
        </div>

        <!-- Specifications -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="settings" class="w-5 h-5 text-primary-600"></i>
                Spesifikasi
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kapasitas Penumpang <span class="text-red-500">*</span></label>
                    <input type="number" name="capacity" value="{{ old('capacity', 7) }}" required min="1" max="20"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Transmisi <span class="text-red-500">*</span></label>
                    <select name="transmission" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="automatic" {{ old('transmission') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                        <option value="manual" {{ old('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bahan Bakar <span class="text-red-500">*</span></label>
                    <select name="fuel_type" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="gasoline" {{ old('fuel_type') == 'gasoline' ? 'selected' : '' }}>Bensin</option>
                        <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="electric" {{ old('fuel_type') == 'electric' ? 'selected' : '' }}>Listrik</option>
                        <option value="hybrid" {{ old('fuel_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>
                </div>
        </div>

        <!-- Pricing -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="banknote" class="w-5 h-5 text-primary-600"></i>
                Harga
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga per Hari Tanpa Driver (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price_per_day" value="{{ old('price_per_day') }}" required min="0"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                           placeholder="Contoh: 350000">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga per Hari Dengan Driver (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price_with_driver" value="{{ old('price_with_driver') }}" required min="0"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                           placeholder="Contoh: 550000">
                </div>
            </div>
        </div>

        <!-- Description & Image -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="image" class="w-5 h-5 text-primary-600"></i>
                Foto & Deskripsi
            </h4>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Foto Mobil</label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, WebP. Maksimal 2MB.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                              placeholder="Deskripsi singkat tentang mobil...">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Fitur / Fasilitas</label>
                    <textarea name="features" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                              placeholder="AC, Audio System, USB Charger, dll">{{ old('features') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.cars.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 shadow-sm transition-all">
                <i data-lucide="save" class="w-4 h-4 inline mr-1"></i>
                Simpan Mobil
            </button>
        </div>
    </form>

</div>
@endsection
