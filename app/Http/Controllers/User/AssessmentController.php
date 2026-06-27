<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentJawaban;
use App\Models\Domain;
use App\Services\SkorService;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function pilihFramework()
    {
        $frameworks = auth()->user()->assignedFrameworks()
            ->where('is_active', true)
            ->with(['domains.kategoris.pertanyaans'])
            ->get();

        return view('user.assessment.pilih-framework', compact('frameworks'));
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $frameworkId = $request->query('framework_id');
        $assessmentId = $request->query('assessment_id');

        if ($frameworkId && !$user->isAssignedTo($frameworkId)) {
            abort(403, 'Anda tidak memiliki akses ke framework ini.');
        }

        if (!$frameworkId) {
            return redirect()->route('user.assessment.pilihFramework')
                ->with('error', 'Silakan pilih framework terlebih dahulu.');
        }

        // Kalau ada assessment_id spesifik → load itu langsung
        if ($assessmentId) {
            $assessment = Assessment::where('user_id', $user->user_id)
                ->where('assessment_id', $assessmentId)
                ->with('jawabans')
                ->firstOrFail();
        } else {
            // Cari yang sedang berjalan
            $assessment = Assessment::where('user_id', $user->user_id)
                ->where('framework_id', $frameworkId)
                ->whereIn('status', ['draft', 'submitted', 'in_review', 'ditolak'])
                ->with('jawabans')
                ->latest()
                ->first();

            // Kalau tidak ada → buat baru
            if (!$assessment) {
                $assessment = Assessment::create([
                    'user_id' => $user->user_id,
                    'framework_id' => $frameworkId,
                    'judul_assessment' => 'Self Assessment Q' . now()->quarter . ' ' . now()->year,
                    'tgl_pelaksanaan' => now()->toDateString(),
                    'status' => 'draft',
                ]);
            }
        }

        $domains = Domain::where('framework_id', $frameworkId)
            ->with([
                'kategoris.pertanyaans' => function ($q) {
                    $q->orderBy('kode_pertanyaan');
                }
            ])
            ->orderBy('kode_domain')
            ->get();

        $existingJawaban = $assessment->jawabans->keyBy('pertanyaan_id');

        return view('user.assessment.index', compact('domains', 'assessment', 'existingJawaban'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,assessment_id',
            'judul_assessment' => 'required|string|max:255',
            'scores' => 'nullable|array',
            'scores.*' => 'nullable|integer|min:0|max:5',
            'files' => 'nullable|array',
            'files.*.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $assessment = Assessment::where('user_id', auth()->user()->user_id)
            ->where('assessment_id', $request->assessment_id)
            ->whereIn('status', ['draft', 'ditolak'])
            ->firstOrFail();

        $isDraft = $request->action === 'draft';

        $assessment->update([
            'judul_assessment' => $request->judul_assessment,
            'status' => $isDraft ? 'draft' : 'submitted',
        ]);

        if ($request->has('scores')) {
            foreach ($request->scores as $pertanyaan_id => $nilai) {
                $jawaban = AssessmentJawaban::firstOrNew([
                    'assessment_id' => $assessment->assessment_id,
                    'pertanyaan_id' => $pertanyaan_id,
                ]);

                // Reset ke pending kalau sebelumnya sudah diverif (ditolak)
                if ($jawaban->status_verifikasi === 'ditolak') {
                    $jawaban->status_verifikasi = 'pending';
                    $jawaban->komentar_approver = null;
                }

                // ✅ FIX: append file baru ke array yang sudah ada (bukan overwrite)
                $pathList = $jawaban->file_bukti ?? [];
                $namaList = $jawaban->nama_file_asli ?? [];
                $ukuranList = $jawaban->ukuran_file ?? [];

                // files[pertanyaan_id] adalah array file (multiple)
                if ($request->hasFile("files.{$pertanyaan_id}")) {
                    $uploadedFiles = $request->file("files.{$pertanyaan_id}");
                    // Pastikan array meski single file
                    $uploadedFiles = is_array($uploadedFiles) ? $uploadedFiles : [$uploadedFiles];

                    foreach ($uploadedFiles as $file) {
                        if ($file && $file->isValid()) {
                            $pathList[] = $file->store('bukti_audit', 'public');
                            $namaList[] = $file->getClientOriginalName();
                            $ukuranList[] = $file->getSize();
                        }
                    }
                }

                $jawaban->indeks_nilai = $nilai ?: null;
                $jawaban->file_bukti = $pathList;
                $jawaban->nama_file_asli = $namaList;
                $jawaban->ukuran_file = $ukuranList;

                $jawaban->save();
            }
        }

        if (!$isDraft) {
            try {
                $skorService = new SkorService();
                $skorService->calculate($assessment->assessment_id);
            } catch (\Exception $e) {
                \Log::warning('SkorService gagal: ' . $e->getMessage());
            }

            return redirect()->route('user.hasil.index')
                ->with('success', 'Berhasil disubmit! Menunggu verifikasi.');
        }

        return redirect()->back()->with('success', 'Draft berhasil disimpan!');
    }

    public function saveJawaban(Request $request, $assessment_id)
    {
        $assessment = Assessment::where('user_id', auth()->user()->user_id)
            ->where('assessment_id', $assessment_id)
            ->firstOrFail();

        if (in_array($assessment->status, ['in_review', 'disetujui'])) {
            return response()->json([
                'success' => false,
                'message' => 'Assessment sedang dikunci untuk direview atau sudah final.',
            ], 403);
        }

        $request->validate([
            'pertanyaan_id' => 'required|exists:pertanyaans,pertanyaan_id',
            'indeks_nilai' => 'nullable|integer|min:0|max:5',
            'file_bukti.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_bukti' => 'nullable',
        ]);

        $jawaban = AssessmentJawaban::firstOrCreate([
            'assessment_id' => $assessment->assessment_id,
            'pertanyaan_id' => $request->pertanyaan_id,
        ]);

        $data = [];

        if ($request->has('indeks_nilai')) {
            $data['indeks_nilai'] = $request->indeks_nilai;
        }

        if ($request->hasFile('file_bukti')) {
            $uploadedFiles = $request->file('file_bukti');
            $uploadedFiles = is_array($uploadedFiles) ? $uploadedFiles : [$uploadedFiles];

            $pathList = $jawaban->file_bukti ?? [];
            $namaList = $jawaban->nama_file_asli ?? [];
            $ukuranList = $jawaban->ukuran_file ?? [];

            foreach ($uploadedFiles as $file) {
                if ($file && $file->isValid()) {
                    $pathList[] = $file->store('bukti_audit', 'public');
                    $namaList[] = $file->getClientOriginalName();
                    $ukuranList[] = $file->getSize();
                }
            }

            $data['file_bukti'] = $pathList;
            $data['nama_file_asli'] = $namaList;
            $data['ukuran_file'] = $ukuranList;
        }

        if (empty($data)) {
            return response()->json(['success' => true, 'message' => 'Tidak ada perubahan']);
        }

        if (in_array($jawaban->status_verifikasi, ['ditolak', 'disetujui', null])) {
            $data['status_verifikasi'] = 'pending';
        }

        $jawaban->update($data);

        return response()->json([
            'success' => true,
            'was_approved' => $jawaban->getOriginal('status_verifikasi') === 'disetujui',
        ]);
    }

    public function previewFile($jawaban_id, $index = 0)
    {
        $jawaban = AssessmentJawaban::with('assessment')->findOrFail($jawaban_id);

        abort_if(
            $jawaban->assessment->user_id !== auth()->user()->user_id,
            403,
            'Akses ditolak.'
        );

        $paths = $jawaban->file_bukti ?? [];
        $namas = $jawaban->nama_file_asli ?? [];

        if (empty($paths) || !isset($paths[$index])) {
            abort(404, 'File tidak ditemukan.');
        }

        $fullPath = storage_path('app/public/' . $paths[$index]);

        if (!file_exists($fullPath)) {
            abort(404, 'File tidak ditemukan di storage.');
        }

        $namaFile = $namas[$index] ?? basename($paths[$index]);
        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
        $mimeMap = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        return response()->file($fullPath, [
            'Content-Type' => $mimeMap[$ext] ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . $namaFile . '"',
        ]);
    }

    public function hapusFile(Request $request, $jawaban_id)
    {
        $jawaban = AssessmentJawaban::with('assessment')->findOrFail($jawaban_id);

        // Guard: hanya pemilik assessment
        abort_if(
            $jawaban->assessment->user_id !== auth()->user()->user_id,
            403,
            'Akses ditolak.'
        );

        // Guard: tidak boleh hapus kalau assessment sedang direview/sudah final
        abort_if(
            in_array($jawaban->assessment->status, ['in_review', 'disetujui']),
            403,
            'Assessment sedang dikunci.'
        );

        $request->validate([
            'index' => 'required|integer|min:0',
        ]);

        $index = (int) $request->index;
        $pathList = $jawaban->file_bukti ?? [];
        $namaList = $jawaban->nama_file_asli ?? [];
        $ukuranList = $jawaban->ukuran_file ?? [];

        if (!isset($pathList[$index])) {
            return response()->json(['success' => false, 'message' => 'File tidak ditemukan.'], 404);
        }

        // Hapus file fisik dari storage
        $filePath = storage_path('app/public/' . $pathList[$index]);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Hapus dari array (reindex)
        array_splice($pathList, $index, 1);
        array_splice($namaList, $index, 1);
        array_splice($ukuranList, $index, 1);

        $jawaban->update([
            'file_bukti' => array_values($pathList),
            'nama_file_asli' => array_values($namaList),
            'ukuran_file' => array_values($ukuranList),
        ]);

        return response()->json([
            'success' => true,
            'sisa_file' => count($pathList),
        ]);
    }
}