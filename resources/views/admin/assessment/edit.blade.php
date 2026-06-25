@extends('layouts.dashboard')
@section('content')
    <div class="space-y-6">

        <nav class="flex items-center gap-2 text-sm text-muted-foreground">
            <span>Platform</span><span>/</span>
            <a href="{{ route('admin.assessment.index') }}" class="hover:text-foreground">Assessment</a>
            <span>/</span>
            <span class="font-medium text-foreground">Edit Struktur</span>
        </nav>

        @if(session('success'))
            <div id="flash-success" class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-xl border bg-white shadow-sm overflow-hidden">

            {{-- Header gelap = Nama Framework --}}
            <div class="flex items-center justify-between px-6 py-4 bg-gray-800">
                <h2 class="text-base font-bold text-white">{{ $framework->name_framework }}</h2>
                <a href="{{ route('admin.assessment.index') }}"
                    class="inline-flex items-center justify-center rounded-md border border-gray-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-gray-700 transition-colors">
                    ← Back to List
                </a>
            </div>

            {{-- Header Kolom Tabel --}}
            <div class="border-b bg-red-50/80">
                <div class="grid text-xs font-semibold text-gray-600 px-4 py-2.5"
                    style="grid-template-columns: 150px 1fr 180px 130px 140px 160px 80px;">
                    <div>No</div>
                    <div>Question</div>
                    <div class="text-center">Evidence & Supporting Doc</div>
                    <div class="text-center">Index</div>
                    <div class="text-center">Current Status</div>
                    <div>Approver Comment</div>
                    <div class="text-right">Aksi</div>
                </div>
            </div>

            {{-- Loop per Domain --}}
            @forelse($framework->domains as $domain)

                {{-- Domain Header --}}
                <div class="flex items-center justify-between px-4 py-2 bg-gray-200 border-b border-gray-300">
                    <div class="flex items-center gap-2">
                        <span
                            class="font-mono text-xs font-bold text-gray-700 bg-white border border-gray-300 px-2 py-0.5 rounded">{{ $domain->kode_domain }}</span>
                        <span class="text-sm font-bold text-gray-700">{{ $domain->nama_domain }}</span>
                        <span class="text-xs text-gray-400">({{ $domain->kategoris->count() }} kategori)</span>
                    </div>
                    <a href="{{ route('admin.assessment.create', ['domain_id' => $domain->domain_id]) }}"
                        class="inline-flex items-center rounded bg-gray-700 px-2.5 py-1 text-[11px] font-semibold text-white hover:bg-gray-900 transition-colors">
                        + Tambah Kategori
                    </a>
                </div>

                @forelse($domain->kategoris as $kat)
                    @php $accordionId = 'kat-' . $kat->kategori_id; @endphp

                    {{-- Row Kategori --}}
                    <div class="border-b">
                        <button type="button" onclick="toggleAccordion('{{ $accordionId }}')"
                            class="w-full flex items-center justify-between px-4 py-2.5 bg-gray-50 hover:bg-gray-100 transition-colors text-left">
                            <div class="flex items-center gap-3">
                                <span class="font-semibold text-sm text-gray-800">{{ $kat->nama_kategori }}</span>
                                <span
                                    class="font-mono text-xs font-bold text-primary bg-primary/10 px-2 py-0.5 rounded">{{ $kat->kode_kategori }}</span>
                            </div>
                            <div class="flex items-center gap-4 shrink-0">
                                <span class="text-xs text-gray-500">
                                    <span class="font-semibold text-gray-700">{{ $kat->pertanyaans->count() }}</span> pertanyaan
                                </span>
                                <span class="text-xs text-gray-400">{{ $kat->updated_at->format('d M Y') }}</span>
                                <a href="{{ route('admin.pertanyaan.create', ['kategori_id' => $kat->kategori_id]) }}"
                                    onclick="event.stopPropagation()"
                                    class="inline-flex items-center rounded bg-primary px-2.5 py-1 text-[11px] font-semibold text-white hover:bg-primary/90 transition-colors">
                                    + Tambah Pertanyaan
                                </a>
                                <svg id="chevron-{{ $accordionId }}" class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>

                        {{-- Pertanyaan --}}
                        <div id="{{ $accordionId }}" class="hidden">
                            @forelse($kat->pertanyaans as $ptq)
                                        @php
                                            $jawaban = $jawabans[$ptq->pertanyaan_id] ?? null;
                                            $labels = [0 => 'Tidak Ada', 1 => 'Awal', 2 => 'Berulang', 3 => 'Terdefinisi', 4 => 'Terkelola', 5 => 'Inovatif'];
                                        @endphp
                                        <div class="grid border-t hover:bg-gray-50/50 transition-colors items-start py-3 px-4 gap-2"
                                            style="grid-template-columns: 150px 1fr 180px 130px 140px 160px 80px;"
                                            id="row-{{ $ptq->pertanyaan_id }}">

                                            {{-- No --}}
                                            <div class="font-mono text-xs font-bold text-primary pt-0.5">
                                                {{ $ptq->kode_pertanyaan }}
                                            </div>

                                            {{-- Question --}}
                                            <div>
                                                <p class="font-medium text-gray-800 text-xs leading-relaxed">{{ $ptq->judul }}</p>
                                                @if($ptq->deskripsi)
                                                    <p class="text-[11px] text-gray-400 mt-0.5">{{ Str::limit($ptq->deskripsi, 100) }}</p>
                                                @endif
                                            </div>

                                            {{-- Evidence --}}
                                            <div class="flex flex-col gap-1.5">
                                                @if($jawaban?->file_bukti)
                                                    @php
                                                        $files = is_array($jawaban->file_bukti) ? $jawaban->file_bukti : [$jawaban->file_bukti];
                                                        $namas = is_array($jawaban->nama_file_asli) ? $jawaban->nama_file_asli : [$jawaban->nama_file_asli ?? basename($jawaban->file_bukti)];
                                                    @endphp
                                                    @foreach($files as $fi => $fpath)
                                                        <a href="{{ route('bukti.preview', ['jawaban_id' => $jawaban->jawaban_id, 'index' => $fi]) }}"
                                                            target="_blank"
                                                            class="text-[11px] text-blue-600 font-medium truncate max-w-[160px] hover:underline"
                                                            title="{{ $namas[$fi] ?? 'File ' . ($fi + 1) }}">
                                                            📎 {{ $namas[$fi] ?? 'File ' . ($fi + 1) }}
                                                        </a>
                                                    @endforeach
                                                @endif
                                                <label
                                                    class="inline-flex items-center gap-1.5 cursor-pointer rounded border border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 px-2.5 py-1.5 text-[11px] text-gray-500 transition-colors">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" y1="3" x2="12" y2="15" />
                                                    </svg>
                                                    {{ $jawaban?->nama_file_asli ? 'Ganti File' : 'Upload Dokumen' }}
                                                    <input type="file" class="hidden file-input" data-pertanyaan-id="{{ $ptq->pertanyaan_id }}"
                                                        data-framework-id="{{ $framework->framework_id }}"
                                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                                </label>
                                                <span id="file-status-{{ $ptq->pertanyaan_id }}"
                                                    class="text-[10px] text-gray-400 italic"></span>
                                            </div>

                                            {{-- Index dropdown --}}
                                            <div class="flex justify-center">
                                                <select
                                                    class="index-select text-xs border border-gray-200 rounded-md px-2 py-1.5 bg-white focus:outline-none focus:ring-1 focus:ring-primary/40 text-gray-700 w-full max-w-[110px]"
                                                    data-pertanyaan-id="{{ $ptq->pertanyaan_id }}"
                                                    data-framework-id="{{ $framework->framework_id }}">
                                                    <option value="">— Pilih —</option>
                                                    @foreach($labels as $val => $lbl)
                                                        <option value="{{ $val }}" {{ $jawaban?->indeks_nilai === $val ? 'selected' : '' }}>
                                                            {{ $val }} – {{ $lbl }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Current Status --}}
                                            <div class="flex justify-center">
                                                @php
                                                    $status = $jawaban?->status_verifikasi ?? 'pending';
                                                    $statusMap = [
                                                        'pending' => ['bg-amber-50 text-amber-600 border-amber-200', 'bg-amber-400', 'Pending'],
                                                        'disetujui' => ['bg-green-50 text-green-600 border-green-200', 'bg-green-400', 'disetujui'],
                                                        'ditolak' => ['bg-red-50 text-red-600 border-red-200', 'bg-red-400', 'Revision'],
                                                    ];
                                                    [$cls, $dot, $label] = $statusMap[$status] ?? $statusMap['pending'];
                                                @endphp
                                 <span
                                                    class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[11px] font-medium border {{ $cls }}">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $dot }} inline-block"></span>
                                                    {{ $label }}
                                                </span>
                                            </div>

                                            {{-- Approver Comment --}}
                                            <div>
                                                <textarea
                                                    class="comment-input w-full text-[11px] border border-gray-200 rounded px-2 py-1 bg-white resize-none focus:outline-none focus:ring-1 focus:ring-primary/40 text-gray-700"
                                                    rows="2" placeholder="Tulis komentar..." data-pertanyaan-id="{{ $ptq->pertanyaan_id }}"
                                                    data-framework-id="{{ $framework->framework_id }}">{{ $jawaban?->komentar_approver }}</textarea>
                                                <button type="button"
                                                    class="save-comment-btn mt-1 text-[10px] text-primary hover:underline font-semibold"
                                                    data-pertanyaan-id="{{ $ptq->pertanyaan_id }}"
                                                    data-framework-id="{{ $framework->framework_id }}">
                                                    Simpan Komentar
                                                </button>
                                            </div>

                                            {{-- Aksi --}}
                                            <div class="flex justify-end gap-1">
                                                <a href="{{ route('admin.pertanyaan.edit', $ptq->pertanyaan_id) }}"
                                                    class="inline-flex h-7 w-7 items-center justify-center rounded border border-input bg-white hover:bg-accent transition-colors"
                                                    title="Edit">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.pertanyaan.destroy', $ptq->pertanyaan_id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus pertanyaan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex h-7 w-7 items-center justify-center rounded border border-input bg-white text-destructive hover:bg-red-50 transition-colors"
                                                        title="Hapus">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2">
                                                            <polyline points="3 6 5 6 21 6" />
                                                            <path
                                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                            @empty
                                <div class="px-4 py-5 text-center text-xs text-gray-400 border-t">
                                    Belum ada pertanyaan.
                                    <a href="{{ route('admin.pertanyaan.create', ['kategori_id' => $kat->kategori_id]) }}"
                                        class="text-primary font-semibold hover:underline ml-1">+ Tambah sekarang</a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-5 text-center text-xs text-gray-400 border-b">
                        Belum ada kategori dalam domain ini.
                        <a href="{{ route('admin.assessment.create', ['domain_id' => $domain->domain_id]) }}"
                            class="text-primary font-semibold hover:underline ml-1">+ Tambah Kategori</a>
                    </div>
                @endforelse

            @empty
                <div class="px-6 py-12 text-center text-sm text-gray-400">
                    Belum ada domain dalam framework ini.
                </div>
            @endforelse

        </div>
    </div>

    @push('scripts')
        <script>
            function toggleAccordion(id) {
                const el = document.getElementById(id);
                const chevron = document.getElementById('chevron-' + id);
                const isHidden = el.classList.contains('hidden');
                el.classList.toggle('hidden', !isHidden);
                chevron.style.transform = isHidden ? 'rotate(180deg)' : '';
            }

            // Helper AJAX
            async function saveJawaban(formData, frameworkId) {
                const res = await fetch(`/admin/assessment/${frameworkId}/jawaban`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData,
                });
                return res.json();
            }

            function showStatus(pertanyaanId, msg, success = true) {
                const el = document.getElementById('file-status-' + pertanyaanId);
                if (el) {
                    el.textContent = msg;
                    el.className = `text-[10px] italic ${success ? 'text-green-500' : 'text-red-500'}`;
                    setTimeout(() => el.textContent = '', 3000);
                }
            }

            // Upload file otomatis saat pilih
            document.querySelectorAll('.file-input').forEach(input => {
                input.addEventListener('change', async function () {
                    const pertanyaanId = this.dataset.pertanyaanId;
                    const frameworkId = this.dataset.frameworkId;
                    const file = this.files[0];
                    if (!file) return;

                    showStatus(pertanyaanId, 'Mengupload...', true);

                    const formData = new FormData();
                    formData.append('pertanyaan_id', pertanyaanId);
                    formData.append('file_bukti', file);

                    const result = await saveJawaban(formData, frameworkId);
                    showStatus(pertanyaanId, result.success ? '✓ File tersimpan' : '✗ Gagal upload', result.success);
                });
            });

            // Index dropdown — save on change
            document.querySelectorAll('.index-select').forEach(select => {
                select.addEventListener('change', async function () {
                    const pertanyaanId = this.dataset.pertanyaanId;
                    const frameworkId = this.dataset.frameworkId;

                    const formData = new FormData();
                    formData.append('pertanyaan_id', pertanyaanId);
                    formData.append('indeks_nilai', this.value);

                    const result = await saveJawaban(formData, frameworkId);
                    showStatus(pertanyaanId, result.success ? '✓ Index tersimpan' : '✗ Gagal', result.success);
                });
            });

            // Simpan Komentar button
            document.querySelectorAll('.save-comment-btn').forEach(btn => {
                btn.addEventListener('click', async function () {
                    const pertanyaanId = this.dataset.pertanyaanId;
                    const frameworkId = this.dataset.frameworkId;
                    const textarea = document.querySelector(`.comment-input[data-pertanyaan-id="${pertanyaanId}"]`);

                    const formData = new FormData();
                    formData.append('pertanyaan_id', pertanyaanId);
                    formData.append('komentar_approver', textarea.value);

                    const result = await saveJawaban(formData, frameworkId);
                    showStatus(pertanyaanId, result.success ? '✓ Komentar tersimpan' : '✗ Gagal', result.success);
                });
            });
        </script>
    @endpush
@endsection