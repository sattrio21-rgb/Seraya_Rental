<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = auth()->user()->documents()->latest()->get();
        return view('customer.documents.index', compact('documents'));
    }

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:ktp,sim',
            'file' => 'required|image|max:5120',
        ]);
        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('documents', 'public');
        }
        $validated['user_id'] = auth()->id();
        Document::create($validated);
        return back()->with('success', 'Dokumen berhasil diupload. Menunggu verifikasi admin.');
    }

    public function destroy(Document $document)
    {
        if ($document->user_id !== auth()->id()) abort(403);
        $document->delete();
        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
