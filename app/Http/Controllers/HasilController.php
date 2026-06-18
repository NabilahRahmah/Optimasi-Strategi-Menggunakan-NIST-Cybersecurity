<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentJawaban;
use App\Models\Domain;
use App\Models\Hasil;
use App\Models\Rekomendasi;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class HasilController extends Controller
{
    public function show($assessment_id)
    {
        $assessment = Assessment::findOrFail($assessment_id);

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

        // fix: pakai primary key yang benar (kategori_id, domain_id bukan .id)
        $rataRataPerKategori = DB::table('assessment_jawabans')
            ->join('pertanyaans', 'assessment_jawabans.pertanyaan_id', '=', 'pertanyaans.pertanyaan_id')
            ->join('kategoris', 'pertanyaans.kategori_id', '=', 'kategoris.kategori_id')
            ->join('domains', 'kategoris.domain_id', '=', 'domains.domain_id')
            ->where('assessment_jawabans.assessment_id', $assessment_id)
            ->whereNotNull('assessment_jawabans.indeks_nilai')
            ->select(
                'domains.nama_domain',
                'kategoris.nama_kategori',
                DB::raw('ROUND(AVG(assessment_jawabans.indeks_nilai), 2) as rata_rata_kategori')
            )
            ->groupBy('domains.nama_domain', 'kategoris.nama_kategori')
            ->orderBy('domains.domain_id')
            ->get();

        return view('hasil.show', compact(
            'assessment',
            'hasils',
            'rekomendasis',
            'nilai_total',
            'chart_labels',
            'chart_values',
            'rataRataPerKategori'
        ));
    }

    private function hitungDanSimpan($assessment_id, $framework_id)
    {
        $domains = Domain::with('kategoris')
            ->where('framework_id', $framework_id)
            ->get();

        foreach ($domains as $domain) {
            $kategori_ids = $domain->kategoris->pluck('kategori_id');

            $rata_rata = AssessmentJawaban::where('assessment_id', $assessment_id)
                ->whereHas('pertanyaan', function ($query) use ($kategori_ids) {
                    $query->whereIn('kategori_id', $kategori_ids);
                })
                ->whereNotNull('indeks_nilai')
                ->avg('indeks_nilai') ?? 0;

            $level = $this->tentukanLevel($rata_rata);

            // fix: hapus target_nilai karena kolom sudah dihapus dari tabel domains
            Hasil::updateOrCreate(
                [
                    'assessment_id' => $assessment_id,
                    'domain_id' => $domain->domain_id,
                ],
                [
                    'nilai_kematangan' => round($rata_rata, 2),
                    'level_kematangan' => $level,
                ]
            );
        }

        $this->generateRekomendasi($assessment_id);
    }

    private function tentukanLevel($nilai): string
    {
        return match (true) {
            $nilai >= 4.5 => 'Level 5 - Inovatif',
            $nilai >= 3.5 => 'Level 4 - Terkelola',
            $nilai >= 2.5 => 'Level 3 - Konsisten',
            $nilai >= 1.5 => 'Level 2 - Awal',
            default => 'Level 1 - Parsial',
        };
    }

    private function generateRekomendasi($assessment_id)
    {
        Rekomendasi::where('assessment_id', $assessment_id)
            ->where('sumber', 'otomatis')
            ->delete();

        $hasils = Hasil::with('domain')
            ->where('assessment_id', $assessment_id)
            ->where('nilai_kematangan', '<', 5) // fix: tidak ada target_nilai, pakai nilai < 5
            ->orderBy('nilai_kematangan', 'asc')
            ->get();

        foreach ($hasils as $hasil) {
            $gap = round(5 - $hasil->nilai_kematangan, 2);

            Rekomendasi::create([
                'assessment_id' => $assessment_id,
                'domain_id' => $hasil->domain_id,
                'deskripsi_perbaikan' => 'Tingkatkan implementasi pada domain '
                    . $hasil->domain->nama_domain
                    . '. Nilai saat ini ' . $hasil->nilai_kematangan
                    . ' dari target 5. Gap: ' . $gap,
                'prioritas' => $gap >= 1.5 ? 'Tinggi' : ($gap >= 0.5 ? 'Sedang' : 'Rendah'),
                'sumber' => 'otomatis',
            ]);
        }
    }

    public function cetakPDF($assessment_id)
    {
        $assessment = Assessment::with('user')->findOrFail($assessment_id);
        $hasils = Hasil::with('domain')->where('assessment_id', $assessment_id)->get();
        $rekomendasis = Rekomendasi::with('domain')->where('assessment_id', $assessment_id)->get();
        $nilai_total = round($hasils->avg('nilai_kematangan'), 2);

        $labels = $hasils->map(fn($h) => "'" . $h->domain->nama_domain . "'")->implode(',');
        $data = $hasils->map(fn($h) => $h->nilai_kematangan)->implode(',');
        $urlGrafik = "https://quickchart.io/chart?c={type:'radar',data:{labels:[{$labels}],datasets:[{label:'Nilai Kematangan',data:[{$data}],backgroundColor:'rgba(0,128,128,0.2)',borderColor:'#008080'}]},options:{scale:{ticks:{min:0,max:5,stepSize:1}}}}";

        $pdf = Pdf::loadView('pdf.laporan', compact('assessment', 'hasils', 'rekomendasis', 'nilai_total', 'urlGrafik'));

        return $pdf->download('Laporan_Assessment_' . $assessment_id . '.pdf');
    }
}