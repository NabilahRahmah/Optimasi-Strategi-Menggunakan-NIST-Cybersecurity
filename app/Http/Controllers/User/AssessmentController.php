<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentJawaban;
use App\Models\Domain;
use App\Models\DokumenPendukung;
use App\Models\Pertanyaan;
use App\Services\SkorService;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function pilihFramework()
    {
        $frameworks = auth()->user()->assignedFrameworks()
            ->where('is_active', true)
            ->get();

        return view('assessment.pilih-framework', compact('frameworks'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        $frameworkId = $request->query('framework_id');

        // Validasi: framework yang diminta harus salah satu yang di-assign ke user ini
        if ($frameworkId && !$user->isAssignedTo($frameworkId)) {
            abort(403, 'Anda tidak memiliki akses ke framework ini.');
        }

        // Kalau tidak ada framework_id di query string, coba ambil dari assessment draft yang sedang berjalan
        if (!$frameworkId) {
            $draft = Assessment::where('user_id', $user->user_id)
                ->where('status', 'draft')
                ->latest()
                ->first();

            $frameworkId = $draft?->framework_id;
        }

        // Masih kosong juga? Berarti user belum pilih framework — lempar ke halaman pilih
        if (!$frameworkId) {
            return redirect()->route('assessment.pilihFramework')
                ->with('error', 'Silakan pilih framework terlebih dahulu.');
        }

        $domains = Domain::where('framework_id', $frameworkId)
            ->with([
                'kategoris.pertanyaans' => function ($q) {
                    $q->orderBy('kode_pertanyaan');
                }
            ])
            ->orderBy('kode_domain')
            ->get();

        $assessment = Assessment::where('user_id', $user->user_id)
            ->where('framework_id', $frameworkId)
            ->where('status', 'draft')
            ->with('jawabans')
            ->latest()
            ->first();

        if (!$assessment) {
            $assessment = Assessment::create([
                'user_id' => $user->user_id,
                'framework_id' => $frameworkId,
                'judul_assessment' => 'Self Assessment Q' . now()->quarter . ' ' . now()->year,
                'tgl_pelaksanaan' => now()->toDateString(),
                'status' => 'draft',
            ]);
        }

        $existingJawaban = $assessment->jawabans->keyBy('pertanyaan_id');

        return view('assessment.create', compact('domains', 'assessment', 'existingJawaban'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,assessment_id',
            'judul_assessment' => 'required|string|max:255',
            'scores' => 'nullable|array',
            'scores.*' => 'nullable|integer|min:0|max:5',
            'files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $assessment = Assessment::where('user_id', auth()->user()->user_id)
            ->where('assessment_id', $request->assessment_id)
            ->where('status', 'draft')
            ->firstOrFail();

        $isDraft = $request->action === 'draft';

        $assessment->update([
            'judul_assessment' => $request->judul_assessment,
            'status' => $isDraft ? 'draft' : 'submitted',
        ]);

        if ($request->has('scores')) {
            foreach ($request->scores as $pertanyaan_id => $nilai) {
                $pathBukti = null;
                $namaFileAsli = null;
                $ukuranFile = null;

                if ($request->hasFile("files.{$pertanyaan_id}")) {
                    $file = $request->file("files.{$pertanyaan_id}");

                    if ($file->isValid()) {
                        $namaFileAsli = $file->getClientOriginalName();
                        $ukuranFile = $file->getSize();
                        $pathBukti = $file->store('bukti_audit', 'local');

                        $existing = AssessmentJawaban::where('assessment_id', $assessment->assessment_id)
                            ->where('pertanyaan_id', $pertanyaan_id)
                            ->first();

                        if ($existing?->file_bukti) {
                            \Storage::disk('local')->delete($existing->file_bukti);
                        }
                    }
                }

                $jawabanData = [
                    'indeks_nilai' => $nilai ?: null,
                    'status_verifikasi' => 'pending',
                ];

                if ($pathBukti) {
                    $jawabanData['file_bukti'] = $pathBukti;
                    $jawabanData['nama_file_asli'] = $namaFileAsli;
                }

                $jawaban = AssessmentJawaban::updateOrCreate(
                    [
                        'assessment_id' => $assessment->assessment_id,
                        'pertanyaan_id' => $pertanyaan_id, // fix: key yang benar
                    ],
                    $jawabanData
                );

                if ($pathBukti) {
                    $pertanyaan = Pertanyaan::with('kategori.domain')->find($pertanyaan_id);
                    $domainId = $pertanyaan?->kategori?->domain?->domain_id;

                    DokumenPendukung::where('jawaban_id', $jawaban->jawaban_id)->delete();

                    DokumenPendukung::create([
                        'user_id' => auth()->user()->user_id,
                        'jawaban_id' => $jawaban->jawaban_id,
                        'domain_id' => $domainId,
                        'nama_dokumen' => 'Bukti: ' . ($pertanyaan?->kode_pertanyaan ?? 'Pertanyaan'),
                        'jenis_dokumen' => 'Bukti Assessment',
                        'deskripsi' => $pertanyaan?->judul,
                        'file_path' => $pathBukti,
                        'nama_file_asli' => $namaFileAsli,
                        'ukuran_file' => $ukuranFile,
                        'status' => 'aktif',
                        'sumber' => 'assessment',
                    ]);
                }
            }
        }

        if (!$isDraft) {
            try {
                $skorService = new SkorService();
                $skorService->calculate($assessment->assessment_id);
            } catch (\Exception $e) {
                \Log::warning('SkorService gagal: ' . $e->getMessage());
            }

            return redirect()
                ->route('user.dashboard')
                ->with('success', 'Assessment berhasil disubmit! Menunggu verifikasi Approver.');
        }

        return redirect()
            ->route('assessment.create')
            ->with('success', 'Draft berhasil disimpan!');
    }

    public function saveJawaban(Request $request, $assessment_id)
    {
        $assessment = Assessment::where('user_id', auth()->user()->user_id)
            ->where('assessment_id', $assessment_id)
            ->firstOrFail();

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

    public function revisi(Assessment $assessment)
    {
        abort_if($assessment->user_id !== auth()->user()->user_id, 403);

        $assessment->load([
            'jawabans' => fn($q) => $q->where('status_verifikasi', 'ditolak')
                ->with('pertanyaan.kategori.domain'),
        ]);

        $grouped = $assessment->jawabans
            ->filter(fn($j) => $j->pertanyaan?->kategori?->domain)
            ->groupBy(fn($j) => $j->pertanyaan->kategori->domain->nama_domain)
            ->map(
                fn($byDomain) =>
                $byDomain->groupBy(fn($j) => $j->pertanyaan->kategori->nama_kategori)
            );

        return view('assessment.revisi', compact('assessment', 'grouped'));
    }

    public function simpanRevisi(Request $request, Assessment $assessment)
    {
        abort_if($assessment->user_id !== auth()->user()->user_id, 403);

        $request->validate([
            'scores' => 'nullable|array',
            'scores.*' => 'nullable|integer|min:0|max:5',
            'files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->has('scores')) {
            foreach ($request->scores as $pertanyaan_id => $nilai) {
                $pathBukti = null;
                $namaFileAsli = null;
                $ukuranFile = null;

                if ($request->hasFile("files.{$pertanyaan_id}")) {
                    $file = $request->file("files.{$pertanyaan_id}");
                    if ($file->isValid()) {
                        $namaFileAsli = $file->getClientOriginalName();
                        $ukuranFile = $file->getSize();
                        $pathBukti = $file->store('bukti_audit', 'local');
                    }
                }

                $jawabanData = [
                    'indeks_nilai' => $nilai ?: null,
                    'status_verifikasi' => 'pending',
                    'komentar_approver' => null,
                ];

                if ($pathBukti) {
                    $jawabanData['file_bukti'] = $pathBukti;
                    $jawabanData['nama_file_asli'] = $namaFileAsli;
                }

                $jawaban = AssessmentJawaban::where('assessment_id', $assessment->assessment_id)
                    ->where('pertanyaan_id', $pertanyaan_id)
                    ->first();

                if ($jawaban) {
                    $jawaban->update($jawabanData);

                    if ($pathBukti) {
                        $pertanyaan = Pertanyaan::with('kategori.domain')->find($pertanyaan_id);
                        $domainId = $pertanyaan?->kategori?->domain?->domain_id;

                        DokumenPendukung::where('jawaban_id', $jawaban->jawaban_id)->delete();

                        DokumenPendukung::create([
                            'user_id' => auth()->user()->user_id,
                            'jawaban_id' => $jawaban->jawaban_id,
                            'domain_id' => $domainId,
                            'nama_dokumen' => 'Bukti: ' . ($pertanyaan?->kode_pertanyaan ?? 'Pertanyaan'),
                            'jenis_dokumen' => 'Bukti Assessment',
                            'deskripsi' => $pertanyaan?->judul,
                            'file_path' => $pathBukti,
                            'nama_file_asli' => $namaFileAsli,
                            'ukuran_file' => $ukuranFile,
                            'status' => 'aktif',
                            'sumber' => 'assessment',
                        ]);
                    }
                }
            }
        }

        $masihDitolak = $assessment->jawabans()
            ->where('status_verifikasi', 'ditolak')
            ->count();

        if ($masihDitolak === 0) {
            $assessment->update(['status' => 'submitted']);
        }

        return redirect()
            ->route('user.hasil.show', $assessment->assessment_id)
            ->with('success', 'Revisi berhasil disimpan! Menunggu verifikasi ulang dari Approver.');
    }
}