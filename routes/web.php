<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\{HomeController, CarController, BookingController, DocumentController, ReviewController, PromoController, ProfileController};
use App\Http\Controllers\Admin\{DashboardController, CarManagementController, BookingManagementController, CustomerManagementController, PricingManagementController, PromoManagementController, DocumentVerificationController, PaymentManagementController, DepositManagementController, ReviewManagementController, ReportController, NotificationController};

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kontak', [HomeController::class, 'contact'])->name('contact');
Route::post('/kontak', [HomeController::class, 'sendContact'])->name('contact.send');

// Auth Routes
require __DIR__.'/auth.php';

// Dashboard redirect based on role
Route::get('/dashboard', function () {
    if (auth()->guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->name('dashboard');

// Customer Profile Routes
Route::middleware(['auth:web'])->group(function () {
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    // Customer Logout
    Route::post('/logout', function () {
        auth()->guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('customer.logout');
});

// Cars (public)
Route::get('/mobil', [CarController::class, 'index'])->name('cars.index');
Route::get('/mobil/{car}', [CarController::class, 'show'])->name('cars.show');
Route::post('/mobil/check-availability', [CarController::class, 'checkAvailability'])->name('cars.availability');

// Bookings (auth required)
Route::middleware(['auth:web'])->group(function () {
    Route::get('/booking', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/booking/buat', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/booking/{booking}/payment', [BookingController::class, 'uploadPayment'])->name('bookings.payment');
    Route::post('/booking/{booking}/promo', [BookingController::class, 'applyPromo'])->name('bookings.promo');

    // Documents
    Route::get('/dokumen', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/dokumen', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::delete('/dokumen/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Reviews
    Route::post('/ulasan', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/ulasan/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Promos
    Route::post('/promo/validate', [PromoController::class, 'validatePromo'])->name('promos.validate');
});

// Promos (public)
Route::get('/promo', [PromoController::class, 'index'])->name('promos.index');

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', function () {
        return view('admin.auth.login');
    })->name('login');

    Route::post('/login', function (\Illuminate\Http\Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->guard('admin')->attempt($credentials)) {
            $user = auth()->guard('admin')->user();
            if ($user->isAdmin()) {
                $request->session()->regenerate();
                $request->session()->put('login_type', 'admin');
                return redirect()->intended(route('admin.dashboard'));
            }
            auth()->guard('admin')->logout();
            return back()->withErrors(['email' => 'Akun ini bukan akun admin.']);
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    })->name('login.submit');
});

// Admin Routes (protected)
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Logout
    Route::post('/logout', function () {
        auth()->guard('admin')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect(route('admin.login'));
    })->name('logout');

    // Car Management
    Route::get('/mobil', [CarManagementController::class, 'index'])->name('cars.index');
    Route::get('/mobil/tambah', [CarManagementController::class, 'create'])->name('cars.create');
    Route::post('/mobil', [CarManagementController::class, 'store'])->name('cars.store');
    Route::get('/mobil/{car}/edit', [CarManagementController::class, 'edit'])->name('cars.edit');
    Route::put('/mobil/{car}', [CarManagementController::class, 'update'])->name('cars.update');
    Route::delete('/mobil/{car}', [CarManagementController::class, 'destroy'])->name('cars.destroy');
    Route::post('/mobil/{car}/toggle', [CarManagementController::class, 'toggleStatus'])->name('cars.toggle');
    Route::put('/mobil/{car}/status', [CarManagementController::class, 'updateStatus'])->name('cars.status');
    Route::post('/mobil/{car}/image', [CarManagementController::class, 'uploadImage'])->name('cars.image');
    Route::delete('/mobil/image/{image}', [CarManagementController::class, 'destroyImage'])->name('cars.image.delete');

    // Booking Management
    Route::get('/booking', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::get('/booking/{booking}', [BookingManagementController::class, 'show'])->name('bookings.show');
    Route::put('/booking/{booking}/status', [BookingManagementController::class, 'updateStatus'])->name('bookings.status');
    Route::get('/kalender', [BookingManagementController::class, 'calendar'])->name('bookings.calendar');
    Route::get('/kalender/data', [BookingManagementController::class, 'getCalendarData'])->name('bookings.calendar.data');

    // Customer Management
    Route::get('/pelanggan', [CustomerManagementController::class, 'index'])->name('customers.index');
    Route::get('/pelanggan/{customer}', [CustomerManagementController::class, 'show'])->name('customers.show');
    Route::post('/pelanggan/{customer}/toggle', [CustomerManagementController::class, 'toggleActive'])->name('customers.toggle');

    // Pricing Management
    Route::get('/harga', [PricingManagementController::class, 'index'])->name('pricing.index');
    Route::put('/harga/{car}', [PricingManagementController::class, 'update'])->name('pricing.update');
    Route::post('/harga/bulk', [PricingManagementController::class, 'bulkUpdate'])->name('pricing.bulk');

    // Promo Management
    Route::get('/promo', [PromoManagementController::class, 'index'])->name('promos.index');
    Route::get('/promo/tambah', [PromoManagementController::class, 'create'])->name('promos.create');
    Route::post('/promo', [PromoManagementController::class, 'store'])->name('promos.store');
    Route::get('/promo/{promo}/edit', [PromoManagementController::class, 'edit'])->name('promos.edit');
    Route::put('/promo/{promo}', [PromoManagementController::class, 'update'])->name('promos.update');
    Route::delete('/promo/{promo}', [PromoManagementController::class, 'destroy'])->name('promos.destroy');
    Route::post('/promo/{promo}/toggle', [PromoManagementController::class, 'toggleActive'])->name('promos.toggle');

    // Document Verification
    Route::get('/dokumen', [DocumentVerificationController::class, 'index'])->name('documents.index');
    Route::get('/dokumen/{document}', [DocumentVerificationController::class, 'show'])->name('documents.show');
    Route::post('/dokumen/{document}/verify', [DocumentVerificationController::class, 'verify'])->name('documents.verify');
    Route::post('/dokumen/{document}/reject', [DocumentVerificationController::class, 'reject'])->name('documents.reject');

    // Payment Management
    Route::get('/pembayaran', [PaymentManagementController::class, 'index'])->name('payments.index');
    Route::get('/pembayaran/{payment}', [PaymentManagementController::class, 'show'])->name('payments.show');
    Route::post('/pembayaran/{payment}/verify', [PaymentManagementController::class, 'verify'])->name('payments.verify');
    Route::post('/pembayaran/{payment}/reject', [PaymentManagementController::class, 'reject'])->name('payments.reject');

    // Deposit Management
    Route::get('/deposit', [DepositManagementController::class, 'index'])->name('deposits.index');
    Route::post('/deposit/{deposit}/return', [DepositManagementController::class, 'returnDeposit'])->name('deposits.return');
    Route::post('/deposit/{deposit}/deduct', [DepositManagementController::class, 'deductDeposit'])->name('deposits.deduct');

    // Review Management
    Route::get('/ulasan', [ReviewManagementController::class, 'index'])->name('reviews.index');
    Route::post('/ulasan/{review}/approve', [ReviewManagementController::class, 'approve'])->name('reviews.approve');
    Route::post('/ulasan/{review}/reject', [ReviewManagementController::class, 'reject'])->name('reviews.reject');
    Route::post('/ulasan/{review}/reply', [ReviewManagementController::class, 'reply'])->name('reviews.reply');

    // Reports
    Route::get('/laporan/pendapatan', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/laporan/penyewaan', [ReportController::class, 'rentals'])->name('reports.rentals');
    Route::get('/laporan/pendapatan/pdf', [ReportController::class, 'exportRevenuePdf'])->name('reports.revenue.pdf');
    Route::get('/laporan/pendapatan/excel', [ReportController::class, 'exportRevenueExcel'])->name('reports.revenue.excel');
    Route::get('/laporan/penyewaan/pdf', [ReportController::class, 'exportRentalsPdf'])->name('reports.rentals.pdf');
    Route::get('/laporan/penyewaan/excel', [ReportController::class, 'exportRentalsExcel'])->name('reports.rentals.excel');

    // Notifications
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifikasi/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});
