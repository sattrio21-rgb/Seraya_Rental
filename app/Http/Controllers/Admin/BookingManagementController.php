<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class BookingManagementController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Tampilkan daftar semua booking.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'car']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Pencarian berdasarkan kode booking atau nama pelanggan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Tampilkan detail satu booking.
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'car', 'promo', 'payments', 'deposit', 'review', 'damageRecords']);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Perbarui status booking.
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status'           => 'required|in:confirmed,active,completed,cancelled',
            'cancelled_reason' => 'required_if:status,cancelled|nullable|string|max:500',
        ]);

        $newStatus = $validated['status'];

        // Validasi transisi status
        $allowedTransitions = [
            'pending'   => ['confirmed', 'cancelled'],
            'confirmed' => ['active', 'cancelled'],
            'active'    => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => [],
        ];

        if (!in_array($newStatus, $allowedTransitions[$booking->status] ?? [])) {
            return redirect()->back()
                ->with('error', "Tidak dapat mengubah status dari \"{$booking->getStatusLabel()}\" ke status yang dipilih.");
        }

        // Set alasan pembatalan jika status = cancelled
        $updateData = ['status' => $newStatus];
        if ($newStatus === 'cancelled') {
            $updateData['cancelled_reason'] = $validated['cancelled_reason'] ?? null;
        }

        $booking->update($updateData);

        // Kirim notifikasi ke pelanggan
        $this->notificationService->sendBookingStatus($booking, $newStatus);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', "Status booking \"{$booking->booking_code}\" berhasil diubah menjadi \"{$booking->getStatusLabel()}\".");
    }

    /**
     * Tampilkan halaman kalender booking.
     */
    public function calendar()
    {
        return view('admin.bookings.calendar');
    }

    /**
     * Ambil data booking untuk kalender (JSON).
     */
    public function getCalendarData(Request $request)
    {
        $start = $request->input('start', now()->startOfMonth()->toIso8601String());
        $end = $request->input('end', now()->endOfMonth()->toIso8601String());

        $bookings = Booking::with(['user', 'car'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->where('start_date', '<=', $start)
                         ->where('end_date', '>=', $end);
                  });
            })
            ->get()
            ->map(function ($booking) {
                return [
                    'id'     => $booking->id,
                    'title'  => "{$booking->booking_code} - {$booking->car->name}",
                    'start'  => $booking->start_date->toIso8601String(),
                    'end'    => $booking->end_date->addDay()->toIso8601String(),
                    'color'  => match ($booking->status) {
                        'pending'   => '#ffc107',
                        'confirmed' => '#0d6efd',
                        'active'    => '#198754',
                        'completed' => '#6c757d',
                        default     => '#dc3545',
                    },
                    'status' => $booking->getStatusLabel(),
                ];
            });

        return response()->json($bookings);
    }
}
