<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentManagementController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Tampilkan daftar semua pembayaran.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['booking.user', 'booking.car', 'verifier']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Pencarian berdasarkan kode booking atau nama pengirim
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('account_name', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%")
                  ->orWhereHas('booking', function ($q2) use ($search) {
                      $q2->where('booking_code', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Tampilkan detail pembayaran.
     */
    public function show(Payment $payment)
    {
        $payment->load(['booking.user', 'booking.car', 'verifier']);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Verifikasi pembayaran.
     */
    public function verify(Payment $payment)
    {
        $this->paymentService->verifyPayment($payment, auth()->user());

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', "Pembayaran sebesar Rp " . number_format($payment->amount, 0, ',', '.') . " berhasil diverifikasi.");
    }

    /**
     * Tolak pembayaran.
     */
    public function reject(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $this->paymentService->rejectPayment($payment, auth()->user(), $validated['reason']);

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', "Pembayaran sebesar Rp " . number_format($payment->amount, 0, ',', '.') . " berhasil ditolak.");
    }
}
