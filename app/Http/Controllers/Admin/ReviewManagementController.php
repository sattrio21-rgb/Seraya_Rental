<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewManagementController extends Controller
{
    /**
     * Tampilkan daftar semua ulasan.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'car', 'booking']);

        // Filter berdasarkan status approved
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Filter berdasarkan rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Pencarian berdasarkan nama pengguna atau mobil
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })->orWhereHas('car', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        // Statistik
        $totalReviews = Review::count();
        $approvedReviews = Review::where('is_approved', true)->count();
        $pendingReviews = Review::where('is_approved', false)->count();
        $averageRating = Review::where('is_approved', true)->avg('rating');

        return view('admin.reviews.index', compact(
            'reviews',
            'totalReviews',
            'approvedReviews',
            'pendingReviews',
            'averageRating'
        ));
    }

    /**
     * Setujui ulasan.
     */
    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);

        return redirect()->back()
            ->with('success', "Ulasan dari \"{$review->user->name}\" untuk mobil \"{$review->car->name}\" berhasil disetujui.");
    }

    /**
     * Tolak ulasan.
     */
    public function reject(Review $review)
    {
        $review->update(['is_approved' => false]);

        return redirect()->back()
            ->with('success', "Ulasan dari \"{$review->user->name}\" untuk mobil \"{$review->car->name}\" berhasil ditolak.");
    }

    /**
     * Balas ulasan dari admin.
     */
    public function reply(Request $request, Review $review)
    {
        $validated = $request->validate([
            'admin_reply' => 'required|string|max:1000',
        ]);

        $review->update(['admin_reply' => $validated['admin_reply']]);

        return redirect()->back()
            ->with('success', 'Balasan ulasan berhasil disimpan.');
    }
}
