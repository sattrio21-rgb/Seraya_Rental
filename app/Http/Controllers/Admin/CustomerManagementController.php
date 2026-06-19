<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerManagementController extends Controller
{
    /**
     * Tampilkan daftar semua pelanggan.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');

        // Pencarian berdasarkan nama atau email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan status aktif
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $customers = $query->withCount('bookings')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Tampilkan detail pelanggan beserta riwayat booking.
     */
    public function show(User $customer)
    {
        // Pastikan yang dilihat adalah customer
        if ($customer->role !== 'customer') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Pengguna yang dipilih bukan pelanggan.');
        }

        $customer->load(['bookings' => function ($q) {
            $q->with('car')->latest();
        }, 'documents', 'reviews']);

        $totalBookings = $customer->bookings()->count();
        $totalSpent = $customer->bookings()
            ->where('status', 'completed')
            ->sum('total_price');

        return view('admin.customers.show', compact('customer', 'totalBookings', 'totalSpent'));
    }

    /**
     * Toggle status aktif/nonaktif pelanggan.
     */
    public function toggleActive(User $customer)
    {
        if ($customer->role !== 'customer') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Pengguna yang dipilih bukan pelanggan.');
        }

        $customer->update(['is_active' => !$customer->is_active]);

        $status = $customer->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Pelanggan \"{$customer->name}\" berhasil {$status}.");
    }
}
