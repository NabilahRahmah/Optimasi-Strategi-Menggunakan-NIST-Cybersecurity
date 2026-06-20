@extends('layouts.dashboard')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    {{-- Breadcrumb & Top Section --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-2">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-3">
                <span>Platform</span>
                <span class="text-gray-300">/</span>
                <span class="text-primary">Overview Dashboard</span>
            </nav>
            <h1 class="text-3xl font-bold tracking-tight text-slate-800">Overview — {{ auth()->user()->name ?? 'Karyawan' }}</h1>
            <p class="text-sm text-gray-500 mt-2">Pantau progres self assessment dan dokumen bukti audit Anda.</p>
        </div>
        <div class="flex items-center bg-amber-50 border border-amber-100 rounded-full px-4 py-1.5 shadow-sm">
            <span class="material-symbols-outlined text-amber-600 text-sm mr-2" style="font-variation-settings: 'FILL' 1;">calendar_month</span>
            <span class="text-xs font-bold text-amber-800">Q2 2026 Period</span>
        </div>
    </div>

    {{-- Metric Cards (Gaya Enterprise + Logic Lu) --}}
    <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        
        {{-- Card 1 --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">assignment</span>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Assessment Aktif</p>
            </div>
            <div class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ $total_assessment ?? 0 }}</div>
            <p class="mt-2 text-xs text-gray-500">Assessment yang berlangsung</p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-blue-500"></div>
        </div>

        {{-- Card 2 --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-lg group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">hourglass_empty</span>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Menunggu Verifikasi</p>
            </div>
            <div class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ $menunggu_verifikasi ?? 0 }}</div>
            <p class="mt-2 text-xs text-gray-500">Sudah disubmit, menunggu approval</p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-amber-500"></div>
        </div>

        {{-- Card 3 --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">speed</span>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Skor Rata-Rata</p>
            </div>
            <div class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ isset($skor_rata) ? number_format($skor_rata, 1) : '0.0' }}</div>
            <p class="mt-2 text-xs text-gray-500">Rata-rata nilai assessment Anda</p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-emerald-500"></div>
        </div>

        {{-- Card 4 --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-red-50 text-primary rounded-lg group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">edit_note</span>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-primary">Revisi Terbuka</p>
            </div>
            <div class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ $revisi_terbuka ?? 0 }}</div>
            <p class="mt-2 text-xs text-gray-500">Assessment yang perlu diperbaiki</p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-primary"></div>
        </div>

    </section>

    {{-- Assessment Saya Section --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-100 p-6 bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-xl">folder_open</span>
                    Assessment Saya
                </h3>
                <p class="text-xs text-gray-500 mt-1">5 assessment terakhir yang Anda buat.</p>
            </div>
            @if(\Illuminate\Support\Facades\Route::has('user.assessment.index'))
                <a href="{{ route('user.assessment.index') }}" class="text-xs font-bold text-primary hover:text-red-800 flex items-center gap-1 transition-colors">
                    Lihat Semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            @endif
        </div>
        
        <div class="p-6">
            @if(empty($my_assessments) || $my_assessments->isEmpty())
                <div class="text-center py-12 flex flex-col items-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100">
                        <span class="material-symbols-outlined text-gray-300 text-3xl">post_add</span>
                    </div>
                    <p class="text-sm font-bold text-gray-600">Belum ada assessment.</p>
                    <p class="text-xs text-gray-400 mt-1 mb-4">Mulai lakukan self assessment pertama Anda.</p>
                    @if(\Illuminate\Support\Facades\Route::has('user.assessment.index'))
                        <a href="{{ route('user.assessment.index') }}" class="px-4 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-red-800 transition-colors shadow-sm">
                            Mulai Assessment
                        </a>
                    @endif
                </div>
            @else
                <div class="space-y-3">
                    @foreach($my_assessments as $assessment)
                        <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-white p-4 hover:border-red-100 hover:shadow-sm transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-red-50 group-hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-sm">description</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $assessment->framework->jenisFramework ?? 'Assessment Baru' }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        <span class="font-semibold text-gray-600">Tanggal:</span> 
                                        {{ $assessment->tglPelaksanaan ? $assessment->tglPelaksanaan->format('d M Y') : 'Belum diset' }}
                                    </p>
                                </div>
                            </div>
                            
                            {{-- Logic Status Colors --}}
                            @php
                                $status = $assessment->status ?? 'pending';
                                $statusClass = match($status) {
                                    'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'submitted' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'assessed'  => 'bg-amber-100 text-amber-800 border-amber-200',
                                    default     => 'bg-gray-100 text-gray-800 border-gray-200',
                                };
                            @endphp
                            
                            <span class="inline-flex items-center rounded-full border px-3 py-1 text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">
                                {{ $status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection