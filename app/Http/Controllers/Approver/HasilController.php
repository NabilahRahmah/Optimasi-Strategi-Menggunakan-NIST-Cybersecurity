<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Hasil;
use App\Models\Rekomendasi;
use Illuminate\Support\Facades\DB;

class HasilController extends Controller
{
    public function index()
    {
        $approverId = auth()->user()->user_id;
        $frameworkIds = \App\Models\FrameworkAssignment::where('user_id', $approverId)->pluck('framework_id');

        $assessments = Assessment::with('user')
            ->where('status', 'disetujui')
            ->whereIn('framework_id', $frameworkIds)
            ->latest()
            ->get();

        return view('approver.hasil.index', compact('assessments'));
    }

    public function show(Assessment $assessment)
    {
        abort_if(!auth()->user()->isAssignedTo($assessment->framework_id), 403, 'Akses ditolak.');

        $hasils = Hasil::with('domain')
            ->where('assessment_id', $assessment->assessment_id)
            ->get();

        $rekomendasis = Rekomendasi::with('domain')
            ->where('assessment_id', $assessment->assessment_id)
            ->orderByRaw("FIELD(prioritas, 'Tinggi', 'Sedang', 'Rendah')")
            ->orderBy('sumber')
            ->get();

        $nilai_total = round($hasils->avg('nilai_kematangan'), 2);

        $rataRataPerKategori = DB::table('assessment_jawabans')
            ->join('pertanyaans', 'assessment_jawabans.pertanyaan_id', '=', 'pertanyaans.pertanyaan_id')
            ->join('kategoris', 'pertanyaans.kategori_id', '=', 'kategoris.kategori_id')
            ->join('domains', 'kategoris.domain_id', '=', 'domains.domain_id')
            ->where('assessment_jawabans.assessment_id', $assessment->assessment_id)
            ->whereNotNull('assessment_jawabans.indeks_nilai')
            ->select(
                'domains.kode_domain',
                'domains.nama_domain',
                'kategoris.kode_kategori',
                'kategoris.nama_kategori',
                DB::raw('ROUND(AVG(assessment_jawabans.indeks_nilai), 2) as rata_rata_kategori')
            )
            ->groupBy('domains.domain_id', 'domains.kode_domain', 'domains.nama_domain', 'kategoris.kategori_id', 'kategoris.kode_kategori', 'kategoris.nama_kategori')
            ->orderBy('domains.domain_id')
            ->orderBy('kategoris.kode_kategori')
            ->get();

        $skorPerDomain = $hasils->mapWithKeys(fn($h) => [
            $h->domain->kode_domain => $h->nilai_kematangan
        ])->toArray();

        return view('approver.hasil.show', compact(
            'assessment',
            'hasils',
            'rekomendasis',
            'nilai_total',
            'rataRataPerKategori',
            'skorPerDomain'
        ));
    }
}