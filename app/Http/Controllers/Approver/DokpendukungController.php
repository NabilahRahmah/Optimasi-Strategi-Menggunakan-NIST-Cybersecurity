<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\DokumenPendukung;
use Illuminate\Support\Facades\Storage;

class DokpendukungController extends Controller
{
    /**
     * Lihat semua dokumen semua user — grouped per domain
     */
    public function index()
    {
        $domains = Domain::with([
            'dokumenPendukungs' => function ($q) {
                $q->with('user')->latest();
            }
        ])->orderBy('kode')->get();

        $totalDokumen = DokumenPendukung::count();

        return view('approver.dokumen.index', compact('domains', 'totalDokumen'));
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