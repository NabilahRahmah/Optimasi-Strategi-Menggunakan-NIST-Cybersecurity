@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-on-surface">Revisi Jawaban</h1>
            <p class="text-sm text-gray-500 mt-1">
                Perbaiki jawaban yang ditolak oleh Approver sebelum disubmit ulang.
            </p>
        </div>
        <a href="{{ route('user.hasil.show', $assessment->assessment_id) }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 border border-gray-200 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
            <span class="material-symbols-outlined text-base">arrow_back</span> Kembali
        </a>
    </div>

    {{-- Info banner --}}
    <div class="rounded-xl border border-red-200 bg-red-50 p-4 flex gap-3">
        <span class="material-symbols-outlined text-red-500 text-xl shrink-0">warning</span>
        <div>
            <p class="text-sm font-bold text-red-700">Jawaban berikut ditolak oleh Approver</p>
            <p class="text-xs text-red-600 mt-0.5">Perbaiki nilai dan/atau unggah bukti baru, lalu simpan revisi.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            ✅ {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('assessment.simpanRevisi', $assessment->assessment_id) }}"
          method="POST" enctype="multipart/form-data">
        @csrf

        <div class="space-y-6">
            @forelse($grouped as $domainName => $byKategori)
                @foreach($byKategori as $kategoriName => $jawabans)

                    {{-- Subheading --}}
                    <div class="flex items-center gap-3 mt-4 mb-2">
                        <span class="text-xs font-bold text-gray-700">{{ $domainName }} · {{ $kategoriName }}</span>
                        <div class="flex-1 h-px bg-gray-100"></div>
                    </div>

                    @foreach($jawabans as $jawaban)
                        @php
                            $pid = $jawaban->pertanyaan_id;
                        @endphp
                        <div class="rounded-xl border-2 border-red-200 bg-white shadow-sm p-6">
                            <div class="flex gap-6">

                                {{-- Kiri --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold bg-gray-100 text-gray-600 font-mono">
                                            {{ $jawaban->pertanyaan->kode_pertanyaan }}
                                        </span>
                                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-bold bg-red-100 text-red-600">
                                            ✗ Ditolak
                                        </span>
                                    </div>

                                    <h3 class="text-base font-bold text-on-surface mb-1">
                                        {{ $jawaban->pertanyaan->judul }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mb-3">
                                        {{ $jawaban->pertanyaan->deskripsi }}
                                    </p>

                                    {{-- Komentar approver --}}
                                    @if($jawaban->komentar_approver)
                                        <div class="rounded-lg border border-orange-200 bg-orange-50 p-3 mb-4 flex gap-2">
                                            <span class="material-symbols-outlined text-orange-500 text-sm shrink-0 mt-0.5">chat</span>
                                            <div>
                                                <p class="text-[10px] font-bold uppercase text-orange-600 mb-0.5">Komentar Approver</p>
                                                <p class="text-xs text-orange-700">{{ $jawaban->komentar_approver }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Pilih skor baru --}}
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2">
                                            Nilai Sebelumnya: <span class="text-red-600">{{ $jawaban->indeks_nilai ?? '-' }}</span>
                                            · Pilih Nilai Baru:
                                        </p>
                                        <div class="flex items-center gap-2">
                                            @for($level = 1; $level <= 5; $level++)
                                                <button type="button"
                                                    class="maturity-btn w-10 h-10 rounded-lg border text-sm font-bold transition-all
                                                        {{ $jawaban->indeks_nilai == $level
                                                            ? 'bg-primary text-white border-primary shadow-md scale-110'
                                                            : 'bg-white text-gray-500 border-gray-200 hover:border-primary hover:text-primary' }}"
                                                    data-level="{{ $level }}"
                                                    data-pid="{{ $pid }}">
                                                    {{ $level }}
                                                </button>
                                            @endfor

                                            <input type="hidden"
                                                name="scores[{{ $pid }}]"
                                                id="score-{{ $pid }}"
                                                value="{{ $jawaban->indeks_nilai }}">

                                            <span class="ml-2 text-xs text-gray-400" id="level-label-{{ $pid }}">
                                                @php $ll = [1=>'Partial',2=>'Risk Informed',3=>'Repeatable',4=>'Adaptive',5=>'Optimal']; @endphp
                                                {{ $ll[$jawaban->indeks_nilai] ?? 'Belum dipilih' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Kanan — Upload --}}
                                <div class="w-52 shrink-0">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2">
                                        Dokumen Bukti Baru
                                    </p>
                                    @if($jawaban->file_bukti)
                                        <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 p-2 mb-2">
                                            <span class="material-symbols-outlined text-red-500 text-lg">picture_as_pdf</span>
                                            <span class="text-xs text-gray-600 truncate flex-1">{{ $jawaban->nama_file_asli ?? 'Bukti lama' }}</span>
                                        </div>
                                    @endif
                                    <label class="flex flex-col items-center justify-center w-full h-20 rounded-lg border-2 border-dashed border-red-200 bg-red-50 cursor-pointer hover:border-primary transition-all">
                                        <span class="material-symbols-outlined text-red-400 text-xl mb-1">upload_file</span>
                                        <span class="text-[10px] font-bold text-red-400 uppercase" id="filename-{{ $pid }}">
                                            Ganti Bukti
                                        </span>
                                        <input type="file"
                                            name="files[{{ $pid }}]"
                                            class="hidden"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            data-pid="{{ $pid }}"
                                            onchange="updateFileName(this)">
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            @empty
                <div class="rounded-xl border bg-green-50 p-8 text-center">
                    <span class="material-symbols-outlined text-green-500 text-4xl">check_circle</span>
                    <p class="text-sm font-bold text-green-700 mt-2">Tidak ada jawaban yang perlu direvisi!</p>
                </div>
            @endforelse

            {{-- Tombol Simpan --}}
            @if($grouped->count() > 0)
            <div class="sticky bottom-0 z-20 rounded-xl border border-gray-200 bg-white/95 backdrop-blur p-4 shadow-lg flex items-center justify-between gap-4">
                <p class="text-xs text-gray-500">
                    <span class="font-bold text-red-600">{{ $assessment->jawabans->count() }}</span> jawaban perlu diperbaiki
                </p>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-6 py-2.5 text-sm font-bold text-white hover:bg-red-800 transition shadow-md">
                    <span class="material-symbols-outlined text-lg">send</span>
                    Simpan Revisi
                </button>
            </div>
            @endif
        </div>
    </form>
</div>

<script>
    const levelLabels = {1:'Partial',2:'Risk Informed',3:'Repeatable',4:'Adaptive',5:'Optimal'};

    document.querySelectorAll('.maturity-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const pid   = btn.dataset.pid;
            const level = parseInt(btn.dataset.level);

            document.querySelectorAll(`.maturity-btn[data-pid="${pid}"]`).forEach(b => {
                b.classList.remove('bg-primary','text-white','border-primary','shadow-md','scale-110');
                b.classList.add('bg-white','text-gray-500','border-gray-200');
            });

            btn.classList.add('bg-primary','text-white','border-primary','shadow-md','scale-110');
            btn.classList.remove('bg-white','text-gray-500','border-gray-200');

            document.getElementById(`score-${pid}`).value = level;
            document.getElementById(`level-label-${pid}`).textContent = levelLabels[level] || '';
        });
    });

    function updateFileName(input) {
        const pid  = input.dataset.pid;
        const span = document.getElementById(`filename-${pid}`);
        if (input.files && input.files[0]) span.textContent = input.files[0].name;
    }
</script>
@endsection