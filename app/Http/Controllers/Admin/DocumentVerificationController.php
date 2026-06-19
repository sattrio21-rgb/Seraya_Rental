<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class DocumentVerificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Tampilkan daftar dokumen yang perlu diverifikasi.
     */
    public function index(Request $request)
    {
        $query = Document::with(['user', 'verifier']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tipe dokumen
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Pencarian berdasarkan nama pengguna
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $documents = $query->latest()->paginate(15)->withQueryString();

        return view('admin.documents.index', compact('documents'));
    }

    /**
     * Tampilkan detail dokumen.
     */
    public function show(Document $document)
    {
        $document->load(['user', 'verifier']);

        return view('admin.documents.show', compact('document'));
    }

    /**
     * Verifikasi dokumen (setujui).
     */
    public function verify(Document $document)
    {
        $document->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Kirim notifikasi ke pelanggan
        $this->notificationService->sendDocumentStatus($document, 'verified');

        return redirect()->route('admin.documents.show', $document)
            ->with('success', "Dokumen \"{$document->type}\" milik \"{$document->user->name}\" berhasil diverifikasi.");
    }

    /**
     * Tolak dokumen.
     */
    public function reject(Request $request, Document $document)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $document->update([
            'status'           => 'rejected',
            'verified_by'      => auth()->id(),
            'verified_at'      => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Kirim notifikasi ke pelanggan
        $this->notificationService->sendDocumentStatus($document, 'rejected');

        return redirect()->route('admin.documents.show', $document)
            ->with('success', "Dokumen \"{$document->type}\" milik \"{$document->user->name}\" berhasil ditolak.");
    }
}
