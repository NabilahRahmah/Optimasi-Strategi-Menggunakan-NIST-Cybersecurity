<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Framework;
use App\Models\FrameworkAssignment;
use App\Models\Assessment;
use App\Models\AssessmentJawaban;
use App\Models\Kategori;
use App\Models\Pertanyaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssessmentController extends Controller
{
    // ══════════════════════════════════════
    //  KATEGORI
    // ══════════════════════════════════════

    public function index()
    {
        $domains = Domain::with([
            'kategoris' => function ($q) {
                $q->with(['pertanyaans' => fn($q2) => $q2->orderBy('kode_pertanyaan')])
                    ->orderBy('kode_kategori');
            }
        ])->orderBy('kode_domain')->get();

        // Load semua framework + user yang sudah di-assign
        $frameworks = Framework::with(['assignedUsers', 'domains'])
            ->where('is_active', true)
            ->get();

        // User & Approver yang bisa di-assign
        $assignableUsers = User::whereIn('role', ['user', 'approver'])
            ->orderBy('name')
            ->get();

        return view('admin.assessment.index', compact('domains', 'frameworks', 'assignableUsers'));
    }

    public function create(Request $request)
    {
        $domainId = $request->query('domain_id');

        // Cari domain asal untuk tahu framework_id-nya
        $currentDomain = $domainId ? Domain::find($domainId) : null;
        $frameworkId = $currentDomain?->framework_id;

        // Filter domain sesuai framework yang sama
        $domains = Domain::when($frameworkId, fn($q) => $q->where('framework_id', $frameworkId))
            ->orderBy('kode_domain')
            ->get();

        return view('admin.assessment.create', compact('domains', 'domainId', 'frameworkId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'domain_id' => 'required|exists:domains,domain_id',
            'kode_kategori' => 'required|string|max:50|unique:kategoris,kode_kategori',
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $domain = Domain::findOrFail($request->domain_id);

        Kategori::create([
            'domain_id' => $request->domain_id,
            'kode_kategori' => $request->kode_kategori,
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

         return redirect()->route('admin.assessment.edit', $domain->framework_id)   
        ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $framework = Framework::with([
            'domains' => fn($q) => $q->orderBy('kode_domain'),
            'domains.kategoris' => fn($q) => $q->orderBy('kode_kategori'),
            'domains.kategoris.pertanyaans' => fn($q) => $q->orderBy('kode_pertanyaan'),
        ])->findOrFail($id);

        $assessment = Assessment::firstOrCreate(
            [
                'user_id' => auth()->user()->user_id,
                'framework_id' => $id,
            ],
            [
                'judul_assessment' => 'Admin Review - ' . $framework->name_framework,
                'tgl_pelaksanaan' => now()->toDateString(),
                'status' => 'draft',
            ]
        );

        $jawabans = AssessmentJawaban::where('assessment_id', $assessment->assessment_id)
            ->get()
            ->keyBy('pertanyaan_id');

        return view('admin.assessment.edit', compact('framework', 'assessment', 'jawabans'));
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'domain_id' => 'required|exists:domains,domain_id',
            'kode_kategori' => 'required|string|max:50|unique:kategoris,kode_kategori,' . $kategori->kategori_id . ',kategori_id',
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update([
            'domain_id' => $request->domain_id,
            'kode_kategori' => $request->kode_kategori,
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.assessment.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Kategori::findOrFail($id)->delete();
        return redirect()->route('admin.assessment.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    // ══════════════════════════════════════
    //  PERTANYAAN
    // ══════════════════════════════════════

    public function createPertanyaan(Request $request)
    {
        $kategoris = Kategori::with('domain')->orderBy('kode_kategori')->get();
        $selectedId = $request->query('kategori_id');

        // Derive framework_id dari kategori yang dipilih
        $currentKategori = $selectedId ? Kategori::with('domain')->find($selectedId) : null;
        $frameworkId = $currentKategori?->domain?->framework_id;

        return view('admin.assessment.pertanyaan_create', compact('kategoris', 'selectedId', 'frameworkId'));
    }

    public function storePertanyaan(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,kategori_id',
            'kode_pertanyaan' => 'required|string|max:50|unique:pertanyaans,kode_pertanyaan',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori = Kategori::with('domain')->findOrFail($request->kategori_id);

        Pertanyaan::create([
            'kategori_id' => $request->kategori_id,
            'kode_pertanyaan' => $request->kode_pertanyaan,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.assessment.edit', $kategori->domain->framework_id)
            ->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    public function editPertanyaan($id)
    {
        $pertanyaan = Pertanyaan::with('kategori.domain')->findOrFail($id);
        $kategoris = Kategori::with('domain')->orderBy('kode_kategori')->get();
        $frameworkId = $pertanyaan->kategori?->domain?->framework_id;

        return view('admin.assessment.pertanyaan_edit', compact('pertanyaan', 'kategoris', 'frameworkId'));
    }

    public function updatePertanyaan(Request $request, $id)
    {
        $pertanyaan = Pertanyaan::findOrFail($id);

        $request->validate([
            'kategori_id' => 'required|exists:kategoris,kategori_id',
            'kode_pertanyaan' => 'required|string|max:50|unique:pertanyaans,kode_pertanyaan,' . $pertanyaan->pertanyaan_id . ',pertanyaan_id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $pertanyaan->update([
            'kategori_id' => $request->kategori_id,
            'kode_pertanyaan' => $request->kode_pertanyaan,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
        ]);

        $kategori = Kategori::with('domain')->findOrFail($request->kategori_id);

        return redirect()->route('admin.assessment.edit', $kategori->domain->framework_id)
            ->with('success', 'Pertanyaan berhasil diperbarui!');
    }

    public function destroyPertanyaan($id)
    {
        $pertanyaan = Pertanyaan::with('kategori.domain')->findOrFail($id);
        $frameworkId = $pertanyaan->kategori?->domain?->framework_id;

        $pertanyaan->delete();

        return redirect()->route('admin.assessment.edit', $frameworkId)
            ->with('success', 'Pertanyaan berhasil dihapus!');
    }

    public function saveJawaban(Request $request, $framework_id)
    {
        $request->validate([
            'pertanyaan_id' => 'required|exists:pertanyaans,pertanyaan_id',
            'indeks_nilai' => 'nullable|integer|min:0|max:5',
            'komentar_approver' => 'nullable|string|max:1000',
            'file_bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $assessment = Assessment::where('user_id', auth()->user()->user_id)
            ->where('framework_id', $framework_id)
            ->firstOrFail();

        $data = [];

        // Hanya update field yang memang dikirim di request
        if ($request->has('indeks_nilai')) {
            $data['indeks_nilai'] = $request->indeks_nilai;
        }

        if ($request->has('komentar_approver')) {
            $data['komentar_approver'] = $request->komentar_approver;
        }

        if ($request->hasFile('file_bukti') && $request->file('file_bukti')->isValid()) {
            $file = $request->file('file_bukti');

            // Hapus file lama kalau ada (replace saat upload ulang)
            $existing = AssessmentJawaban::where('assessment_id', $assessment->assessment_id)
                ->where('pertanyaan_id', $request->pertanyaan_id)
                ->first();
            if ($existing?->file_bukti) {
                Storage::disk('local')->delete($existing->file_bukti);
            }

            $data['file_bukti'] = $file->store('bukti_audit', 'local');
            $data['nama_file_asli'] = $file->getClientOriginalName();
            $data['ukuran_file'] = $file->getSize();
        }

        // Kalau gak ada data yang perlu diupdate, return early
        if (empty($data)) {
            return response()->json(['success' => true, 'message' => 'Tidak ada perubahan']);
        }

        $jawaban = AssessmentJawaban::firstOrCreate(
            [
                'assessment_id' => $assessment->assessment_id,
                'pertanyaan_id' => $request->pertanyaan_id,
            ]
        );

        if (in_array($jawaban->status_verifikasi, [null, 'rejected'])) {
            $data['status_verifikasi'] = 'pending';
        }

        $jawaban->update($data);

        return response()->json(['success' => true]);
    }

    public function previewFile($jawaban_id)
    {
        $jawaban = AssessmentJawaban::findOrFail($jawaban_id);
        $fullPath = storage_path('app/' . $jawaban->file_bukti);

        if (!$jawaban->file_bukti || !file_exists($fullPath)) {
            abort(404, 'File tidak ditemukan.');
        }

        $ext = strtolower(pathinfo($jawaban->nama_file_asli, PATHINFO_EXTENSION));
        $mimeMap = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
        ];

        return response()->file($fullPath, [
            'Content-Type' => $mimeMap[$ext] ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . $jawaban->nama_file_asli . '"',
        ]);
    }

    // ══════════════════════════════════════
    //  ASSIGN USER & APPROVER KE FRAMEWORK
    // ══════════════════════════════════════

    public function assign(Request $request, $framework_id)
    {
        $framework = Framework::findOrFail($framework_id);

        $request->validate([
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,user_id',
        ]);

        $framework->assignedUsers()->sync($request->user_ids ?? []);

        return redirect()->route('admin.assessment.index')
            ->with('success', 'Akses framework berhasil diperbarui!');
    }
}