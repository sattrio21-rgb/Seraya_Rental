<?php

namespace App\Services;

use App\Models\{Booking, Payment, Document, NotificationLog};

class NotificationService
{
    public function sendBookingStatus(Booking $booking, string $status): void
    {
        $messages = [
            'confirmed' => 'Booking Anda telah dikonfirmasi. Silakan lakukan pembayaran.',
            'active' => 'Booking Anda telah aktif. Selamat menikmati perjalanan!',
            'completed' => 'Booking Anda telah selesai. Terima kasih telah menggunakan layanan kami.',
            'cancelled' => 'Booking Anda telah dibatalkan.',
        ];

        NotificationLog::create([
            'user_id' => $booking->user_id,
            'type' => 'booking_status',
            'title' => 'Status Booking Diperbarui',
            'message' => $messages[$status] ?? "Status booking {$status}",
            'data' => ['booking_id' => $booking->id, 'status' => $status],
        ]);
    }

    public function sendPaymentStatus(Payment $payment, string $status): void
    {
        $messages = [
            'verified' => 'Pembayaran Anda telah diverifikasi.',
            'rejected' => 'Pembayaran Anda ditolak. Silakan upload ulang bukti pembayaran.',
        ];

        NotificationLog::create([
            'user_id' => $payment->booking->user_id,
            'type' => 'payment_status',
            'title' => 'Status Pembayaran',
            'message' => $messages[$status] ?? "Status pembayaran {$status}",
            'data' => ['payment_id' => $payment->id, 'status' => $status],
        ]);
    }

    public function sendDocumentStatus(Document $document, string $status): void
    {
        $messages = [
            'verified' => 'Dokumen ' . strtoupper($document->type) . ' Anda telah diverifikasi.',
            'rejected' => 'Dokumen ' . strtoupper($document->type) . ' Anda ditolak. Silakan upload ulang.',
        ];

        NotificationLog::create([
            'user_id' => $document->user_id,
            'type' => 'document_status',
            'title' => 'Status Dokumen',
            'message' => $messages[$status] ?? "Status dokumen {$status}",
            'data' => ['document_id' => $document->id, 'status' => $status],
        ]);
    }
}
