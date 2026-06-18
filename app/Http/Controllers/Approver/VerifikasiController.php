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
    public function __construct(
        private SkorService $skor
    ) {
    }

    /**
     * Daftar semua assessment yang sudah disubmit & menunggu verifikasi.
     */
    public function index()
    {
        $assessments = Assessment::with('user')
            ->whereIn('status', ['submitted', 'in_review'])
            ->latest()
            ->get();

        return view('approver.verifikasi.index', compact('assessments'));
    }

    /**
     * Detail assessment — tampilkan semua jawaban per pertanyaan.
     */
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
            ->map(
                fn($byDomain) =>
                $byDomain->groupBy(fn($j) => $j->pertanyaan->kategori->nama_kategori)
            );

        return view('approver.verifikasi.show', compact('assessment', 'grouped'));
    }

    public function approved(Assessment $assessment)
    {
        if ($assessment->status !== 'approved') {
            return redirect()->route('approver.verifikasi.index')
                ->with('error', 'Akses ditolak. Assessment ini belum selesai diverifikasi.');
        }
        $hasil = \App\Models\Hasil::where('assessment_id', $assessment->assessment_id)->first();
        $nilai_total = $hasil ? $hasil->nilai_kematangan : 0;
        return view('approver.verifikasi.approved', compact('assessment', 'nilai_total'));
    }


    public function verifikasiItem(Request $request, AssessmentJawaban $jawaban)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'komentar' => 'nullable|string|max:500',
        ]);

        $jawaban->update([
            'status_verifikasi' => $request->status,
            'komentar_approver' => $request->komentar,
        ]);

        $assessment = $jawaban->assessment;
        $totalJawaban = $assessment->jawabans()->count();
        $sudahVerif = $assessment->jawabans()
            ->whereIn('status_verifikasi', ['disetujui', 'ditolak'])
            ->count();

        if ($totalJawaban > 0 && $sudahVerif === $totalJawaban) {
            try {
                $this->skor->calculate($assessment->assessment_id);
                $assessment->update(['status' => 'approved']);
            } catch (\Exception $e) {
                Log::error('[Verifikasi] Gagal hitung skor', [
                    'assessment_id' => $assessment->assessment_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return back()->with(
            'success',
            'Jawaban berhasil di-' . $request->status . '!'
        );
    }

    public function saveJawaban(Request $request, $assessment_id)
    {
        $assessment = Assessment::findOrFail($assessment_id);

        $request->validate([
            'pertanyaan_id' => 'required|exists:pertanyaans,pertanyaan_id',
            'indeks_nilai' => 'nullable|integer|min:0|max:5',
            'komentar_approver' => 'nullable|string|max:1000',
            'file_bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = [];

        if ($request->has('indeks_nilai')) {
            $data['indeks_nilai'] = $request->indeks_nilai;
        }

        if ($request->has('komentar_approver')) {
            $data['komentar_approver'] = $request->komentar_approver;
        }

        if ($request->hasFile('file_bukti') && $request->file('file_bukti')->isValid()) {
            $file = $request->file('file_bukti');

            $existing = AssessmentJawaban::where('assessment_id', $assessment->assessment_id)
                ->where('pertanyaan_id', $request->pertanyaan_id)
                ->first();
            if ($existing?->file_bukti) {
                \Storage::disk('local')->delete($existing->file_bukti);
            }

            $data['file_bukti'] = $file->store('bukti_audit', 'local');
            $data['nama_file_asli'] = $file->getClientOriginalName();
            $data['ukuran_file'] = $file->getSize();
        }

        if (empty($data)) {
            return response()->json(['success' => true, 'message' => 'Tidak ada perubahan']);
        }

        $jawaban = AssessmentJawaban::firstOrCreate([
            'assessment_id' => $assessment->assessment_id,
            'pertanyaan_id' => $request->pertanyaan_id,
        ]);

        if (in_array($jawaban->status_verifikasi, [null, 'rejected'])) {
            $data['status_verifikasi'] = 'pending';
        }

        $jawaban->update($data);

        return response()->json(['success' => true]);
    }

    public function finalisasi(Assessment $assessment)
    {
        try {
            $result = $this->skor->calculate($assessment->assessment_id);
            $assessment->update(['status' => 'approved']);

            return response()->json([
                'success' => true,
                'nilai_total' => $result['nilai_total'],
                'level' => $result['level']['label'],
                'radar' => $result['radar'],
                'gap' => $result['gap'],
            ]);

        } catch (\Exception $e) {
            Log::error('[Verifikasi] Gagal finalisasi', [
                'assessment_id' => $assessment->assessment_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung skor. ' . $e->getMessage(),
            ], 500);
        }
    }
}