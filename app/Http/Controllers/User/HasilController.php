<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentJawaban;
use App\Models\Domain;
use App\Models\Hasil;
use App\Models\Rekomendasi;
use App\Models\Pertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class HasilController extends Controller
{
    public function index()
    {
        // ✅ FIX: Ganti query pakai framework_id biar sesuai konsep kerja tim
        $frameworkIds = auth()->user()->assignedFrameworks()->pluck('frameworks.framework_id')->toArray();
        
        $assessments = Assessment::whereIn('framework_id', $frameworkIds)
            ->whereIn('status', ['submitted', 'in_review', 'disetujui', 'ditolak'])
            ->latest()
            ->get();

        return view('user.hasil.index', compact('assessments'));
    }
    
    public function show($assessment_id)
    {
        // ✅ FIX: Hapus ->with('user') karena kolomnya udah dihilangin
        $assessment = Assessment::findOrFail($assessment_id);
        
        // Guard: Pastikan dia di-assign ke framework assessment ini
        abort_if(!auth()->user()->isAssignedTo($assessment->framework_id), 403, 'Akses ditolak.');

        $this->hitungDanSimpan($assessment_id, $assessment->framework_id);

        $hasils = Hasil::with('domain')
            ->where('assessment_id', $assessment_id)
            ->get();

        $rekomendasis = Rekomendasi::with('domain')
            ->where('assessment_id', $assessment_id)
            ->orderByRaw("FIELD(prioritas, 'Tinggi', 'Sedang', 'Rendah')")
            ->get();

        $nilai_total = round($hasils->avg('nilai_kematangan'), 2);

        $chart_labels = $hasils->map(fn($h) => $h->domain->nama_domain);
        $chart_values = $hasils->map(fn($h) => $h->nilai_kematangan);

        $rataRataPerKategori = DB::table('assessment_jawabans')
            ->join('pertanyaans', 'assessment_jawabans.pertanyaan_id', '=', 'pertanyaans.pertanyaan_id')
            ->join('kategoris', 'pertanyaans.kategori_id', '=', 'kategoris.kategori_id')
            ->join('domains', 'kategoris.domain_id', '=', 'domains.domain_id')
            ->where('assessment_jawabans.assessment_id', $assessment_id)
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

        return view('user.hasil.show', compact(
            'assessment',
            'hasils',
            'rekomendasis',
            'nilai_total',
            'chart_labels',
            'chart_values',
            'rataRataPerKategori',
            'skorPerDomain'
        ));
    }

    public function cetakPDF(Request $request, $assessment_id)
    {
        // ✅ FIX: Hapus ->with('user')
        $assessment = Assessment::findOrFail($assessment_id);
        
        abort_if(!auth()->user()->isAssignedTo($assessment->framework_id), 403, 'Akses ditolak.');

        $hasils = Hasil::with('domain')
            ->where('assessment_id', $assessment_id)
            ->get();

        $rekomendasis = Rekomendasi::with('domain')
            ->where('assessment_id', $assessment_id)
            ->orderByRaw("FIELD(prioritas, 'Tinggi', 'Sedang', 'Rendah')")
            ->get();

        $nilai_total = round($hasils->avg('nilai_kematangan'), 2);

        $rataRataPerKategori = DB::table('assessment_jawabans')
            ->join('pertanyaans', 'assessment_jawabans.pertanyaan_id', '=', 'pertanyaans.pertanyaan_id')
            ->join('kategoris', 'pertanyaans.kategori_id', '=', 'kategoris.kategori_id')
            ->join('domains', 'kategoris.domain_id', '=', 'domains.domain_id')
            ->where('assessment_jawabans.assessment_id', $assessment_id)
            ->whereNotNull('assessment_jawabans.indeks_nilai')
            ->select(
                'domains.kode_domain',
                'domains.nama_domain',
                'kategoris.kode_kategori',
                'kategoris.nama_kategori',
                DB::raw('ROUND(AVG(assessment_jawabans.indeks_nilai), 2) as rata_rata_kategori')
            )
            ->groupBy(
                'domains.domain_id',
                'domains.kode_domain',
                'domains.nama_domain',
                'kategoris.kategori_id',
                'kategoris.kode_kategori',
                'kategoris.nama_kategori'
            )
            ->orderBy('domains.domain_id')
            ->orderBy('kategoris.kode_kategori')
            ->get();

        $skorPerDomain = $hasils->mapWithKeys(fn($h) => [
            $h->domain->kode_domain => $h->nilai_kematangan
        ])->toArray();

        $radarImage = $request->input('radar_image');

        $pdf = Pdf::loadView('user.hasil.pdf', compact(
            'assessment',
            'hasils',
            'rekomendasis',
            'nilai_total',
            'rataRataPerKategori',
            'skorPerDomain',
            'radarImage'
        ))->setPaper('a4', 'portrait');

        $filename = 'Laporan-Assessment-' .
            str($assessment->judul_assessment)->slug() . '-' .
            now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    private function hitungDanSimpan($assessment_id, $framework_id)
    {
        $domains = Domain::with('kategoris.pertanyaans')
            ->where('framework_id', $framework_id)
            ->get();

        foreach ($domains as $domain) {
            $pertanyaan_ids = $domain->kategoris
                ->flatMap(fn($k) => $k->pertanyaans->pluck('pertanyaan_id'));

            $rata_rata = AssessmentJawaban::where('assessment_id', $assessment_id)
                ->whereIn('pertanyaan_id', $pertanyaan_ids)
                ->whereNotNull('indeks_nilai')
                ->avg('indeks_nilai') ?? 0;

            $targetNilai = 3;
            $gap = round($targetNilai - $rata_rata, 2);
            $level = $this->tentukanLevel($rata_rata);

            Hasil::updateOrCreate(
                ['assessment_id' => $assessment_id, 'domain_id' => $domain->domain_id],
                [
                    'nilai_kematangan' => round($rata_rata, 2),
                    'target_nilai' => $targetNilai,
                    'gap' => $gap,
                    'level_kematangan' => $level,
                ]
            );
        }

        $this->generateRekomendasi($assessment_id);
    }

    private function generateRekomendasi($assessment_id)
    {
        Rekomendasi::where('assessment_id', $assessment_id)
            ->where('sumber', 'otomatis')
            ->delete();

        $jawabans = AssessmentJawaban::with('pertanyaan.kategori.domain')
            ->where('assessment_id', $assessment_id)
            ->whereNotNull('indeks_nilai')
            ->where('indeks_nilai', '<', 5)
            ->get();

        foreach ($jawabans as $jawaban) {
            $pertanyaan = $jawaban->pertanyaan;
            if (!$pertanyaan)
                continue;

            $indeksTarget = $jawaban->indeks_nilai + 1;
            $teksRekomendasi = $pertanyaan->{"indeks_{$indeksTarget}"};

            if (!$teksRekomendasi)
                continue;

            $domain = $pertanyaan->kategori?->domain;
            if (!$domain)
                continue;

            $gap = 5 - $jawaban->indeks_nilai;

            Rekomendasi::create([
                'assessment_id' => $assessment_id,
                'domain_id' => $domain->domain_id,
                'deskripsi_perbaikan' => "[{$pertanyaan->kode_pertanyaan}] {$teksRekomendasi}",
                'prioritas' => $gap >= 3 ? 'Tinggi' : ($gap >= 1.5 ? 'Sedang' : 'Rendah'),
                'sumber' => 'otomatis',
            ]);
        }
    }
    private function tentukanLevel(float $nilai): string
    {
        return match (true) {
            $nilai >= 3.75 => 'Tier 4 – Adaptive',
            $nilai >= 2.50 => 'Tier 3 – Repeatable',
            $nilai >= 1.25 => 'Tier 2 – Risk Informed',
            $nilai > 0.0 => 'Tier 1 – Partial',
            default => 'Belum Ada Penerapan',
        };
    }
}