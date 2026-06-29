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
    ) {}

    public function index(Request $request)
    {
        $approverId = auth()->user()->user_id;

        $frameworkIds = \App\Models\FrameworkAssignment::where('user_id', $approverId)
            ->pluck('framework_id');

        $tab = $request->get('tab', 'antrian');

        $statuses = match($tab) {
            'ditolak'   => ['ditolak'],
            'disetujui' => ['disetujui'],
            default     => ['submitted', 'in_review'],
        };

        $assessments = Assessment::with(['user', 'jawabans'])
            ->whereIn('status', $statuses)
            ->whereIn('framework_id', $frameworkIds)
            ->latest()
            ->get();

        return view('approver.verifikasi.index', compact('assessments', 'tab'));
    }

    public function show(Assessment $assessment)
    {
        abort_if(!auth()->user()->isAssignedTo($assessment->framework_id), 403, 'Akses ditolak.');

        $assessment->load([
            'user',
            'jawabans.pertanyaan.kategori.domain',
        ]);

        if ($assessment->status === 'submitted') {
            $assessment->update(['status' => 'in_review']);
        }

        $totalJawaban = $assessment->jawabans->count();

        // ✅ sudahVerif = yang sudah punya keputusan (bukan pending/null)
        $sudahVerif = $assessment->jawabans
            ->whereIn('status_verifikasi', ['disetujui', 'ditolak'])
            ->count();

        // ✅ masihPending = yang belum ada keputusan sama sekali
        $masihPending = $assessment->jawabans
            ->filter(fn($j) => is_null($j->status_verifikasi) || $j->status_verifikasi === 'pending')
            ->count();

        $adaDitolak = $assessment->jawabans
            ->where('status_verifikasi', 'ditolak')
            ->count();

        // ✅ Selesai = tidak ada pending, bukan harus sama dengan total
        // Ini cover skenario resubmit: jawaban yang tidak diedit tetap 'disetujui'
        // dan tidak perlu diverif ulang
        $semuaSelesai = $masihPending === 0 && $totalJawaban > 0;

        $grouped = $assessment->jawabans
            ->filter(fn($j) => $j->pertanyaan?->kategori?->domain)
            ->groupBy(fn($j) => $j->pertanyaan->kategori->domain->nama_domain)
            ->map(
                fn($byDomain) =>
                $byDomain->groupBy(fn($j) => $j->pertanyaan->kategori->nama_kategori)
            );

        return view('approver.verifikasi.show', compact(
            'assessment',
            'grouped',
            'semuaSelesai',
            'totalJawaban',
            'sudahVerif',
            'masihPending',
            'adaDitolak'
        ));
    }

    public function disetujui(Assessment $assessment)
    {
        abort_if(!auth()->user()->isAssignedTo($assessment->framework_id), 403, 'Akses ditolak.');

        if ($assessment->status !== 'disetujui') {
            return redirect()->route('approver.verifikasi.index')
                ->with('error', 'Akses ditolak. Assessment ini belum selesai diverifikasi.');
        }
        $hasil       = \App\Models\Hasil::where('assessment_id', $assessment->assessment_id)->first();
        $nilai_total = $hasil ? $hasil->nilai_kematangan : 0;
        return view('approver.verifikasi.disetujui', compact('assessment', 'nilai_total'));
    }

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

        // ✅ FIX UTAMA: cek pending, bukan count sudah diverif vs total
        // Skenario resubmit: jawaban tidak diedit = tetap 'disetujui' (tidak perlu verif ulang)
        // Hanya yang user edit = reset ke 'pending' = harus diverif approver
        $masihPending = $assessment->jawabans()
            ->where(function ($q) {
                $q->whereNull('status_verifikasi')
                  ->orWhere('status_verifikasi', 'pending');
            })->count();

        $adaDitolak = $assessment->jawabans()
            ->where('status_verifikasi', 'ditolak')
            ->count();

        $semuaSelesai = $masihPending === 0 && $totalJawaban > 0;

        // Status assessment TIDAK berubah di sini.
        // Tetap in_review sampai approver klik Finalisasi manual.

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'       => true,
                'message'       => 'Jawaban berhasil di-' . $request->status . '!',
                'semua_selesai' => $semuaSelesai,
                'masih_pending' => $masihPending,
                'ada_ditolak'   => $adaDitolak,
            ]);
        }

        return back()->with('success', 'Jawaban berhasil di-' . $request->status . '!');
    }

    /**
     * Dipanggil MANUAL setelah semua jawaban diverifikasi (tidak ada pending).
     * Ada yang ditolak → assessment = ditolak, user revisi jawaban itu.
     * Semua disetujui → hitung skor → assessment = disetujui.
     */
    public function finalisasi(Assessment $assessment)
    {
        abort_if(!auth()->user()->isAssignedTo($assessment->framework_id), 403, 'Akses ditolak.');

        // ✅ Guard pakai masihPending, bukan count vs total
        $masihPending = $assessment->jawabans()
            ->where(function ($q) {
                $q->whereNull('status_verifikasi')
                  ->orWhere('status_verifikasi', 'pending');
            })->count();

        if ($masihPending > 0) {
            return response()->json([
                'success' => false,
                'message' => "Masih ada {$masihPending} jawaban yang belum diverifikasi.",
            ], 422);
        }

        $adaDitolak = $assessment->jawabans()
            ->where('status_verifikasi', 'ditolak')
            ->exists();

        try {
            if ($adaDitolak) {
                $assessment->update(['status' => 'ditolak']);

                return response()->json([
                    'success'  => true,
                    'hasil'    => 'ditolak',
                    'message'  => 'Assessment dikembalikan ke user untuk revisi.',
                    'redirect' => route('approver.verifikasi.index'),
                ]);
            } else {
                $result = $this->skor->calculate($assessment->assessment_id);
                $assessment->update(['status' => 'disetujui']);

                return response()->json([
                    'success'     => true,
                    'hasil'       => 'disetujui',
                    'nilai_total' => $result['nilai_total'],
                    'level'       => $result['level']['label'],
                    'radar'       => $result['radar'],
                    'gap'         => $result['gap'],
                    'redirect'    => route('approver.verifikasi.index'),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('[Verifikasi] Gagal finalisasi', [
                'assessment_id' => $assessment->assessment_id,
                'error'         => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses finalisasi: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function saveJawaban(Request $request, $assessment_id)
    {
        $assessment = Assessment::findOrFail($assessment_id);

        $request->validate([
            'pertanyaan_id'     => 'required|exists:pertanyaans,pertanyaan_id',
            'indeks_nilai'      => 'nullable|integer|min:0|max:5',
            'komentar_approver' => 'nullable|string|max:1000',
            'file_bukti.*'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_bukti'        => 'nullable',
        ]);

        $jawaban = AssessmentJawaban::firstOrCreate([
            'assessment_id' => $assessment->assessment_id,
            'pertanyaan_id' => $request->pertanyaan_id,
        ]);

        $data = [];

        if ($request->has('indeks_nilai')) {
            $data['indeks_nilai'] = $request->indeks_nilai;
        }
        if ($request->has('komentar_approver')) {
            $data['komentar_approver'] = $request->komentar_approver;
        }

        if ($request->hasFile('file_bukti')) {
            $uploadedFiles = $request->file('file_bukti');
            $uploadedFiles = is_array($uploadedFiles) ? $uploadedFiles : [$uploadedFiles];

            $pathList   = $jawaban->file_bukti     ?? [];
            $namaList   = $jawaban->nama_file_asli  ?? [];
            $ukuranList = $jawaban->ukuran_file     ?? [];

            foreach ($uploadedFiles as $file) {
                if ($file && $file->isValid()) {
                    $pathList[]   = $file->store('bukti_audit', 'public');
                    $namaList[]   = $file->getClientOriginalName();
                    $ukuranList[] = $file->getSize();
                }
            }

            $data['file_bukti']     = $pathList;
            $data['nama_file_asli'] = $namaList;
            $data['ukuran_file']    = $ukuranList;
        }

        if (empty($data)) {
            return response()->json(['success' => true, 'message' => 'Tidak ada perubahan']);
        }

        if (in_array($jawaban->status_verifikasi, [null, 'rejected'])) {
            $data['status_verifikasi'] = 'pending';
        }

        $jawaban->update($data);

        return response()->json(['success' => true]);
    }

    public function previewFile($jawaban_id, $index = 0)
    {
        $jawaban = AssessmentJawaban::findOrFail($jawaban_id);

        $paths = $jawaban->file_bukti     ?? [];
        $namas = $jawaban->nama_file_asli  ?? [];

        if (empty($paths) || !isset($paths[$index])) {
            abort(404, 'File tidak ditemukan.');
        }

        $fullPath = storage_path('app/' . $paths[$index]);

        if (!file_exists($fullPath)) {
            abort(404, 'File tidak ditemukan di storage.');
        }

        $namaFile = $namas[$index] ?? basename($paths[$index]);
        $ext      = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
        $mimeMap  = [
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        return response()->file($fullPath, [
            'Content-Type'        => $mimeMap[$ext] ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . $namaFile . '"',
        ]);
    }
}