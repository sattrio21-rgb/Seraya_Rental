<?php

namespace App\Services;

use App\Models\{Booking, Car, Promo, Payment};
use Carbon\Carbon;

class BookingService
{
    public function generateBookingCode(): string
    {
        return 'BK-' . now()->format('Ymd') . '-' . strtoupper(uniqid());
    }

    public function calculateTotalPrice(Carbon $start, Carbon $end, Car $car, string $rentalType): float
    {
        $days = $start->diffInDays($end) + 1;
        $dailyRate = $rentalType === 'with_driver' && $car->price_with_driver
            ? $car->price_with_driver
            : $car->price_per_day;

        return $dailyRate * $days;
    }

    public function calculateLateFee(Booking $booking): float
    {
        if (!$booking->end_date->isPast() || $booking->status === 'cancelled') {
            return 0;
        }

        $lateDays = $booking->end_date->diffInDays(now());
        $dailyRate = $booking->rental_type === 'with_driver' && $booking->car->price_with_driver
            ? $booking->car->price_with_driver
            : $booking->car->price_per_day;

        return $dailyRate * $lateDays * 0.1;
    }

    public function checkAvailability(Car $car, Carbon $start, Carbon $end): bool
    {
        return !$car->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('start_date', '<=', $start)
                          ->where('end_date', '>=', $end);
                    });
            })
            ->exists();
    }

    public function applyPromo(Booking $booking, Promo $promo): float
    {
        if ($booking->total_price < $promo->min_booking_amount) {
            return 0;
        }

        return $promo->calculateDiscount($booking->total_price);
    }
}
