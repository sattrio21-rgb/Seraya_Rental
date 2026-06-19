<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\{Car, Promo, ContactMessage};

class HomeController extends Controller
{
    public function index()
    {
        $featuredCars = Car::available()->with('reviews')->limit(6)->get();
        return view('customer.home', compact('featuredCars'));
    }

    public function contact()
    {
        return view('customer.contact');
    }

    public function sendContact(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);
        ContactMessage::create($validated);
        return redirect()->route('contact')->with('success', 'Pesan berhasil dikirim! Kami akan segera merespons.');
    }
}
