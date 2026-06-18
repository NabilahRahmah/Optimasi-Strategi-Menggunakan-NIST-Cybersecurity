<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Domain;
use App\Models\Hasil;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SkorService
{
    private const SKALA_MAX = 5;
    private const TARGET_NILAI = 5.0;

    private const LEVEL = [
        ['min' => 0.0, 'max' => 0.9, 'label' => 'Tier 0 – Tidak Ada', 'warna' => '#718096'],
        ['min' => 1.0, 'max' => 1.9, 'label' => 'Tier 1 – Partial', 'warna' => '#E53E3E'],
        ['min' => 2.0, 'max' => 2.9, 'label' => 'Tier 2 – Risk Informed', 'warna' => '#DD6B20'],
        ['min' => 3.0, 'max' => 3.9, 'label' => 'Tier 3 – Repeatable', 'warna' => '#D69E2E'],
        ['min' => 4.0, 'max' => 4.9, 'label' => 'Tier 4 – Adaptive', 'warna' => '#38A169'],
        ['min' => 5.0, 'max' => 5.0, 'label' => 'Tier 5 – Optimal', 'warna' => '#2B6CB0'],
    ];

    public function calculate(int $assessmentId): array
    {
        $assessment = Assessment::with([
            'jawabans.pertanyaan.kategori.domain', 
        ])->findOrFail($assessmentId);

        if (!in_array($assessment->status, ['submitted', 'in_review', 'approved'])) {
            throw new \InvalidArgumentException(
                "Assessment ID {$assessmentId} tidak bisa dihitung. Status: {$assessment->status}"
            );
        }

        $grouped = $this->groupByDomain($assessment->jawabans);
        $skorPerDomain = $this->hitungSkor($grouped);
        $nilaiTotal = collect($skorPerDomain)->avg('score');
        $persentase = ($nilaiTotal / self::SKALA_MAX) * 100;
        $level = $this->cariLevel($nilaiTotal);

        $hasil = \DB::transaction(function () use ($assessmentId, $skorPerDomain, $nilaiTotal, $persentase, $level) {
            return Hasil::updateOrCreate(
                ['assessment_id' => $assessmentId],
                [
                    'skor_per_domain' => $skorPerDomain,
                    'nilai_total' => round($nilaiTotal, 2),
                    'persentase_total' => round($persentase, 2),
                    'level_kematangan' => $level['label'],
                    'tgl_hasil' => now(),
                ]
            );
        });

        return [
            'hasil' => $hasil,
            'skor_per_domain' => $skorPerDomain,
            'nilai_total' => round($nilaiTotal, 2),
            'persentase' => round($persentase, 2),
            'level' => $level,
            'radar' => $this->radarData($skorPerDomain),
            'gap' => $this->gapAnalysis($skorPerDomain),
            'rekomendasi' => $this->rekomendasiOtomatis($assessment->jawabans),
        ];
    }

    public function getGap(int $assessmentId): array
    {
        $hasil = Hasil::where('assessment_id', $assessmentId)->firstOrFail();
        return $this->gapAnalysis($hasil->skor_per_domain);
    }

    public function getRadar(int $assessmentId): array
    {
        $hasil = Hasil::where('assessment_id', $assessmentId)->firstOrFail();
        return $this->radarData($hasil->skor_per_domain);
    }

    // ── Private helpers ──────────────────────────────────────────

    private function groupByDomain(Collection $jawabans): Collection
    {
        return $jawabans->groupBy(
            fn($j) => $j->pertanyaan?->kategori?->domain?->kode ?? 'UNKNOWN'
        );
    }

    private function hitungSkor(Collection $grouped): array
    {
        $domains = Domain::with('framework')->get()->keyBy('kode');
        $result = [];

        foreach ($domains as $kode => $domain) {
            $jawabans = $grouped->get($kode, collect());
            $disetujui = $jawabans->filter(fn($j) => $j->status_verifikasi === 'disetujui');
            $score = $disetujui->count() === 0
                ? 0.0
                : $disetujui->avg('indeks_nilai');

            $targetDomain = $domain->target_nilai ?? self::TARGET_NILAI;

            $result[$kode] = [
                'kode' => $kode,
                'nama' => $domain->nama_domain,
                'score' => round($score, 2),
                'percentage' => round(($score / self::SKALA_MAX) * 100, 1),
                'gap' => round($targetDomain - $score, 2),
                'target' => $targetDomain,
                'total' => $jawabans->count(),
                'disetujui' => $disetujui->count(),
                'ditolak' => $jawabans->where('status_verifikasi', 'ditolak')->count(),
                'pending' => $jawabans->where('status_verifikasi', 'pending')->count(),
            ];
        }

        return $result;
    }

    private function cariLevel(float $score): array
    {
        foreach (self::LEVEL as $level) {
            if ($score >= $level['min'] && $score <= $level['max']) {
                return $level;
            }
        }
        return self::LEVEL[0];
    }

    private function gapAnalysis(array $skorPerDomain): array
    {
        return collect($skorPerDomain)
            ->sortByDesc('gap')
            ->map(fn($item) => [
                ...$item,
                'prioritas' => $this->prioritas($item['gap']),
                'level_label' => $this->cariLevel($item['score'])['label'],
                'level_warna' => $this->cariLevel($item['score'])['warna'],
            ])
            ->values()
            ->toArray();
    }

    private function prioritas(float $gap): string
    {
        return match (true) {
            $gap >= 3.0 => 'Tinggi',
            $gap >= 1.5 => 'Sedang',
            $gap >= 0.5 => 'Rendah',
            default => 'Tercapai',
        };
    }

    private function radarData(array $skorPerDomain): array
    {
        $order = ['GV', 'ID', 'PR', 'DE', 'RS', 'RC'];
        $labels = [];
        $scores = [];
        $targets = [];

        foreach ($order as $kode) {
            if (isset($skorPerDomain[$kode])) {
                $labels[] = $skorPerDomain[$kode]['nama'];
                $scores[] = $skorPerDomain[$kode]['score'];
                $targets[] = $skorPerDomain[$kode]['target'];
            }
        }

        return compact('labels', 'scores', 'targets');
    }

    private function rekomendasiOtomatis(Collection $jawabans): array
    {
        $result = [];

        foreach ($jawabans as $j) {
            if (is_null($j->indeks_nilai) || $j->indeks_nilai >= 5)
                continue;
            if ($j->status_verifikasi === 'ditolak')
                continue;

            // ✅ FIX: akses kategori lewat pertanyaan, bukan langsung
            $pertanyaan = $j->pertanyaan;
            if (!$pertanyaan)
                continue;

            $kategori = $pertanyaan->kategori;
            if (!$kategori)
                continue;

            $domain = $kategori->domain;
            if (!$domain)
                continue;

            $target = $j->indeks_nilai + 1;
            $teks = $kategori->{"indeks_{$target}"};
            if (!$teks)
                continue;

            $result[] = [
                'kode_kategori' => $kategori->kode_kategori,
                'nama_kategori' => $kategori->nama_kategori,
                'domain_kode' => $domain->kode,
                'domain_nama' => $domain->nama_domain,
                'indeks_saat' => $j->indeks_nilai,
                'indeks_target' => $target,
                'rekomendasi' => $teks,
                'prioritas' => $this->prioritas(self::TARGET_NILAI - $j->indeks_nilai),
            ];
        }

        return collect($result)
            ->sortBy([['prioritas', 'asc'], ['domain_kode', 'asc']])
            ->values()
            ->toArray();
    }
}