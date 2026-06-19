<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;

class DepositManagementController extends Controller
{
    /**
     * Tampilkan daftar semua deposit.
     */
    public function index(Request $request)
    {
        $query = Deposit::with(['booking.user', 'booking.car']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pencarian berdasarkan kode booking atau nama pelanggan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('booking', function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $deposits = $query->latest()->paginate(15)->withQueryString();

        // Statistik deposit
        $totalHeld = Deposit::where('status', 'held')->sum('amount');
        $totalReturned = Deposit::where('status', 'returned')->sum('amount');
        $totalDeducted = Deposit::where('status', 'deducted')->sum('amount');

        return view('admin.deposits.index', compact('deposits', 'totalHeld', 'totalReturned', 'totalDeducted'));
    }

    /**
     * Kembalikan deposit ke pelanggan.
     */
    public function returnDeposit(Deposit $deposit)
    {
        if ($deposit->status !== 'held') {
            return redirect()->back()
                ->with('error', 'Deposit tidak dapat dikembalikan karena statusnya bukan "ditahan".');
        }

        $deposit->update([
            'status'      => 'returned',
            'returned_at' => now(),
            'notes'       => 'Deposit dikembalikan oleh admin.',
        ]);

        return redirect()->route('admin.deposits.index')
            ->with('success', "Deposit sebesar Rp " . number_format($deposit->amount, 0, ',', '.') . " untuk booking \"{$deposit->booking->booking_code}\" berhasil dikembalikan.");
    }

    /**
     * Potong deposit (untuk kerusakan/denda).
     */
    public function deductDeposit(Request $request, Deposit $deposit)
    {
        if ($deposit->status !== 'held') {
            return redirect()->back()
                ->with('error', 'Deposit tidak dapat dipotong karena statusnya bukan "ditahan".');
        }

        $validated = $request->validate([
            'deduction_amount' => 'required|numeric|min:0|max:' . $deposit->amount,
            'reason'           => 'required|string|max:500',
        ]);

        $deposit->update([
            'status'      => 'deducted',
            'returned_at' => now(),
            'notes'       => "Potongan deposit: Rp " . number_format($validated['deduction_amount'], 0, ',', '.') . ". Alasan: {$validated['reason']}",
        ]);

        return redirect()->route('admin.deposits.index')
            ->with('success', "Deposit sebesar Rp " . number_format($deposit->amount, 0, ',', '.') . " untuk booking \"{$deposit->booking->booking_code}\" berhasil dipotong.");
    }
}
