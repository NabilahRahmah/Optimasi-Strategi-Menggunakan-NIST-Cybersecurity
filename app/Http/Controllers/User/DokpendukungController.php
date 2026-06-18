<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\DokumenPendukung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DokpendukungController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->user_id;

        // Ambil semua domain + dokumen milik user (assessment & manual)
        $domains = Domain::with([
            'dokumenPendukungs' => function ($q) use ($userId) {
                $q->where('user_id', $userId)
                    ->with(['jawaban.pertanyaan'])
                    ->latest();
            }
        ])->orderBy('kode')->get();

        $totalDokumen = DokumenPendukung::where('user_id', $userId)->count();
        $dariAssessment = DokumenPendukung::where('user_id', $userId)->where('sumber', 'assessment')->count();
        $manual = DokumenPendukung::where('user_id', $userId)->where('sumber', 'manual')->count();

        return view('user.dokpendukung.index', compact(
            'domains',
            'totalDokumen',
            'dariAssessment',
            'manual'
        ));
    }

    public function create()
    {
        $domains = Domain::orderBy('kode')->get();
        return view('user.dokpendukung.create', compact('domains'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'domain_id' => 'required|exists:domains,domain_id',
            'nama_dokumen' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,docx,xlsx|max:10240',
        ]);

        $file = $request->file('file');
        $namaFileAsli = $file->getClientOriginalName();
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('dokumen/' . date('Y/m'), $filename, 'local');

        DokumenPendukung::create([
            'user_id' => auth()->user()->user_id,
            'domain_id' => $request->domain_id,
            'nama_dokumen' => $request->nama_dokumen,
            'deskripsi' => $request->deskripsi,
            'file_path' => $path,
            'nama_file_asli' => $namaFileAsli,
            'ukuran_file' => $file->getSize(),
            'status' => 'aktif',
            'sumber' => 'manual',
        ]);

        return redirect()
            ->route('user.dokpendukung.index')
            ->with('success', 'Dokumen berhasil diupload!');
    }

    public function destroy($id)
    {
        $dokumen = DokumenPendukung::where('dok_id', $id)
            ->where('user_id', auth()->user()->user_id)
            ->firstOrFail();

        if ($dokumen->file_path) {
            Storage::disk('local')->delete($dokumen->file_path);
        }

        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus!');
    }

    public function download($id)
    {
        $dokumen = DokumenPendukung::where('dok_id', $id)
            ->where('user_id', auth()->user()->user_id)
            ->firstOrFail();

        $fullPath = storage_path('app/' . $dokumen->file_path);

        if (!file_exists($fullPath)) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        return response()->download($fullPath, $dokumen->nama_file_asli);
    }

    public function preview($id)
    {
        $dokumen = DokumenPendukung::where('dok_id', $id)
            ->where('user_id', auth()->user()->user_id)
            ->firstOrFail();

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
}