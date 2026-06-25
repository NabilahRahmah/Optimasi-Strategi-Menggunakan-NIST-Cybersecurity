<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentJawaban;
use App\Services\SkorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifikasiController extends Controller
{
    public function __construct(private SkorService $skor) {}

    // Antrian: submitted + in_review
    public function index()
    {
        $assessments = Assessment::with('user')
            ->whereIn('status', ['submitted', 'in_review'])
            ->latest()
            ->get();

        return view('approver.verifikasi.index', [
            'assessments' => $assessments,
            'pageTitle'   => 'Antrian Verifikasi',
            'activeTab'   => 'antrian',
        ]);
    }

    // List yang sudah disetujui
    public function disetujui()
    {
        $assessments = Assessment::with('user')
            ->where('status', 'disetujui')
            ->latest()
            ->get();

        return view('approver.verifikasi.index', [
            'assessments' => $assessments,
            'pageTitle'   => 'Assessment Selesai Diverifikasi',
            'activeTab'   => 'disetujui',
        ]);
    }

    // Detail assessment — tampilkan semua jawaban
    public function show(Assessment $assessment)
    {
        $assessment->load([
            'user',
            'jawabans.pertanyaan.kategori.domain',
        ]);

        // Update status jadi in_review kalau masih submitted
        if ($assessment->status === 'submitted') {
            $assessment->update(['status' => 'in_review']);
        }

        // Kelompokkan jawaban per domain → kategori
        $grouped = $assessment->jawabans
            ->filter(fn($j) => $j->pertanyaan?->kategori?->domain)
            ->groupBy(fn($j) => $j->pertanyaan->kategori->domain->nama_domain)
            ->map(fn($byDomain) =>
                $byDomain->groupBy(fn($j) => $j->pertanyaan->kategori->nama_kategori)
            );

        return view('approver.verifikasi.show', compact('assessment', 'grouped'));
    }

    // Verifikasi satu jawaban
    public function verifikasiItem(Request $request, AssessmentJawaban $jawaban)
    {
        $request->validate([
            'status'   => 'required|in:disetujui,ditolak',
            'komentar' => 'nullable|string|max:500',
        ]);

        $jawaban->update([
            'status_verifikasi' => $request->status,
            'komentar_approver' => $request->komentar,
        ]);

        $assessment   = $jawaban->assessment;
        $totalJawaban = $assessment->jawabans()->count();
        $sudahVerif   = $assessment->jawabans()
            ->whereIn('status_verifikasi', ['disetujui', 'ditolak'])
            ->count();

        // Kalau semua jawaban sudah diverifikasi → hitung skor & set disetujui
        if ($totalJawaban > 0 && $sudahVerif === $totalJawaban) {
            try {
                $this->skor->calculate($assessment->assessment_id);
                $assessment->update(['status' => 'disetujui']);
            } catch (\Exception $e) {
                Log::error('[Verifikasi] Gagal hitung skor', [
                    'assessment_id' => $assessment->assessment_id,
                    'error'         => $e->getMessage(),
                ]);
            }
        }

        return back()->with('success', 'Jawaban berhasil di-' . $request->status . '!');
    }

    // Finalisasi manual
    public function finalisasi(Assessment $assessment)
    {
        try {
            $result = $this->skor->calculate($assessment->assessment_id);
            $assessment->update(['status' => 'disetujui']);

            return response()->json([
                'success'     => true,
                'nilai_total' => $result['nilai_total'],
                'level'       => $result['level']['label'],
            ]);
        } catch (\Exception $e) {
            Log::error('[Verifikasi] Gagal finalisasi', [
                'assessment_id' => $assessment->assessment_id,
                'error'         => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}