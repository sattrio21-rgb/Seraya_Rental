<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\{Booking, Car, Payment, Promo};
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()->bookings()->with('car')->latest()->paginate(10);
        return view('customer.bookings.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $car = Car::findOrFail($request->car_id);
        $service = new BookingService();
        return view('customer.bookings.create', compact('car'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pickup_location' => 'required|string|max:255',
            'return_location' => 'required|string|max:255',
            'rental_type' => 'required|in:with_driver,without_driver',
        ]);

        $car = Car::findOrFail($validated['car_id']);
        $service = new BookingService();

        if (!$service->checkAvailability($car, \Carbon\Carbon::parse($validated['start_date']), \Carbon\Carbon::parse($validated['end_date']))) {
            return back()->with('error', 'Mobil tidak tersedia untuk tanggal tersebut.');
        }

        $totalPrice = $service->calculateTotalPrice(
            \Carbon\Carbon::parse($validated['start_date']),
            \Carbon\Carbon::parse($validated['end_date']),
            $car,
            $validated['rental_type']
        );

        $validated['user_id'] = auth()->id();
        $validated['booking_code'] = $service->generateBookingCode();
        $validated['total_price'] = $totalPrice;
        $validated['status'] = 'pending';

        $booking = Booking::create($validated);

        return redirect()->route('bookings.show', $booking)->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran.');
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) abort(403);
        $booking->load(['car', 'payments', 'deposit', 'review']);
        return view('customer.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) abort(403);
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Booking tidak dapat dibatalkan.');
        }
        $booking->update(['status' => 'cancelled', 'cancelled_reason' => 'Dibatalkan oleh pelanggan']);
        (new \App\Services\NotificationService())->sendBookingStatus($booking, 'cancelled');
        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    public function uploadPayment(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) abort(403);
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'account_name' => 'nullable|string',
            'payment_proof' => 'required|image|max:5120',
        ]);
        if ($request->hasFile('payment_proof')) {
            $validated['payment_proof'] = $request->file('payment_proof')->store('payments', 'public');
        }
        $validated['booking_id'] = $booking->id;
        $validated['status'] = 'pending';
        Payment::create($validated);
        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

    public function applyPromo(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) abort(403);
        $request->validate(['code' => 'required|string']);
        $promo = Promo::active()->valid()->where('code', $request->code)->first();
        if (!$promo) return back()->with('error', 'Kode promo tidak valid atau sudah kedaluwarsa.');
        $service = new BookingService();
        $discount = $service->applyPromo($booking, $promo);
        if ($discount <= 0) return back()->with('error', 'Promo tidak dapat digunakan untuk booking ini.');
        $booking->update(['promo_id' => $promo->id, 'discount_amount' => $discount, 'total_price' => $booking->total_price - $discount]);
        $promo->increment('used_count');
        return back()->with('success', "Promo berhasil diterapkan! Diskon: Rp " . number_format($discount, 0, ',', '.'));
    }
}
