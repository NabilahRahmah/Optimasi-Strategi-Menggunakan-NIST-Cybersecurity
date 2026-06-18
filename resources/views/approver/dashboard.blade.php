@extends('layouts.dashboard')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-2">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-3">
                <span>Platform</span>
                <span class="text-gray-300">/</span>
                <span class="text-primary">Overview Dashboard</span>
            </nav>
            <h1 class="text-3xl font-bold tracking-tight text-slate-800">Overview — Approver</h1>
            <p class="text-sm text-gray-500 mt-2">Pantau antrian verifikasi dan kelola rekomendasi perbaikan.</p>
        </div>
        <div class="flex items-center bg-amber-50 border border-amber-100 rounded-full px-4 py-1.5 shadow-sm">
            <span class="material-symbols-outlined text-amber-600 text-sm mr-2" style="font-variation-settings: 'FILL' 1;">calendar_month</span>
            <span class="text-xs font-bold text-amber-800">Q2 2026 Period</span>
        </div>
    </div>

    <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">pending_actions</span>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Queue Review</p>
            </div>
            <div class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ $queue_review ?? 0 }}</div>
            <p class="mt-2 text-xs text-gray-500">Assessment masuk & menunggu</p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-blue-500"></div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-lg group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">gavel</span>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Perlu Keputusan</p>
            </div>
            <div class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ $perlu_keputusan ?? 0 }}</div>
            <p class="mt-2 text-xs text-gray-500">Belum ada keputusan</p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-amber-500"></div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">verified</span>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Disetujui</p>
            </div>
            <div class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ $disetujui ?? 0 }}</div>
            <p class="mt-2 text-xs text-gray-500">Total assessment disetujui</p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-emerald-500"></div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-red-50 text-primary rounded-lg group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">assignment_late</span>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-primary">Rekomendasi Terbuka</p>
            </div>
            <div class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ $rekomendasi_terbuka ?? 0 }}</div>
            <p class="mt-2 text-xs text-gray-500">Belum ditindaklanjuti</p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-primary"></div>
        </div>
    </section>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-100 p-6 bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500 text-xl">rule_folder</span>
                    Assessment Menunggu Verifikasi
                </h3>
                <p class="text-xs text-gray-500 mt-1">Daftar assessment yang perlu ditindaklanjuti segera.</p>
            </div>
            @if(\Illuminate\Support\Facades\Route::has('approver.verifikasi.index'))
                <a href="{{ route('approver.verifikasi.index') }}" class="text-xs font-bold text-primary hover:text-red-800 flex items-center gap-1 transition-colors">
                    Lihat Semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            @endif
        </div>
        
        <div class="p-6">
            @if(empty($pending_assessments) || $pending_assessments->isEmpty())
                <div class="text-center py-10 flex flex-col items-center">
                    <span class="material-symbols-outlined text-emerald-300 text-5xl mb-3">task_alt</span>
                    <p class="text-sm font-bold text-slate-700">Antrian Kosong</p>
                    <p class="text-xs text-gray-500 mt-1">Tidak ada assessment yang menunggu verifikasi saat ini.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($pending_assessments as $assessment)
                        <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white p-4 hover:border-amber-100 hover:shadow-sm transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-100 transition-colors">
                                    <span class="material-symbols-outlined text-sm">person</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $assessment->user->name ?? 'User Unknown' }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        <span class="font-semibold text-gray-600">{{ $assessment->framework->jenisFramework ?? '-' }}</span> • 
                                        {{ $assessment->tglPelaksanaan ? $assessment->tglPelaksanaan->format('d M Y') : '-' }}
                                    </p>
                                </div>
                            </div>
                            
                            <span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-100 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-blue-800">
                                Submitted
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection