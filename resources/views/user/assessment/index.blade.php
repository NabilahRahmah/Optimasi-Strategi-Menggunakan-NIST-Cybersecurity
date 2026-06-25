@extends('layouts.dashboard')

@section('content')

@php
    $totalPertanyaan = $domains->sum(fn($d) => $d->kategoris->sum(fn($k) => $k->pertanyaans->count()));
    $terisi = $existingJawaban->filter(fn($j) => $j->indeks_nilai > 0)->count();
    $progressPct = $totalPertanyaan > 0 ? round(($terisi / $totalPertanyaan) * 100) : 0;
    $belumDiisi = $totalPertanyaan - $terisi;

    // --- LOGIKA FASE ASSESSMENT ---
    $isReadOnly = $assessment->status === 'disetujui';
    $isReviewPhase = in_array($assessment->status, ['submitted', 'in_review']);
    $isRevisiPhase = $assessment->status === 'revisi';
    
    // Footer aksi (tombol submit) hanya muncul saat Draft atau Revisi
    $showFooterAksi = !$isReadOnly && !$isReviewPhase; 
@endphp 

<form id="assessmentForm"
      action="{{ route('user.assessment.store') }}"
      method="POST"
      enctype="multipart/form-data">
@csrf
<input type="hidden" name="assessment_id" value="{{ $assessment->assessment_id }}">
<input type="hidden" name="judul_assessment" value="{{ $assessment->judul_assessment }}">

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-2">
        <div class="flex items-center gap-2 text-xs text-gray-400 uppercase tracking-wider font-semibold">
            <span>Assessment</span>
            <span>/</span>
            <span class="text-primary font-bold">{{ $assessment->framework->name_framework ?? 'NIST CSF 2.0' }}</span>
        </div>
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-black text-on-surface tracking-tight">Self Assessment Keamanan Informasi</h1>
                <p class="text-sm text-gray-500 mt-2 max-w-2xl leading-relaxed">
                    Lakukan penilaian mandiri secara berkala untuk memastikan kepatuhan terhadap standar keamanan siber perusahaan. Pastikan setiap dokumen pendukung telah diunggah.
                </p>
            </div>
            <div class="shrink-0 w-full md:w-56 rounded-xl border bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Progres Pengisian</p>
                    <span class="text-sm font-black text-primary" id="pct-label">{{ $progressPct }}%</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-primary rounded-full transition-all duration-500" id="progress-bar" style="width: {{ $progressPct }}%"></div>
                </div>
                <p class="text-[10px] text-gray-400 mt-2" id="progress-text">{{ $terisi }} dari {{ $totalPertanyaan }} pertanyaan selesai</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700 font-medium flex items-center gap-2">
            <span class="material-symbols-outlined text-green-600 text-lg">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- BANNER NOTIFIKASI PINTAR --}}
    @if($isReadOnly)
        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-700 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-500">verified</span>
            Assessment ini sudah diverifikasi dan disetujui sepenuhnya. Tidak dapat diubah.
        </div>
    @elseif($isReviewPhase)
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700 flex items-center gap-2">
            <span class="material-symbols-outlined text-amber-500">lock</span>
            Assessment sedang direview oleh Approver. Seluruh form dikunci sementara agar data tidak berubah.
        </div>
    @elseif($isRevisiPhase)
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700 flex items-center gap-2">
            <span class="material-symbols-outlined text-red-500">warning</span>
            Assessment dikembalikan! Silakan perbaiki Skor Maturitas dan unggah bukti baru HANYA pada jawaban yang DITOLAK.
        </div>
    @endif

    {{-- TAB FILTER --}}
    <div class="flex flex-wrap items-center gap-2">
        {{-- Semua Domain (dropdown trigger) --}}
        <div class="relative" id="domainDropdownWrapper">
            <button type="button" id="btnDomainDropdown"
                class="status-tab inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-xs font-bold transition-colors bg-primary text-white shadow-md"
                data-status="all">
                <span class="material-symbols-outlined text-sm">filter_list</span>
                <span id="domainBtnLabel">Semua Domain</span>
                <span class="material-symbols-outlined text-sm transition-transform duration-200" id="domainChevron">expand_more</span>
            </button>

            {{-- Dropdown panel --}}
            <div id="domainDropdown"
                class="hidden absolute left-0 top-full mt-2 z-30 min-w-[220px] rounded-2xl border border-gray-200 bg-white shadow-xl p-3 space-y-1">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 px-2 pb-1">Pilih Domain</p>

                <button type="button" class="domain-option w-full flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-left transition-colors bg-primary/10 text-primary" data-domain="all">
                    <span class="material-symbols-outlined text-sm">select_all</span>
                    Semua Domain
                    <span class="ml-auto material-symbols-outlined text-sm text-primary" id="check-all">check</span>
                </button>

                @foreach($domains as $domain)
                    @php
                        $optionColor = match($domain->kode_domain) {
                            'GV' => 'text-purple-700 hover:bg-purple-50',
                            'ID' => 'text-blue-700 hover:bg-blue-50',
                            'PR' => 'text-green-700 hover:bg-green-50',
                            'DE' => 'text-yellow-700 hover:bg-yellow-50',
                            'RS' => 'text-orange-700 hover:bg-orange-50',
                            'RC' => 'text-red-700 hover:bg-red-50',
                            default => 'text-gray-700 hover:bg-gray-50',
                        };
                        $dotColor = match($domain->kode_domain) {
                            'GV' => 'bg-purple-500', 'ID' => 'bg-blue-500', 'PR' => 'bg-green-500',
                            'DE' => 'bg-yellow-500', 'RS' => 'bg-orange-500', 'RC' => 'bg-red-500',
                            default => 'bg-gray-400',
                        };
                        $domainCount = $domain->kategoris->sum(fn($k) => $k->pertanyaans->count());
                    @endphp
                    <button type="button" class="domain-option w-full flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-left transition-colors {{ $optionColor }}" data-domain="{{ $domain->kode_domain }}">
                        <span class="w-2 h-2 rounded-full shrink-0 {{ $dotColor }}"></span>
                        <span class="font-mono">{{ $domain->kode_domain }}</span>
                        <span class="font-normal opacity-70">{{ $domain->nama_domain }}</span>
                        <span class="ml-auto text-[10px] font-normal text-gray-400">{{ $domainCount }}</span>
                        <span class="material-symbols-outlined text-sm opacity-0" id="check-{{ $domain->kode_domain }}">check</span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Tab: Status --}}
        <button type="button" class="status-tab inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-xs font-bold transition-colors border border-gray-200 bg-white text-gray-600 hover:bg-gray-50" data-status="belum">
            <span class="material-symbols-outlined text-sm">radio_button_unchecked</span>
            Belum Diisi (<span id="count-belum">{{ $belumDiisi }}</span>)
        </button>
        <button type="button" class="status-tab inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-xs font-bold transition-colors border border-gray-200 bg-white text-gray-600 hover:bg-gray-50" data-status="selesai">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            Selesai (<span id="count-selesai">{{ $terisi }}</span>)
        </button>

        {{-- Draft Timestamp --}}
        <div class="ml-auto flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-500">
            <span class="material-symbols-outlined text-sm">schedule</span>
            Draft Terakhir: {{ $assessment->updated_at->format('H:i') }}
        </div>
    </div>

    {{-- DAFTAR PERTANYAAN --}}
    <div class="space-y-4" id="questionList">

        @foreach($domains as $domain)
            @php
                $domainBadgeClass = match($domain->kode_domain) {
                    'GV' => 'bg-purple-100 text-purple-700', 'ID' => 'bg-blue-100 text-blue-700',
                    'PR' => 'bg-green-100 text-green-700', 'DE' => 'bg-yellow-100 text-yellow-700',
                    'RS' => 'bg-orange-100 text-orange-700', 'RC' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-700',
                };
            @endphp

            @foreach($domain->kategoris as $kategori)
                @if($kategori->pertanyaans->count() > 0)
                <div class="domain-section-header flex items-center gap-3 mt-6 mb-2" data-domain="{{ $domain->kode_domain }}">
                    <span class="inline-flex items-center rounded-md px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider {{ $domainBadgeClass }}">
                        {{ $domain->kode_domain }} — {{ $domain->nama_domain }}
                    </span>
                    <span class="text-xs font-bold text-gray-700">{{ $kategori->kode_kategori }} · {{ $kategori->nama_kategori }}</span>
                    <div class="flex-1 h-px bg-gray-100"></div>
                    <span class="text-[10px] text-gray-400">{{ $kategori->pertanyaans->count() }} pertanyaan</span>
                </div>
                @endif

                @foreach($kategori->pertanyaans as $pertanyaan)
                    @php
                        $pid        = $pertanyaan->pertanyaan_id;
                        $jawaban    = $existingJawaban[$pid] ?? null;
                        $nilaiSaat  = $jawaban?->indeks_nilai ?? 0;
                        $fileList   = $jawaban?->file_bukti ?? [];
                        $namaList   = $jawaban?->nama_file_asli ?? [];
                        $hasFile    = !empty($fileList);
                        $komentar   = $jawaban?->komentar_approver ?? null;
                        $sudahDiisi = $nilaiSaat > 0;
                        $statusItem = $jawaban?->status_verifikasi;

                        // 🔴 KUNCI LOGIKA PER ITEM 🔴
                        // Kapan pertanyaan ini dikunci (disabled)?
                        if ($isReadOnly || $isReviewPhase) {
                            // Fase disetujui atau sedang direview approver = Kunci Semua
                            $itemLocked = true;
                        } elseif ($isRevisiPhase) {
                            // Fase revisi = Buka kuncinya HANYA kalau ditolak approver
                            $itemLocked = ($statusItem !== 'ditolak'); 
                        } else {
                            // Fase draft awal = Bebas isi semua
                            $itemLocked = false;
                        }
                    @endphp

                    <div class="question-card rounded-xl border bg-white shadow-sm overflow-hidden transition-all hover:shadow-md"
                         data-status="{{ $sudahDiisi ? 'selesai' : 'belum' }}"
                         data-domain="{{ $domain->kode_domain }}">
                        <div class="flex gap-6 p-6">

                            {{-- Konten Kiri --}}
                            <div class="flex-1 min-w-0">

                                {{-- Badge kode pertanyaan + status --}}
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 font-mono">
                                        {{ $pertanyaan->kode_pertanyaan }}
                                    </span>
                                    <span id="status-badge-{{ $pid }}">
                                        @if($sudahDiisi)
                                            <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-700">
                                                <span class="material-symbols-outlined text-xs">check_circle</span> Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-600">
                                                Belum Diisi
                                            </span>
                                        @endif
                                    </span>
                                     @if($statusItem)
                                        @php
                                            $svMap = [
                                                'pending'   => ['bg-amber-100 text-amber-700', 'schedule', 'Menunggu Verifikasi'],
                                                'disetujui' => ['bg-green-100 text-green-700', 'verified', 'Disetujui'],
                                                'ditolak'   => ['bg-red-100 text-red-700',     'cancel',   'Ditolak — Perlu Revisi'],
                                            ];
                                            [$svClass, $svIcon, $svLabel] = $svMap[$statusItem] ?? ['bg-gray-100 text-gray-500', 'help', $statusItem];
                                        @endphp
                                        <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $svClass }}">
                                        <span class="material-symbols-outlined text-xs">{{ $svIcon }}</span>
                                            {{ $svLabel }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Judul & Deskripsi Pertanyaan --}}
                                <h3 class="text-base font-bold text-on-surface leading-snug mb-1">
                                    {{ $pertanyaan->judul }}
                                </h3>
                                <p class="text-sm text-gray-500 leading-relaxed mb-4">
                                    {{ $pertanyaan->deskripsi }}
                                </p>

                                {{-- Feedback Approver --}}
                                @if($statusItem === 'ditolak')
                                    <div class="rounded-lg border border-red-200 bg-red-50 p-3 mb-4 flex items-start gap-2">
                                        <span class="material-symbols-outlined text-red-500 text-sm mt-0.5">warning</span>
                                        <div>
                                            <p class="text-[10px] font-bold text-red-600 uppercase tracking-wider mb-1">Catatan Approver</p>
                                            <p class="text-xs text-red-700 leading-relaxed">
                                            {{ $komentar ?? 'Jawaban ditolak. Silakan perbaiki dan upload ulang bukti.' }}
                                             </p>
                                        </div>
                                    </div>
                                @elseif($statusItem === 'disetujui')
                                    <div class="rounded-lg border border-green-200 bg-green-50 p-3 mb-4 flex items-start gap-2">
                                        <span class="material-symbols-outlined text-green-500 text-sm mt-0.5">check_circle</span>
                                        <p class="text-xs text-green-700 leading-relaxed font-medium">Jawaban telah diverifikasi dan disetujui.</p>
                                    </div>
                                @endif

                                {{-- Pilih Skor Maturitas --}}
                                <div class="mb-1">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2">Pilih Skor Maturitas</p>
                                    <div class="flex items-center gap-2">
                                        @for($level = 1; $level <= 5; $level++)
                                            <button type="button"
                                                class="maturity-btn w-10 h-10 rounded-lg border text-sm font-bold transition-all
                                                {{ $nilaiSaat == $level ? 'bg-primary text-white border-primary shadow-md scale-110' : 'bg-white text-gray-500 border-gray-200 hover:border-primary hover:text-primary' }}
                                                {{ $itemLocked ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                data-level="{{ $level }}"
                                                data-pid="{{ $pid }}"
                                                {{ $itemLocked ? 'disabled' : '' }}>
                                                {{ $level }}
                                            </button>
                                        @endfor

                                        <input type="hidden" name="scores[{{ $pid }}]" id="score-{{ $pid }}" value="{{ $nilaiSaat }}">

                                        @php $levelLabels = [1=>'Partial', 2=>'Risk Informed', 3=>'Repeatable', 4=>'Adaptive', 5=>'Optimal']; @endphp
                                        <span class="ml-2 text-xs font-semibold text-primary" id="level-label-{{ $pid }}">
                                            {{ $nilaiSaat > 0 ? ($levelLabels[$nilaiSaat] ?? '') : 'Belum dipilih' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Konten Kanan — Upload Bukti --}}
                            <div class="w-52 shrink-0">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2">
                                    Dokumen Bukti (PDF/JPG)
                                </p>

                                @if($hasFile)
                                    <div class="space-y-1 mb-2">
                                @foreach($fileList as $i => $path)
                                    <a href="{{ route('bukti.preview', ['jawaban_id' => $jawaban->jawaban_id, 'index' => $i]) }}"
                                        target="_blank"
                                        class="flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 p-2 hover:bg-gray-100 transition-colors">
                                        <span class="material-symbols-outlined text-red-500 text-base">description</span>
                                        <span class="text-[11px] font-medium text-gray-700 truncate flex-1">
                                            {{ $namaList[$i] ?? basename($path) }}
                                        </span>
                                    </a>
                                @endforeach
                                    </div>
                                @endif

                                {{-- Tampilkan form upload jika tidak dilock --}}
                                @if(!$itemLocked)
                                <label class="flex flex-col items-center justify-center w-full h-20 rounded-lg border-2 border-dashed border-gray-200 bg-gray-50 cursor-pointer hover:border-primary hover:bg-red-50 transition-all">
                                    <span class="material-symbols-outlined text-gray-400 text-xl mb-1">upload_file</span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider" id="filename-{{ $pid }}">
                                        {{ $hasFile ? 'Ganti Bukti Baru' : 'Unggah Bukti' }}
                                    </span>
                                <input type="file"
                                    name="files[{{ $pid }}][]"
                                    class="hidden"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    data-pid="{{ $pid }}"
                                    multiple
                                    onchange="updateFileName(this)">
                                </label>
                                @endif
                            </div>
                        </div>
                    </div>

                @endforeach
            @endforeach
        @endforeach
    </div>

    {{-- FOOTER AKSI --}}
    @if($showFooterAksi)
    <div class="sticky bottom-0 z-20 rounded-xl border border-gray-200 bg-white/95 backdrop-blur p-4 shadow-lg flex flex-col sm:flex-row items-center gap-4">
        <div class="flex items-center gap-3 text-sm text-gray-500">
            <span class="material-symbols-outlined text-amber-500">warning</span>
            <div>
                <p class="font-bold text-on-surface text-sm">Validasi Form</p>
                <p class="text-xs text-gray-400" id="footer-belum-text">
                    <span id="footer-count-belum">{{ $belumDiisi }}</span> pertanyaan masih memerlukan perhatian Anda sebelum diserahkan.
                </p>
            </div>
        </div>
        <div class="flex gap-3 ml-auto">
            <button type="button" id="btnDraft"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-lg">save</span>
                SIMPAN SEBAGAI DRAFT
            </button>
            <button type="button" id="btnSubmit"
                class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-red-800 transition-colors shadow-md">
                <span class="material-symbols-outlined text-lg">send</span>
                SUBMIT ASSESSMENT
            </button>
        </div>
    </div>
    @endif

</div>
</form>

<script>
    const levelLabels = {1:'Partial', 2:'Risk Informed', 3:'Repeatable', 4:'Adaptive', 5:'Optimal'};

    let activeStatus  = 'all';
    let activeDomains = new Set();

    const dropdown  = document.getElementById('domainDropdown');
    const chevron   = document.getElementById('domainChevron');
    const btnDomain = document.getElementById('btnDomainDropdown');

    btnDomain.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = !dropdown.classList.contains('hidden');
        dropdown.classList.toggle('hidden', isOpen);
        chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
    });

    document.addEventListener('click', (e) => {
        if (!document.getElementById('domainDropdownWrapper').contains(e.target)) {
            dropdown.classList.add('hidden');
            chevron.style.transform = '';
        }
    });

    document.querySelectorAll('.domain-option').forEach(opt => {
        opt.addEventListener('click', (e) => {
            e.stopPropagation();
            const kode = opt.dataset.domain;

            if (kode === 'all') {
                activeDomains.clear();
                document.querySelectorAll('.domain-option').forEach(o => {
                    document.getElementById(`check-${o.dataset.domain}`)?.classList.add('opacity-0');
                    o.classList.remove('bg-primary/10');
                });
                opt.classList.add('bg-primary/10');
                document.getElementById('check-all').classList.remove('opacity-0');
                document.getElementById('domainBtnLabel').textContent = 'Semua Domain';
            } else {
                document.getElementById('check-all').classList.add('opacity-0');
                document.querySelector('.domain-option[data-domain="all"]').classList.remove('bg-primary/10');

                if (activeDomains.has(kode)) {
                    activeDomains.delete(kode);
                    document.getElementById(`check-${kode}`).classList.add('opacity-0');
                    opt.classList.remove('bg-primary/10');
                } else {
                    activeDomains.add(kode);
                    document.getElementById(`check-${kode}`).classList.remove('opacity-0');
                    opt.classList.add('bg-primary/10');
                }

                if (activeDomains.size === 0) {
                    document.querySelector('.domain-option[data-domain="all"]').classList.add('bg-primary/10');
                    document.getElementById('check-all').classList.remove('opacity-0');
                    document.getElementById('domainBtnLabel').textContent = 'Semua Domain';
                } else {
                    const labels = [...activeDomains].join(', ');
                    document.getElementById('domainBtnLabel').textContent = labels;
                }
            }
            applyFilter();
        });
    });

    document.querySelectorAll('.status-tab').forEach(btn => {
        btn.addEventListener('click', () => {
            activeStatus = btn.dataset.status;
            document.querySelectorAll('.status-tab').forEach(b => {
                b.classList.remove('bg-primary', 'text-white', 'shadow-md');
                b.classList.add('border', 'border-gray-200', 'bg-white', 'text-gray-600');
            });
            btn.classList.add('bg-primary', 'text-white', 'shadow-md');
            btn.classList.remove('border', 'border-gray-200', 'bg-white', 'text-gray-600');
            applyFilter();
        });
    });

    function applyFilter() {
        const noDomainFilter = activeDomains.size === 0;

        document.querySelectorAll('.question-card').forEach(card => {
            const domainMatch = noDomainFilter || activeDomains.has(card.dataset.domain);
            const statusMatch = activeStatus === 'all' || card.dataset.status === activeStatus;
            card.classList.toggle('hidden', !(domainMatch && statusMatch));
        });

        document.querySelectorAll('.domain-section-header').forEach(header => {
            const domainMatch = noDomainFilter || activeDomains.has(header.dataset.domain);
            if (!domainMatch) { header.classList.add('hidden'); return; }
            const hasVisible = Array.from(
                document.querySelectorAll(`.question-card[data-domain="${header.dataset.domain}"]`)
            ).some(c => !c.classList.contains('hidden'));
            header.classList.toggle('hidden', !hasVisible);
        });
    }

    function resetSkor(pid) {
        document.querySelectorAll(`.maturity-btn[data-pid="${pid}"]`).forEach(b => {
            b.classList.remove('bg-primary', 'text-white', 'border-primary', 'shadow-md', 'scale-110');
            b.classList.add('bg-white', 'text-gray-500', 'border-gray-200');
        });

        const scoreInput = document.getElementById(`score-${pid}`);
        if (scoreInput) scoreInput.value = 0;

        const label = document.getElementById(`level-label-${pid}`);
        if (label) label.textContent = 'Belum dipilih';

        const badgeContainer = document.getElementById(`status-badge-${pid}`);
        if (badgeContainer) {
            badgeContainer.innerHTML = `
                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-600">
                    Belum Diisi
                </span>`;
        }

        const card = document.querySelector(`.question-card:has(#score-${pid})`);
        if (card) card.dataset.status = 'belum';

        updateCounters();
        applyFilter();
    }

    document.querySelectorAll('.maturity-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const pid      = btn.dataset.pid;
            const level    = parseInt(btn.dataset.level);
            const current  = parseInt(document.getElementById(`score-${pid}`).value || 0);

            if (current === level) {
                resetSkor(pid);
                return;
            }

            document.querySelectorAll(`.maturity-btn[data-pid="${pid}"]`).forEach(b => {
                b.classList.remove('bg-primary', 'text-white', 'border-primary', 'shadow-md', 'scale-110');
                b.classList.add('bg-white', 'text-gray-500', 'border-gray-200');
            });

            btn.classList.add('bg-primary', 'text-white', 'border-primary', 'shadow-md', 'scale-110');
            btn.classList.remove('bg-white', 'text-gray-500', 'border-gray-200');

            document.getElementById(`score-${pid}`).value = level;
            document.getElementById(`level-label-${pid}`).textContent = levelLabels[level] || '';

            const badgeContainer = document.getElementById(`status-badge-${pid}`);
            if (badgeContainer) {
                badgeContainer.innerHTML = `
                    <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-700">
                        <span class="material-symbols-outlined text-xs">check_circle</span> Selesai
                    </span>`;
            }

            const card = btn.closest('.question-card');
            if (card) card.dataset.status = 'selesai';

            updateCounters();
            applyFilter();
        });
    });

    function updateCounters() {
        const allCards  = document.querySelectorAll('.question-card');
        const total     = allCards.length;
        const selesai   = document.querySelectorAll('.question-card[data-status="selesai"]').length;
        const belum     = total - selesai;
        const pct       = total > 0 ? Math.round((selesai / total) * 100) : 0;

        const elBelum   = document.getElementById('count-belum');
        const elSelesai = document.getElementById('count-selesai');
        if (elBelum)   elBelum.textContent   = belum;
        if (elSelesai) elSelesai.textContent  = selesai;

        const bar      = document.getElementById('progress-bar');
        const pctLabel = document.getElementById('pct-label');
        const progText = document.getElementById('progress-text');
        if (bar)      bar.style.width          = pct + '%';
        if (pctLabel) pctLabel.textContent      = pct + '%';
        if (progText) progText.textContent      = `${selesai} dari ${total} pertanyaan selesai`;

        const footerCount = document.getElementById('footer-count-belum');
        if (footerCount) footerCount.textContent = belum;
    }

    function updateFileName(input) {
        const pid  = input.dataset.pid;
        const span = document.getElementById(`filename-${pid}`);
        if (input.files && input.files.length > 0 && span) {
            span.textContent = input.files.length === 1
                ? input.files[0].name
                : `${input.files.length} file dipilih`;
        }
    }

    document.getElementById('btnDraft')?.addEventListener('click', () => {
        const inp   = document.createElement('input');
        inp.type    = 'hidden';
        inp.name    = 'action';
        inp.value   = 'draft';
        document.getElementById('assessmentForm').appendChild(inp);
        document.getElementById('assessmentForm').submit();
    });

    document.getElementById('btnSubmit')?.addEventListener('click', () => {
        const scores   = document.querySelectorAll('[name^="scores["]');
        const adaDiisi = Array.from(scores).some(i => parseInt(i.value) > 0);

        if (!adaDiisi) {
            alert('Harap isi minimal satu maturity level sebelum submit!');
            return;
        }

        if (confirm('Yakin ingin submit assessment?\nSetelah disubmit, form akan dikunci untuk direview oleh Approver.')) {
            document.getElementById('assessmentForm').submit();
        }
    });
</script>
@endsection