<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Tampilkan daftar notifikasi admin.
     */
    public function index(Request $request)
    {
        $query = NotificationLog::where('user_id', auth()->id());

        // Filter berdasarkan status dibaca
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }

        // Filter berdasarkan tipe notifikasi
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->latest()->paginate(20)->withQueryString();

        $unreadCount = NotificationLog::where('user_id', auth()->id())
            ->unread()
            ->count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(NotificationLog $notification)
    {
        // Pastikan notifikasi ini milik user yang sedang login
        if ($notification->user_id !== auth()->id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses ke notifikasi ini.');
        }

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca.
     */
    public function markAllAsRead()
    {
        NotificationLog::where('user_id', auth()->id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return redirect()->back()
            ->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
