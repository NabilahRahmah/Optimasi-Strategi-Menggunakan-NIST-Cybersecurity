@extends('layouts.dashboard')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- BREADCRUMB & HEADER --}}
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-2 text-xs text-gray-400 uppercase tracking-wider font-semibold">
                <span>Verifikasi</span>
                <span>/</span>
                <span class="text-red-700 font-bold">Assessment Disetujui</span>
            </div>
            <h1 class="text-3xl font-black text-stone-900 tracking-tight">Status Hasil Verifikasi</h1>
        </div>

        {{-- HERO CARD STATE disetujui (Merah, Hitam, Putih) --}}
        <div class="bg-stone-950 text-white rounded-2xl border border-stone-800 p-8 shadow-xl relative overflow-hidden">
            {{-- Dekorasi Background Gak Lebay --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-red-700/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-6 relative z-10">
                <div class="flex items-center gap-5">
                    <div
                        class="w-16 h-16 rounded-full bg-red-700/20 border border-red-600 flex items-center justify-center shrink-0 shadow-lg shadow-red-900/20">
                        <span class="material-symbols-outlined text-red-500 text-3xl font-bold">verified_user</span>
                    </div>
                    <div>
                        <div
                            class="inline-flex items-center gap-1.5 rounded-full bg-red-900/50 px-3 py-1 text-xs font-black text-red-400 border border-red-800 uppercase tracking-widest mb-2">
                            Status: DISETUJUI
                        </div>
                        <h2 class="text-2xl font-black tracking-tight">
                            {{ $assessment->judul_assessment ?? 'Self Assessment Keamanan Informasi' }}</h2>
                        <p class="text-sm text-stone-400 mt-1">
                            Dokumen dan bukti pemenuhan kontrol NIST CSF 2.0 telah diperiksa dan **Disetujui** oleh pihak
                            Approver.
                        </p>
                    </div>
                </div>

                {{-- SCORE BLOCK --}}
                <div
                    class="w-full md:w-auto bg-stone-900 border border-stone-800 rounded-xl p-4 text-center min-w-[200px] shadow-inner">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-stone-500">Final Maturity Index</p>
                    <p class="text-4xl font-black text-red-500 my-1">
                        {{ isset($nilai_total) ? number_format($nilai_total, 2) : '0.00' }}
                    </p>
                    <p class="text-[10px] text-stone-400 font-medium">Skala Maksimal 5.00</p>
                </div>
            </div>

            {{-- METADATA BAR --}}
            <div class="mt-6 pt-6 border-t border-stone-800 grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs text-stone-400">
                <div>
                    <span class="block text-[10px] uppercase font-bold text-stone-500">Organisasi / Unit</span>
                    <span class="font-semibold text-white">{{ auth()->user()->name ?? 'User Pemohon' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-stone-500">Tanggal Verifikasi</span>
                    <span class="font-semibold text-white">{{ now()->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-stone-500">Framework</span>
                    <span class="font-semibold text-white font-mono">NIST CSF 2.0</span>
                </div>
                <div>
                    <span class="block text-[10px] uppercase font-bold text-stone-500">ID Dokumen</span>
                    <span class="font-semibold text-white font-mono">#ASM-{{ $assessment->assessment_id ?? '000' }}</span>
                </div>
            </div>
        </div>

        {{-- MAIN ACTION BUTTONS --}}
        <div class="flex flex-wrap items-center justify-between gap-4 bg-white p-4 rounded-xl border shadow-sm">
            <div class="flex items-center gap-2 text-sm text-stone-500 font-medium">
                <span class="material-symbols-outlined text-red-600">info</span>
                <span>Gunakan data hasil audit ini sebagai landasan penyusunan strategi mitigasi risiko IT GRC.</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('user.dashboard') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-stone-200 bg-white px-4 py-2 text-xs font-bold text-stone-700 hover:bg-stone-50 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    KEMBALI KE DASHBOARD
                </a>
                @if(isset($assessment))
                    <a href="{{ route('user.hasil.pdf', $assessment->assessment_id) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-700 px-5 py-2 text-xs font-bold text-white hover:bg-red-800 transition-colors shadow-md">
                        <span class="material-symbols-outlined text-sm">download</span>
                        UNDUH LAPORAN RESMI (PDF)
                    </a>
                @endif
            </div>
        </div>

        {{-- FEEDBACK APPROVER BOX --}}
        @if(!empty($assessment->catatan_approver))
            <div class="rounded-xl border border-stone-200 bg-white p-6 shadow-sm">
                <h3 class="font-bold text-stone-800 text-sm mb-2 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-red-600 text-lg">comment</span>
                    Catatan Resmi dari Tim Approver / Auditor:
                </h3>
                <p
                    class="text-sm text-stone-600 bg-stone-50 p-4 rounded-lg border border-dashed border-stone-300 leading-relaxed font-medium">
                    "{{ $assessment->catatan_approver }}"
                </p>
            </div>
        @endif

    </div>
@endsection