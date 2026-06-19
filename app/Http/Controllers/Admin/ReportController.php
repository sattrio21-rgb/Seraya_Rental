<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    /**
     * Tampilkan laporan pendapatan.
     */
    public function revenue(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfMonth();

        // Total pendapatan dari pembayaran terverifikasi
        $totalRevenue = Payment::where('status', 'verified')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // Pendapatan per bulan (12 bulan terakhir)
        $monthlyRevenue = Payment::where('status', 'verified')
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Pendapatan per mobil
        $revenueByCar = Payment::where('status', 'verified')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('cars', 'bookings.car_id', '=', 'cars.id')
            ->selectRaw('cars.name as car_name, SUM(payments.amount) as total')
            ->groupBy('cars.name')
            ->orderByDesc('total')
            ->get();

        // Detail pembayaran dalam rentang tanggal
        $payments = Payment::with(['booking.user', 'booking.car'])
            ->where('status', 'verified')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.revenue', compact(
            'totalRevenue',
            'monthlyRevenue',
            'revenueByCar',
            'payments',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Tampilkan laporan penyewaan.
     */
    public function rentals(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfMonth();

        // Total booking dalam rentang tanggal
        $totalBookings = Booking::whereBetween('created_at', [$startDate, $endDate])->count();

        // Booking berdasarkan status
        $bookingsByStatus = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Booking per mobil (Top 10)
        $bookingsByCar = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->join('cars', 'bookings.car_id', '=', 'cars.id')
            ->selectRaw('cars.name as car_name, COUNT(*) as count')
            ->groupBy('cars.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Booking per bulan (12 bulan terakhir)
        $monthlyBookings = Booking::where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Durasi rata-rata penyewaan
        $averageDuration = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('AVG(DATEDIFF(end_date, start_date) + 1) as avg_duration')
            ->value('avg_duration');

        // Detail booking dalam rentang tanggal
        $bookings = Booking::with(['user', 'car'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.rentals', compact(
            'totalBookings',
            'bookingsByStatus',
            'bookingsByCar',
            'monthlyBookings',
            'averageDuration',
            'bookings',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export laporan pendapatan sebagai PDF.
     */
    public function exportRevenuePdf(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth()->startOfDay();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfMonth()->endOfDay();

        $totalRevenue = Payment::where('status', 'verified')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $payments = Payment::with(['booking.user', 'booking.car'])
            ->where('status', 'verified')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $revenueByCar = Payment::where('status', 'verified')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('cars', 'bookings.car_id', '=', 'cars.id')
            ->selectRaw('cars.name as car_name, SUM(payments.amount) as total')
            ->groupBy('cars.name')
            ->orderByDesc('total')
            ->get();

        // Menggunakan view PDF (memerlukan package seperti barryvdh/laravel-dompdf)
        $view = view('admin.reports.revenue-pdf', compact(
            'totalRevenue',
            'payments',
            'revenueByCar',
            'startDate',
            'endDate'
        ))->render();

        // Placeholder: gunakan PDF library yang tersedia
        // return \Barryvdh\DomPDF\Facade\Pdf::loadHtml($view)
        //     ->setPaper('a4', 'landscape')
        //     ->download('laporan-pendapatan-' . $startDate->format('Y-m-d') . '.pdf');

        return response($view, 200)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="laporan-pendapatan.pdf"');
    }

    /**
     * Export laporan pendapatan sebagai Excel.
     */
    public function exportRevenueExcel(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth()->startOfDay();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfMonth()->endOfDay();

        $payments = Payment::with(['booking.user', 'booking.car'])
            ->where('status', 'verified')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        // Menggunakan Excel export (memerlukan package seperti maatwebsite/excel)
        // return \Maatwebsite\Excel\Facades\Excel::download(
        //     new RevenueExport($payments, $startDate, $endDate),
        //     'laporan-pendapatan-' . $startDate->format('Y-m-d') . '.xlsx'
        // );

        // Placeholder: generate CSV sebagai alternatif
        $filename = 'laporan-pendapatan-' . $startDate->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Kode Booking', 'Pelanggan', 'Mobil', 'Jumlah', 'Metode Pembayaran', 'Bank', 'Tanggal Verifikasi']);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->booking->booking_code,
                    $payment->booking->user->name,
                    $payment->booking->car->name,
                    $payment->amount,
                    $payment->payment_method,
                    $payment->bank_name,
                    $payment->verified_at?->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export laporan penyewaan sebagai PDF.
     */
    public function exportRentalsPdf(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth()->startOfDay();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfMonth()->endOfDay();

        $bookings = Booking::with(['user', 'car'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $totalBookings = $bookings->count();

        $bookingsByStatus = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Placeholder: gunakan PDF library yang tersedia
        $view = view('admin.reports.rentals-pdf', compact(
            'totalBookings',
            'bookingsByStatus',
            'bookings',
            'startDate',
            'endDate'
        ))->render();

        return response($view, 200)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="laporan-penyewaan.pdf"');
    }

    /**
     * Export laporan penyewaan sebagai Excel.
     */
    public function exportRentalsExcel(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth()->startOfDay();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfMonth()->endOfDay();

        $bookings = Booking::with(['user', 'car'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        // Placeholder: generate CSV sebagai alternatif
        $filename = 'laporan-penyewaan-' . $startDate->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Kode Booking', 'Pelanggan', 'Mobil', 'Tanggal Mulai', 'Tanggal Selesai', 'Durasi (hari)', 'Total Harga', 'Status']);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_code,
                    $booking->user->name,
                    $booking->car->name,
                    $booking->start_date->format('d/m/Y'),
                    $booking->end_date->format('d/m/Y'),
                    $booking->getDurationInDays(),
                    $booking->total_price,
                    $booking->getStatusLabel(),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
