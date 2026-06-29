<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Domain;
use App\Models\Rekomendasi;
use Illuminate\Http\Request;

class RekomendasiController extends Controller
{
    // List semua assessment disetujui yang bisa dikasih rekomendasi manual
    public function index()
    {
        $approverId = auth()->user()->user_id;
        $frameworkIds = \App\Models\FrameworkAssignment::where('user_id', $approverId)->pluck('framework_id');

        $assessments = Assessment::with(['user', 'rekomendasis'])
            ->where('status', 'disetujui')
            ->whereIn('framework_id', $frameworkIds)
            ->latest()
            ->get();

        return view('approver.rekomendasi.index', compact('assessments'));
    }

    // Form tambah rekomendasi manual untuk satu assessment
    public function create(Assessment $assessment)
    {
        abort_if(!auth()->user()->isAssignedTo($assessment->framework_id), 403, 'Akses ditolak.');

        $domains = Domain::where('framework_id', $assessment->framework_id)
            ->orderBy('kode_domain')
            ->get();

        $rekomendasis = Rekomendasi::with('domain')
            ->where('assessment_id', $assessment->assessment_id)
            ->orderByRaw("FIELD(sumber, 'approver', 'otomatis')")
            ->orderByRaw("FIELD(prioritas, 'Tinggi', 'Sedang', 'Rendah')")
            ->get();

        return view('approver.rekomendasi.create', compact(
            'assessment',
            'domains',
            'rekomendasis'
        ));
    }

    // Simpan rekomendasi manual dari approver
    public function store(Request $request, Assessment $assessment)
    {
        abort_if(!auth()->user()->isAssignedTo($assessment->framework_id), 403, 'Akses ditolak.');

        $request->validate([
            'domain_id'           => 'required|exists:domains,domain_id',
            'deskripsi_perbaikan' => 'required|string|max:1000',
            'prioritas'           => 'required|in:Tinggi,Sedang,Rendah',
        ]);

        Rekomendasi::create([
            'assessment_id'       => $assessment->assessment_id,
            'domain_id'           => $request->domain_id,
            'deskripsi_perbaikan' => $request->deskripsi_perbaikan,
            'prioritas'           => $request->prioritas,
            'sumber'              => 'approver',
        ]);

        return back()->with('success', 'Rekomendasi berhasil ditambahkan!');
    }

    // Hapus rekomendasi manual
    public function destroy(Rekomendasi $rekomendasi)
    {
        if ($rekomendasi->sumber !== 'approver') {
            return back()->with('error', 'Hanya rekomendasi dari approver yang bisa dihapus!');
        }

        $rekomendasi->delete();
        return back()->with('success', 'Rekomendasi berhasil dihapus!');
    }
}