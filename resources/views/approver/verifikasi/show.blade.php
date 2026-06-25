@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Detail Verifikasi</h1>
            <p class="text-sm text-muted-foreground mt-1">
                Assessment: <span class="font-semibold text-foreground">{{ $assessment->judul_assessment }}</span>
                &bull; Karyawan: <span class="font-semibold text-foreground">{{ $assessment->user->name }}</span>
            </p>
        </div>
        <a href="{{ route('approver.verifikasi.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800 border border-gray-200 px-4 py-2 rounded-md hover:bg-gray-50 transition">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Kembali
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Progress verifikasi --}}
    @php
        $total     = $assessment->jawabans->count();
        $disetujui = $assessment->jawabans->where('status_verifikasi', 'disetujui')->count();
        $ditolak   = $assessment->jawabans->where('status_verifikasi', 'ditolak')->count();
        $pending   = $assessment->jawabans->where('status_verifikasi', 'pending')->count();
        $pct       = $total > 0 ? round((($disetujui + $ditolak) / $total) * 100) : 0;
    @endphp

    <div class="rounded-xl border bg-card shadow-sm p-5">
        <div class="flex flex-wrap gap-6 mb-3">
            <div class="text-center">
                {{-- Tambahan: data-total --}}
                <p class="text-2xl font-bold text-foreground" data-total="{{ $total }}">{{ $total }}</p>
                <p class="text-xs text-muted-foreground">Total Jawaban</p>
            </div>
            <div class="text-center">
                {{-- Tambahan: data-count-disetujui --}}
                <p class="text-2xl font-bold text-green-600" data-count-disetujui>{{ $disetujui }}</p>
                <p class="text-xs text-muted-foreground">Disetujui</p>
            </div>
            <div class="text-center">
                {{-- Tambahan: data-count-ditolak --}}
                <p class="text-2xl font-bold text-red-600" data-count-ditolak>{{ $ditolak }}</p>
                <p class="text-xs text-muted-foreground">Ditolak</p>
            </div>
            <div class="text-center">
                {{-- Tambahan: data-count-pending --}}
                <p class="text-2xl font-bold text-yellow-600" data-count-pending>{{ $pending }}</p>
                <p class="text-xs text-muted-foreground">Pending</p>
            </div>
            <div class="text-center">
                {{-- Tambahan: data-count-pct --}}
                <p class="text-2xl font-bold text-blue-600" data-count-pct>{{ $pct }}%</p>
                <p class="text-xs text-muted-foreground">Progress</p>
            </div>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-2">
            {{-- Tambahan: data-progress-bar --}}
            <div class="bg-primary h-2 rounded-full transition-all" data-progress-bar style="width: {{ $pct }}%"></div>
        </div>
    </div>

    {{-- Jawaban per domain → kategori --}}
    @foreach($grouped as $domainName => $byKategori)
        <div class="rounded-xl border bg-card shadow-sm overflow-hidden">
            {{-- Domain header --}}
            <div class="px-6 py-3 bg-primary/10 border-b flex items-center gap-3">
                <span class="material-symbols-outlined text-primary">folder</span>
                <p class="font-bold text-foreground text-sm">{{ $domainName }}</p>
            </div>

            @foreach($byKategori as $kategoriName => $jawabans)
                {{-- Kategori sub-header --}}
                <div class="px-6 py-2 bg-muted/20 border-b">
                    <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wide">
                        {{ $kategoriName }}
                    </p>
                </div>

                <table class="w-full text-sm">
                    <thead class="bg-muted/10 border-b">
                        <tr>
                            <th class="px-6 py-2 text-left text-xs text-muted-foreground w-12">No</th>
                            <th class="px-6 py-2 text-left text-xs text-muted-foreground">Pertanyaan</th>
                            <th class="px-6 py-2 text-center text-xs text-muted-foreground w-20">Nilai</th>
                            <th class="px-6 py-2 text-center text-xs text-muted-foreground w-24">Bukti</th>
                            <th class="px-6 py-2 text-center text-xs text-muted-foreground w-28">Status</th>
                            <th class="px-6 py-2 text-center text-xs text-muted-foreground w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($jawabans as $i => $jawaban)
                            <tr class="hover:bg-muted/20 transition-colors">
                                <td class="px-6 py-3 text-muted-foreground text-xs text-center">
                                    {{ $i + 1 }}
                                </td>
                                <td class="px-6 py-3">
                                    <p class="text-xs font-mono text-primary font-semibold mb-0.5">
                                        {{ $jawaban->pertanyaan->kode_pertanyaan ?? '-' }}
                                    </p>
                                    <p class="text-xs text-foreground">
                                        {{ Str::limit($jawaban->pertanyaan->judul ?? '-', 100) }}
                                    </p>
                                    @if($jawaban->komentar_approver)
                                        <p class="text-xs text-orange-600 mt-1 italic">
                                            💬 {{ $jawaban->komentar_approver }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @if(!is_null($jawaban->indeks_nilai))
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold
                                            @if($jawaban->indeks_nilai >= 4) bg-green-100 text-green-700
                                            @elseif($jawaban->indeks_nilai >= 2) bg-yellow-100 text-yellow-700
                                            @else bg-red-100 text-red-700 @endif">
                                            {{ $jawaban->indeks_nilai }}
                                        </span>
                                    @else
                                        <span class="text-muted-foreground text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @php
                                        $files = $jawaban->file_bukti ?? [];
                                        $namas = $jawaban->nama_file_asli ?? [];
                                    @endphp
                                @if(!empty($files))
                                    <div class="flex flex-col gap-1 items-center">
                                    @foreach($files as $i => $path)
                                        <a href="{{ route('bukti.preview', ['jawaban_id' => $jawaban->jawaban_id, 'index' => $i]) }}"
                                        target="_blank"
                                        class="text-xs text-blue-600 hover:underline font-medium">
                                        📎 {{ $namas[$i] ?? 'File ' . ($i + 1) }}
                                        </a>
                                    @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-muted-foreground italic">Tidak ada</span>
                                @endif
                                </td>
                                <td class="px-6 py-3 text-center" id="status-{{ $jawaban->jawaban_id }}">
                                    @if($jawaban->status_verifikasi === 'disetujui')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            ✓ Disetujui
                                        </span>
                                    @elseif($jawaban->status_verifikasi === 'ditolak')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            ✗ Ditolak
                                        </span>
                                     @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                            ⏳ Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex justify-center gap-2">
                                        {{-- Setujui --}}
                                        <button type="button"
                                            onclick="verifikasiItem({{ $jawaban->jawaban_id }}, 'disetujui', this)"
                                            class="text-xs font-semibold text-green-700 border border-green-200 px-2.5 py-1 rounded-lg hover:bg-green-50 transition
                                            {{ $jawaban->status_verifikasi === 'disetujui' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $jawaban->status_verifikasi === 'disetujui' ? 'disabled' : '' }}>
                                            ✓ Setuju
                                        </button>

                                        {{-- Tolak --}}
                                        <button type="button"
                                            onclick="openTolakModal({{ $jawaban->jawaban_id }}, this)"
                                            class="text-xs font-semibold text-red-700 border border-red-200 px-2.5 py-1 rounded-lg hover:bg-red-50 transition
                                            {{ $jawaban->status_verifikasi === 'ditolak' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $jawaban->status_verifikasi === 'ditolak' ? 'disabled' : '' }}>
                                                ✗ Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    @endforeach

</div>

{{-- Modal Tolak dengan Komentar --}}
<div id="tolakModal"
     class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden items-center justify-center"
     onclick="if(event.target===this) closeTolakModal()">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <h3 class="text-lg font-bold text-foreground mb-4">Tolak Jawaban</h3>
        <form id="tolakForm" method="POST" action="">
            @csrf
            <input type="hidden" name="status" value="ditolak">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-foreground mb-1.5">
                    Alasan Penolakan
                    <span class="text-xs font-normal text-muted-foreground ml-1">(opsional)</span>
                </label>
                <textarea name="komentar" rows="3"
                          placeholder="Tuliskan alasan penolakan atau rekomendasi perbaikan..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 resize-none"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeTolakModal()"
                        class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
                    Tolak Jawaban
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let activeTolakBtn = null;

async function verifikasiItem(jawabanId, status, btn, komentar = '') {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    btn.disabled = true;
    btn.textContent = '...';

    try {
        const res = await fetch(`/approver/verifikasi/${jawabanId}/item`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ status, komentar }),
        });
        const data = await res.json();

        if (!res.ok) {
            throw new Error(data.message ?? 'Gagal menyimpan');
        }

        // Update badge status di baris yang sama
        const statusCell = document.getElementById(`status-${jawabanId}`);
        if (status === 'disetujui') {
            statusCell.innerHTML = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">✓ Disetujui</span>`;
            btn.textContent = '✓ Setuju';
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            btn.disabled = true;
        } else {
            statusCell.innerHTML = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">✗ Ditolak</span>`;
            btn.textContent = '✗ Tolak';
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            btn.disabled = true;
        }

        // Update counter progress
        updateProgress();

        if (data.semua_selesai) {
            alert('Semua jawaban sudah diverifikasi! Silakan finalisasi.');
        }

    } catch (e) {
        btn.disabled = false;
        btn.textContent = status === 'disetujui' ? '✓ Setuju' : '✗ Tolak';
        alert('Gagal menyimpan. Coba lagi.');
    }
}

function updateProgress() {
    const total    = parseInt(document.querySelector('[data-total]')?.dataset.total ?? 0);
    const setujui  = document.querySelectorAll('[id^="status-"] .bg-green-100').length;
    const tolak    = document.querySelectorAll('[id^="status-"] .bg-red-100').length;
    const pending  = total - setujui - tolak;
    const pct      = total > 0 ? Math.round(((setujui + tolak) / total) * 100) : 0;

    document.querySelector('[data-count-disetujui]').textContent = setujui;
    document.querySelector('[data-count-ditolak]').textContent   = tolak;
    document.querySelector('[data-count-pending]').textContent   = pending;
    document.querySelector('[data-count-pct]').textContent       = pct + '%';
    document.querySelector('[data-progress-bar]').style.width    = pct + '%';
}

function openTolakModal(jawabanId, btn) {
    activeTolakBtn = btn;
    const modal = document.getElementById('tolakModal');
    modal.dataset.jawabanId = jawabanId;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.querySelector('#tolakForm textarea').value = '';
}

function closeTolakModal() {
    const modal = document.getElementById('tolakModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    activeTolakBtn = null;
}

document.getElementById('tolakForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const modal     = document.getElementById('tolakModal');
    const jawabanId = modal.dataset.jawabanId;
    const komentar  = this.querySelector('textarea[name="komentar"]').value;

    await verifikasiItem(jawabanId, 'ditolak', activeTolakBtn, komentar);
    closeTolakModal();
});
</script>
@endsection