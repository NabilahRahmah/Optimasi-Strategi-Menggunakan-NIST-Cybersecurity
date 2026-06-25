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
    private const TARGET_NILAI = 5.0; // target skor maksimal (skala 1-5), bukan label tier

    private const LEVEL = [
        ['min' => 0.0, 'max' => 0.9, 'label' => 'Tier 0 – Tidak Ada', 'warna' => '#718096'],
        ['min' => 1.0, 'max' => 1.9, 'label' => 'Tier 1 – Partial', 'warna' => '#E53E3E'],
        ['min' => 2.0, 'max' => 2.9, 'label' => 'Tier 2 – Risk Informed', 'warna' => '#DD6B20'],
        ['min' => 3.0, 'max' => 3.9, 'label' => 'Tier 3 – Repeatable', 'warna' => '#D69E2E'],
        ['min' => 4.0, 'max' => 4.9, 'label' => 'Tier 4 – Adaptive', 'warna' => '#38A169'],
        ['min' => 5.0, 'max' => 5.0, 'label' => 'Tier 5 – Optimal', 'warna' => '#2B6CB0'],
    ];

    /**
     * Hitung skor kematangan per domain untuk sebuah assessment, lalu
     * simpan satu baris `hasils` per domain (sesuai struktur tabel).
     */
    public function calculate(int $assessmentId): array
    {
        $assessment = Assessment::with([
            'jawabans.pertanyaan.kategori.domain',
        ])->findOrFail($assessmentId);

        if (!in_array($assessment->status, ['submitted', 'in_review', 'disetujui'])) {
            throw new \InvalidArgumentException(
                "Assessment ID {$assessmentId} tidak bisa dihitung. Status: {$assessment->status}"
            );
        }

        // Domain yang relevan = domain milik framework assessment ini
        $domains = Domain::where('framework_id', $assessment->framework_id)->get();

        $grouped = $this->groupByDomain($assessment->jawabans);
        $skorPerDomain = $this->hitungSkor($domains, $grouped);

        $hasilRows = DB::transaction(function () use ($assessmentId, $skorPerDomain) {
            $rows = [];

            foreach ($skorPerDomain as $domainId => $item) {
                $rows[$domainId] = Hasil::updateOrCreate(
                    [
                        'assessment_id' => $assessmentId,
                        'domain_id'     => $domainId,
                    ],
                    [
                        'nilai_kematangan' => $item['score'],
                        'target_nilai'     => $item['target'],
                        'gap'              => $item['gap'],
                        'level_kematangan' => $this->cariLevel($item['score'])['label'],
                    ]
                );
            }

            return $rows;
        });

        $nilaiTotal = collect($skorPerDomain)->avg('score') ?? 0;
        $persentase = ($nilaiTotal / self::SKALA_MAX) * 100;
        $level = $this->cariLevel($nilaiTotal);

        return [
            'hasil'           => $hasilRows,
            'skor_per_domain' => $skorPerDomain,
            'nilai_total'     => round($nilaiTotal, 2),
            'persentase'      => round($persentase, 2),
            'level'           => $level,
            'radar'           => $this->radarData($skorPerDomain),
            'gap'             => $this->gapAnalysis($skorPerDomain),
            'rekomendasi'     => $this->rekomendasiOtomatis($assessment->jawabans),
        ];
    }

    /**
     * Ambil ulang ringkasan hasil (dari baris-baris hasils yang sudah tersimpan)
     * untuk sebuah assessment yang sudah pernah dihitung.
     */
    public function getRingkasan(int $assessmentId): array
    {
        $hasils = Hasil::with('domain')
            ->where('assessment_id', $assessmentId)
            ->get();

        $skorPerDomain = [];
        foreach ($hasils as $h) {
            $kode = $h->domain?->kode_domain ?? 'UNKNOWN';
            $skorPerDomain[$h->domain_id] = [
                'domain_id' => $h->domain_id,
                'kode'      => $kode,
                'nama'      => $h->domain?->nama_domain,
                'score'     => (float) $h->nilai_kematangan,
                'target'    => (float) $h->target_nilai,
                'gap'       => (float) $h->gap,
            ];
        }

        $nilaiTotal = collect($skorPerDomain)->avg('score') ?? 0;

        return [
            'skor_per_domain' => $skorPerDomain,
            'nilai_total'     => round($nilaiTotal, 2),
            'persentase'      => round(($nilaiTotal / self::SKALA_MAX) * 100, 2),
            'level'           => $this->cariLevel($nilaiTotal),
            'radar'           => $this->radarData($skorPerDomain),
            'gap'             => $this->gapAnalysis($skorPerDomain),
        ];
    }

    public function getGap(int $assessmentId): array
    {
        return $this->getRingkasan($assessmentId)['gap'];
    }

    public function getRadar(int $assessmentId): array
    {
        return $this->getRingkasan($assessmentId)['radar'];
    }

    // ── Private helpers ──────────────────────────────────────────

    /**
     * Kelompokkan jawaban berdasarkan domain_id (bukan kode_domain),
     * supaya konsisten dipakai sebagai array key di hitungSkor().
     */
    private function groupByDomain(Collection $jawabans): Collection
    {
        return $jawabans->groupBy(
            fn($j) => $j->pertanyaan?->kategori?->domain?->domain_id
        )->filter(fn($items, $key) => $key !== null);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Domain>  $domains
     * @param  Collection  $grouped  jawaban dikelompokkan per domain_id
     * @return array<int, array>  key = domain_id
     */
    private function hitungSkor(Collection $domains, Collection $grouped): array
    {
        $result = [];

        foreach ($domains as $domain) {
            $jawabans = $grouped->get($domain->domain_id, collect());
            $disetujui = $jawabans->filter(fn($j) => $j->status_verifikasi === 'disetujui');
            $score = $disetujui->count() === 0
                ? 0.0
                : $disetujui->avg('indeks_nilai');

            $targetDomain = self::TARGET_NILAI;

            $result[$domain->domain_id] = [
                'domain_id'  => $domain->domain_id,
                'kode'       => $domain->kode_domain,
                'nama'       => $domain->nama_domain,
                'score'      => round($score, 2),
                'percentage' => round(($score / self::SKALA_MAX) * 100, 1),
                'gap'        => round($targetDomain - $score, 2),
                'target'     => $targetDomain,
                'total'      => $jawabans->count(),
                'disetujui'  => $disetujui->count(),
                'ditolak'    => $jawabans->where('status_verifikasi', 'ditolak')->count(),
                'pending'    => $jawabans->where('status_verifikasi', 'pending')->count(),
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
                'prioritas'    => $this->prioritas($item['gap']),
                'level_label'  => $this->cariLevel($item['score'])['label'],
                'level_warna'  => $this->cariLevel($item['score'])['warna'],
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
        $byKode = collect($skorPerDomain)->keyBy('kode');

        $labels = [];
        $scores = [];
        $targets = [];

        foreach ($order as $kode) {
            if ($byKode->has($kode)) {
                $item = $byKode->get($kode);
                $labels[] = $item['nama'];
                $scores[] = $item['score'];
                $targets[] = $item['target'];
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
                'kode_kategori'  => $kategori->kode_kategori,
                'nama_kategori'  => $kategori->nama_kategori,
                'domain_kode'    => $domain->kode_domain,
                'domain_nama'    => $domain->nama_domain,
                'indeks_saat'    => $j->indeks_nilai,
                'indeks_target'  => $target,
                'rekomendasi'    => $teks,
                'prioritas'      => $this->prioritas(self::TARGET_NILAI - $j->indeks_nilai),
            ];
        }

        return collect($result)
            ->sortBy([['prioritas', 'asc'], ['domain_kode', 'asc']])
            ->values()
            ->toArray();
    }
}