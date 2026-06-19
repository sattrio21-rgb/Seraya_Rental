<?php

namespace App\Services;

use App\Models\{Payment, User, Booking};

class PaymentService
{
    public function verifyPayment(Payment $payment, User $admin): void
    {
        $payment->update([
            'status' => 'verified',
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);

        // Check if all payments for this booking are verified
        $booking = $payment->booking;
        $totalPaid = $booking->payments()->where('status', 'verified')->sum('amount');
        if ($totalPaid >= $booking->total_price) {
            $booking->update(['status' => 'confirmed']);
        }
    }

    public function rejectPayment(Payment $payment, User $admin, string $reason): void
    {
        $payment->update([
            'status' => 'rejected',
            'verified_by' => $admin->id,
            'verified_at' => now(),
            'notes' => $reason,
        ]);
    }
}
