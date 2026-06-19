<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::active()->valid()->latest()->get();
        return view('customer.promos.index', compact('promos'));
    }

    public function validatePromo(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $promo = Promo::active()->valid()->where('code', $request->code)->first();
        return response()->json(['valid' => !!$promo, 'promo' => $promo]);
    }
}
