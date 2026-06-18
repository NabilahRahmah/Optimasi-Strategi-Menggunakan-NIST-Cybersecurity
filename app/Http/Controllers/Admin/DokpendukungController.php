<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\DokumenPendukung;
use Illuminate\Support\Facades\Storage;

class DokpendukungController extends Controller
{
    public function index()
    {
        $domains = Domain::withCount('dokumenPendukungs')
            ->with([
                'dokumenPendukungs' => function ($q) {
                    $q->with(['user', 'jawaban.pertanyaan'])->latest();
                }
            ])
            ->orderBy('kode')
            ->get();

        $totalDokumen = DokumenPendukung::count();
        $dariAssessment = DokumenPendukung::where('sumber', 'assessment')->count();
        $manual = DokumenPendukung::where('sumber', 'manual')->count();

        return view('admin.dokpendukung.index', compact(
            'domains',
            'totalDokumen',
            'dariAssessment',
            'manual'
        ));
    }

    public function preview($id)
    {
        $dokumen = DokumenPendukung::findOrFail($id);
        $fullPath = storage_path('app/' . $dokumen->file_path);

        if (!file_exists($fullPath)) {
            abort(404, 'File tidak ditemukan.');
        }

        $ext = strtolower(pathinfo($dokumen->nama_file_asli, PATHINFO_EXTENSION));
        $mimeMap = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
        ];
        $mime = $mimeMap[$ext] ?? 'application/octet-stream';

        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $dokumen->nama_file_asli . '"',
        ]);
    }

    public function create()
    {
        $domains = Domain::orderBy('kode_domain')->get();
        return view('admin.dokpendukung.create', compact('domains'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'domain_id' => 'required|exists:domains,domain_id',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('dokumen_pendukung', 'local');

        DokumenPendukung::create([
            'user_id' => auth()->user()->user_id,
            'domain_id' => $request->domain_id,
            'nama_dokumen' => $request->nama_dokumen,
            'deskripsi' => $request->deskripsi,
            'file_path' => $path,
            'nama_file_asli' => $file->getClientOriginalName(),
            'ukuran_file' => $file->getSize(),
            'status' => 'aktif',
            'sumber' => 'manual',
        ]);

        return redirect()->route('admin.dokpendukung.index')
            ->with('success', 'Dokumen berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $dokumen = DokumenPendukung::findOrFail($id);

        if ($dokumen->file_path) {
            Storage::disk('local')->delete($dokumen->file_path);
        }

        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus!');
    }

    public function download($id)
    {
        $dokumen = DokumenPendukung::findOrFail($id);
        $fullPath = storage_path('app/' . $dokumen->file_path);

        if (!file_exists($fullPath)) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        return response()->download($fullPath, $dokumen->nama_file_asli);
    }
}