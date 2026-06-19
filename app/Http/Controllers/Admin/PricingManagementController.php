<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class PricingManagementController extends Controller
{
    /**
     * Tampilkan daftar harga semua mobil.
     */
    public function index(Request $request)
    {
        $query = Car::query();

        // Pencarian berdasarkan nama atau brand
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan brand
        if ($request->filled('brand')) {
            $query->byBrand($request->brand);
        }

        $cars = $query->orderBy('brand')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $brands = Car::distinct()->pluck('brand')->sort()->values();

        return view('admin.pricing.index', compact('cars', 'brands'));
    }

    /**
     * Perbarui harga satu mobil.
     */
    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'price_per_day'     => 'required|numeric|min:0',
            'price_with_driver' => 'required|numeric|min:0',
        ]);

        $car->update($validated);

        return redirect()->route('admin.pricing.index')
            ->with('success', "Harga mobil \"{$car->name}\" berhasil diperbarui.");
    }

    /**
     * Perbarui harga beberapa mobil sekaligus (bulk update).
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'prices'                  => 'required|array',
            'prices.*.car_id'         => 'required|exists:cars,id',
            'prices.*.price_per_day'     => 'required|numeric|min:0',
            'prices.*.price_with_driver' => 'required|numeric|min:0',
        ]);

        $updated = 0;

        foreach ($validated['prices'] as $item) {
            Car::where('id', $item['car_id'])->update([
                'price_per_day'     => $item['price_per_day'],
                'price_with_driver' => $item['price_with_driver'],
            ]);
            $updated++;
        }

        return redirect()->route('admin.pricing.index')
            ->with('success', "Harga berhasil diperbarui untuk {$updated} mobil.");
    }
}
