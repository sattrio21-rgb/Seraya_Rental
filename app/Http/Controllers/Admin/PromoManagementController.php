<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoManagementController extends Controller
{
    /**
     * Tampilkan daftar semua promo.
     */
    public function index(Request $request)
    {
        $query = Promo::query();

        // Pencarian berdasarkan kode atau nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status aktif
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $promos = $query->latest()->paginate(15)->withQueryString();

        return view('admin.promos.index', compact('promos'));
    }

    /**
     * Tampilkan form tambah promo baru.
     */
    public function create()
    {
        return view('admin.promos.create');
    }

    /**
     * Simpan promo baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'                => 'required|string|max:50|unique:promos,code',
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string|max:1000',
            'discount_type'       => 'required|in:percentage,fixed',
            'discount_value'      => 'required|numeric|min:0',
            'min_booking_amount'  => 'required|numeric|min:0',
            'max_discount_amount' => 'required_if:discount_type,percentage|nullable|numeric|min:0',
            'max_uses'            => 'required|integer|min:1',
            'valid_from'          => 'required|date',
            'valid_until'         => 'required|date|after_or_equal:valid_from',
            'is_active'           => 'boolean',
        ]);

        // Generate kode promo otomatis jika kosong
        if (empty($validated['code'])) {
            $validated['code'] = 'PROMO-' . Str::upper(Str::random(6));
        }

        $validated['used_count'] = 0;
        $validated['is_active'] = $request->boolean('is_active', true);

        Promo::create($validated);

        return redirect()->route('admin.promos.index')
            ->with('success', "Promo \"{$validated['name']}\" berhasil ditambahkan.");
    }

    /**
     * Tampilkan form edit promo.
     */
    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', compact('promo'));
    }

    /**
     * Perbarui data promo.
     */
    public function update(Request $request, Promo $promo)
    {
        $validated = $request->validate([
            'code'                => 'required|string|max:50|unique:promos,code,' . $promo->id,
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string|max:1000',
            'discount_type'       => 'required|in:percentage,fixed',
            'discount_value'      => 'required|numeric|min:0',
            'min_booking_amount'  => 'required|numeric|min:0',
            'max_discount_amount' => 'required_if:discount_type,percentage|nullable|numeric|min:0',
            'max_uses'            => 'required|integer|min:1',
            'valid_from'          => 'required|date',
            'valid_until'         => 'required|date|after_or_equal:valid_from',
            'is_active'           => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $promo->update($validated);

        return redirect()->route('admin.promos.index')
            ->with('success', "Promo \"{$promo->name}\" berhasil diperbarui.");
    }

    /**
     * Hapus promo dari database.
     */
    public function destroy(Promo $promo)
    {
        $promo->delete();

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif promo.
     */
    public function toggleActive(Promo $promo)
    {
        $promo->update(['is_active' => !$promo->is_active]);

        $status = $promo->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Promo \"{$promo->name}\" berhasil {$status}.");
    }
}
