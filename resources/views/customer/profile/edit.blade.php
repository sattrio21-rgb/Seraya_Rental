@extends('customer.layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Profil</h1>
    <p class="text-gray-500">Perbarui informasi profil Anda</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- Profile Form --}}
    <div class="card p-6">
        <h2 class="text-xl font-bold mb-4">Informasi Profil</h2>
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" name="name" value="{{ $user->name }}" class="input-field" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="input-field" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                <input type="text" name="phone" value="{{ $user->phone }}" class="input-field" placeholder="081234567890">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                <input type="file" name="avatar" accept="image/*" class="input-field">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-16 h-16 rounded-full mt-2">
                @endif
            </div>
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </form>
    </div>

    {{-- Password Form --}}
    <div class="card p-6">
        <h2 class="text-xl font-bold mb-4">Ubah Password</h2>
        <form action="{{ route('profile.password') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                <input type="password" name="current_password" class="input-field" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <input type="password" name="password" class="input-field" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="input-field" required>
            </div>
            <button type="submit" class="btn-primary">Ubah Password</button>
        </form>
    </div>
</div>
@endsection
