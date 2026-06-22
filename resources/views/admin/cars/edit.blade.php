@extends('admin.layouts.app')

@section('title', 'Edit Mobil')
@section('header', 'Edit Mobil')
@section('subtitle', 'Perbarui data mobil')

@section('breadcrumb')
    <span class="mx-1">/</span>
    <a href="{{ route('admin.cars.index') }}" class="hover:text-primary-600">Mobil</a>
    <span class="mx-1">/</span>
    <span class="text-primary-600 font-medium">Edit</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Edit: {{ $car->name ?? 'Mobil' }}</h3>
            <p class="text-sm text-gray-500">Perbarui informasi mobil</p>
        </div>
        <a href="{{ route('admin.cars.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.cars.update', $car->id ?? 0) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i data-lucide="info" class="w-5 h-5 text-primary-600"></i>
                Informasi Dasar
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Mobil <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $car->name ?? '') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Merek <span class="text-red-500">*</span></label>
                    <input type="text" name="brand" value="{{ old('brand', $car->brand ?? '') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tahun <span class="text-red-500">*</span></label>
                    <input type="number" name="year" value="{{ old('year', $car->year ?? date('Y')) }}" required min="2000" max="{{ date('Y') + 1 }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Plat Nomor <span class="text-red-500">*</span></label>
                    <input type="text" name="plate_number" value="{{ old('plate_number', $car->plate_number ?? '') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent uppercase">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Model <span class="text-red-500">*</span></label>
                    <input type="text" name="model" value="{{ old('model', $car->model ?? '') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                           placeholder="Contoh: Avanza">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Warna <span class="text-red-500">*</span></label>
                    <input type="text" name="color" value="{{ old('color', $car->color ?? '') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
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
                    <input type="number" name="capacity" value="{{ old('capacity', $car->capacity ?? 7) }}" required min="1" max="20"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Transmisi <span class="text-red-500">*</span></label>
                    <select name="transmission" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="automatic" {{ old('transmission', $car->transmission ?? '') == 'automatic' ? 'selected' : '' }}>Automatic</option>
                        <option value="manual" {{ old('transmission', $car->transmission ?? '') == 'manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bahan Bakar <span class="text-red-500">*</span></label>
                    <select name="fuel_type" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="petrol" {{ old('fuel_type', $car->fuel_type ?? '') == 'petrol' ? 'selected' : '' }}>Bensin</option>
                        <option value="diesel" {{ old('fuel_type', $car->fuel_type ?? '') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="electric" {{ old('fuel_type', $car->fuel_type ?? '') == 'electric' ? 'selected' : '' }}>Listrik</option>
                        <option value="hybrid" {{ old('fuel_type', $car->fuel_type ?? '') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                    <select name="status" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="available" {{ old('status', $car->status ?? '') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="unavailable" {{ old('status', $car->status ?? '') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
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
                    <input type="number" name="price_per_day" value="{{ old('price_per_day', $car->price_per_day ?? '') }}" required min="0"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Harga per Hari Dengan Driver (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price_with_driver" value="{{ old('price_with_driver', $car->price_with_driver ?? '') }}" required min="0"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
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
                @if(isset($car->image))
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Foto Saat Ini</label>
                    <div class="w-48 h-32 bg-gray-100 rounded-xl overflow-hidden">
                        <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->name }}" class="w-full h-full object-cover">
                    </div>
                </div>
                @endif
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">{{ isset($car->image) ? 'Ganti Foto' : 'Upload Foto' }}</label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, WebP. Maksimal 2MB.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('description', $car->description ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Fitur / Fasilitas</label>
                    <textarea name="features" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('features', is_array($car->features ?? null) ? implode(', ', $car->features) : ($car->features ?? '')) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma. Contoh: AC, Audio System, USB Charger</p>
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
                Perbarui Mobil
            </button>
        </div>
    </form>

</div>
@endsection
