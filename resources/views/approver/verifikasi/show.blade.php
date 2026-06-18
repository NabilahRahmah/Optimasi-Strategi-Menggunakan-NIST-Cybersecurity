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
                <p class="text-2xl font-bold text-foreground">{{ $total }}</p>
                <p class="text-xs text-muted-foreground">Total Jawaban</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">{{ $disetujui }}</p>
                <p class="text-xs text-muted-foreground">Disetujui</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-red-600">{{ $ditolak }}</p>
                <p class="text-xs text-muted-foreground">Ditolak</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-yellow-600">{{ $pending }}</p>
                <p class="text-xs text-muted-foreground">Pending</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $pct }}%</p>
                <p class="text-xs text-muted-foreground">Progress</p>
            </div>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-2">
            <div class="bg-primary h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
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
                                    @if($jawaban->file_bukti)
                                        <a href="{{ Storage::url($jawaban->file_bukti) }}" target="_blank"
                                           class="text-xs text-blue-600 hover:underline font-medium">
                                            📄 Lihat
                                        </a>
                                    @else
                                        <span class="text-xs text-muted-foreground italic">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center">
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
                                        <form action="{{ route('approver.verifikasi.item', $jawaban->jawaban_id) }}"
                                              method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit"
                                                    class="text-xs font-semibold text-green-700 border border-green-200 px-2.5 py-1 rounded-lg hover:bg-green-50 transition
                                                    {{ $jawaban->status_verifikasi === 'disetujui' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    {{ $jawaban->status_verifikasi === 'disetujui' ? 'disabled' : '' }}>
                                                ✓ Setuju
                                            </button>
                                        </form>

                                        {{-- Tolak --}}
                                        <button type="button"
                                                onclick="openTolakModal({{ $jawaban->jawaban_id }})"
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
function openTolakModal(jawabanId) {
    const modal = document.getElementById('tolakModal');
    const form  = document.getElementById('tolakForm');
    form.action = `/approver/verifikasi/${jawabanId}/item`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeTolakModal() {
    const modal = document.getElementById('tolakModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endsection