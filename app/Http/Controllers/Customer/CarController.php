<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::available()->with('reviews');
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('brand', 'like', "%{$search}%");
            });
        }
        if ($brand = $request->brand) $query->byBrand($brand);
        if ($transmission = $request->transmission) $query->byTransmission($transmission);
        if ($minPrice = $request->min_price) $query->where('price_per_day', '>=', $minPrice);
        if ($maxPrice = $request->max_price) $query->where('price_per_day', '<=', $maxPrice);
        $cars = $query->paginate(12);
        $brands = Car::distinct()->pluck('brand');
        return view('customer.cars.index', compact('cars', 'brands'));
    }

    public function show(Car $car)
    {
        $car->load(['reviews' => fn($q) => $q->approved()->with('user')]);
        return view('customer.cars.show', compact('car'));
    }

    public function checkAvailability(Request $request)
    {
        $request->validate(['car_id' => 'required', 'start_date' => 'required|date', 'end_date' => 'required|date|after_or_equal:start_date']);
        $car = Car::findOrFail($request->car_id);
        $service = new \App\Services\BookingService();
        $available = $service->checkAvailability($car, \Carbon\Carbon::parse($request->start_date), \Carbon\Carbon::parse($request->end_date));
        return response()->json(['available' => $available]);
    }
}
