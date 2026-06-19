<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarManagementController extends Controller
{
    /**
     * Tampilkan daftar semua mobil.
     */
    public function index(Request $request)
    {
        $query = Car::query();

        // Pencarian berdasarkan nama, brand, atau plat nomor
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('plate_number', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan brand
        if ($request->filled('brand')) {
            $query->byBrand($request->brand);
        }

        // Filter berdasarkan transmisi
        if ($request->filled('transmission')) {
            $query->byTransmission($request->transmission);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan status aktif
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $cars = $query->latest()->paginate(10)->withQueryString();

        // Daftar brand untuk filter
        $brands = Car::distinct()->pluck('brand')->sort()->values();

        return view('admin.cars.index', compact('cars', 'brands'));
    }

    /**
     * Tampilkan form tambah mobil baru.
     */
    public function create()
    {
        return view('admin.cars.create');
    }

    /**
     * Simpan mobil baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'brand'            => 'required|string|max:255',
            'model'            => 'required|string|max:255',
            'year'             => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'plate_number'     => 'required|string|max:20|unique:cars,plate_number',
            'color'            => 'required|string|max:100',
            'capacity'         => 'required|integer|min:1|max:20',
            'transmission'     => 'required|in:manual,automatic',
            'fuel_type'        => 'required|in:gasoline,diesel,electric,hybrid',
            'price_per_day'    => 'required|numeric|min:0',
            'price_with_driver'=> 'required|numeric|min:0',
            'image'            => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'description'      => 'nullable|string',
            'features'         => 'nullable|array',
            'features.*'       => 'string|max:255',
            'is_active'        => 'boolean',
        ]);

        // Upload gambar utama
        $validated['image'] = $request->file('image')->store('cars', 'public');

        // Set status default
        $validated['status'] = 'available';
        $validated['is_active'] = $request->boolean('is_active', true);

        $car = Car::create($validated);

        return redirect()->route('admin.cars.index')
            ->with('success', "Mobil \"{$car->name}\" berhasil ditambahkan.");
    }

    /**
     * Tampilkan form edit mobil.
     */
    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    /**
     * Perbarui data mobil.
     */
    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'brand'            => 'required|string|max:255',
            'model'            => 'required|string|max:255',
            'year'             => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'plate_number'     => 'required|string|max:20|unique:cars,plate_number,' . $car->id,
            'color'            => 'required|string|max:100',
            'capacity'         => 'required|integer|min:1|max:20',
            'transmission'     => 'required|in:manual,automatic',
            'fuel_type'        => 'required|in:gasoline,diesel,electric,hybrid',
            'price_per_day'    => 'required|numeric|min:0',
            'price_with_driver'=> 'required|numeric|min:0',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'description'      => 'nullable|string',
            'features'         => 'nullable|array',
            'features.*'       => 'string|max:255',
            'status'           => 'required|in:available,maintenance,rented',
            'is_active'        => 'boolean',
        ]);

        // Upload gambar baru jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($car->image && Storage::disk('public')->exists($car->image)) {
                Storage::disk('public')->delete($car->image);
            }
            $validated['image'] = $request->file('image')->store('cars', 'public');
        } else {
            unset($validated['image']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $car->update($validated);

        return redirect()->route('admin.cars.index')
            ->with('success', "Data mobil \"{$car->name}\" berhasil diperbarui.");
    }

    /**
     * Hapus mobil dari database.
     */
    public function destroy(Car $car)
    {
        // Hapus gambar utama
        if ($car->image && Storage::disk('public')->exists($car->image)) {
            Storage::disk('public')->delete($car->image);
        }

        // Hapus semua gambar terkait
        foreach ($car->images as $image) {
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }

        $car->delete();

        return redirect()->route('admin.cars.index')
            ->with('success', 'Mobil berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif mobil.
     */
    public function toggleStatus(Car $car)
    {
        $car->update(['is_active' => !$car->is_active]);

        $status = $car->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Mobil \"{$car->name}\" berhasil {$status}.");
    }

    /**
     * Upload gambar tambahan untuk mobil.
     */
    public function uploadImage(Request $request, Car $car)
    {
        $validated = $request->validate([
            'image'      => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_primary' => 'boolean',
        ]);

        $imagePath = $request->file('image')->store('cars', 'public');

        // Jika ditandai sebagai gambar utama, unset gambar utama lainnya
        if ($request->boolean('is_primary')) {
            CarImage::where('car_id', $car->id)->update(['is_primary' => false]);
        }

        CarImage::create([
            'car_id'      => $car->id,
            'image_path'  => $imagePath,
            'is_primary'  => $request->boolean('is_primary', false),
        ]);

        return redirect()->route('admin.cars.edit', $car)
            ->with('success', 'Gambar mobil berhasil ditambahkan.');
    }

    /**
     * Hapus gambar mobil.
     */
    public function destroyImage(CarImage $image)
    {
        $car = $image->car;

        // Hapus file gambar dari storage
        if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return redirect()->route('admin.cars.edit', $car)
            ->with('success', 'Gambar mobil berhasil dihapus.');
    }
}
