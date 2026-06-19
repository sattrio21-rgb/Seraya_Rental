<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\{Review, Booking};
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);
        $booking = Booking::findOrFail($validated['booking_id']);
        if ($booking->user_id !== auth()->id() || $booking->status !== 'completed') {
            return back()->with('error', 'Anda hanya dapat memberikan ulasan untuk booking yang sudah selesai.');
        }
        if (Review::where('booking_id', $booking->id)->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk booking ini.');
        }
        $validated['user_id'] = auth()->id();
        $validated['car_id'] = $booking->car_id;
        Review::create($validated);
        return back()->with('success', 'Ulasan berhasil dikirim!');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) abort(403);
        $review->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
