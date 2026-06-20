<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use App\Models\Payment;
use App\Models\ContactMessage;
use App\Models\NotificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin dengan statistik utama.
     */
    public function index()
    {
        // Statistik utama
        $totalCars = Car::count();
        $totalBookings = Booking::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalRevenue = Payment::where('status', 'verified')->sum('amount');

        // Booking berdasarkan status
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $activeBookings = Booking::where('status', 'active')->count();
        $completedBookings = Booking::where('status', 'completed')->count();

        // Mobil tersedia
        $availableCars = Car::where('status', 'available')->where('is_active', true)->count();

        // Booking terbaru (10 terakhir)
        $recentBookings = Booking::with(['user', 'car'])
            ->latest()
            ->limit(10)
            ->get();

        // Pembayaran yang belum diverifikasi
        $pendingPayments = Payment::where('status', 'pending')->count();

        // Pesan kontak yang belum dibaca
        $unreadMessages = ContactMessage::unread()->count();

        // Notifikasi yang belum dibaca
        $unreadCount = NotificationLog::where('user_id', auth()->id())->unread()->count();

        // Pendapatan bulan ini
        $monthlyRevenue = Payment::where('status', 'verified')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        // Booking bulan ini
        $currentMonthBookings = Booking::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        return view('admin.dashboard', compact(
            'totalCars',
            'totalBookings',
            'totalCustomers',
            'totalRevenue',
            'pendingBookings',
            'confirmedBookings',
            'activeBookings',
            'completedBookings',
            'availableCars',
            'recentBookings',
            'pendingPayments',
            'unreadMessages',
            'monthlyRevenue',
            'currentMonthBookings',
            'unreadCount'
        ));
    }
}
