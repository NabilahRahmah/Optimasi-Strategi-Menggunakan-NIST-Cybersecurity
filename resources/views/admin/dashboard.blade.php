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
            <h1 class="text-3xl font-bold tracking-tight text-slate-800">Overview — Admin</h1>
            <p class="text-sm text-gray-500 mt-2">Kelola konfigurasi checklist dan dokumen pendukung sistem.</p>
        </div>
        <div class="flex items-center bg-amber-50 border border-amber-100 rounded-full px-4 py-1.5 shadow-sm">
            <span class="material-symbols-outlined text-amber-600 text-sm mr-2" style="font-variation-settings: 'FILL' 1;">calendar_month</span>
            <span class="text-xs font-bold text-amber-800">Q2 2026 Period</span>
        </div>
    </div>

    {{-- Metric Cards (Gaya Enterprise) --}}
    <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">dns</span>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Domain Aktif</p>
            </div>
            <div class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ $total_domain ?? 0 }}</div>
            <p class="mt-2 text-xs text-gray-500">Domain NIST CSF terdaftar</p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-blue-500"></div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-lg group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">fact_check</span>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Item Checklist</p>
            </div>
            <div class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ $total_checklist ?? 0 }}</div>
            <p class="mt-2 text-xs text-gray-500">Total item checklist aktif</p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-amber-500"></div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">folder_copy</span>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Kategori Dokumen</p>
            </div>
            <div class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ $total_kategori ?? 0 }}</div>
            <p class="mt-2 text-xs text-gray-500">Kategori dokumen pendukung</p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-emerald-500"></div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-red-50 text-primary rounded-lg group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">schema</span>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-primary">Total Framework</p>
            </div>
            <div class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ $total_framework ?? 0 }}</div>
            <p class="mt-2 text-xs text-gray-500">Framework terdaftar</p>
            <div class="absolute bottom-0 left-0 h-1 w-full bg-primary"></div>
        </div>
    </section>

    {{-- Quick Access Section --}}
    <section class="rounded-2xl border border-red-100 bg-red-50/50 p-6 shadow-sm">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-white text-primary shadow-sm border border-red-100">
                    <span class="material-symbols-outlined text-2xl">admin_panel_settings</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 tracking-tight">Quick Access</h3>
                    <p class="text-xs text-gray-600 mt-1">Aksi cepat untuk konfigurasi sistem <strong>Admin</strong>.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                @if(\Illuminate\Support\Facades\Route::has('admin.checklist.index'))
                    <a href="{{ route('admin.checklist.index') }}" class="inline-flex items-center justify-center rounded-lg bg-primary px-5 py-2.5 text-sm font-bold text-white transition-colors hover:bg-red-800 shadow-md shadow-red-200">
                        Kelola Checklist
                        <span class="material-symbols-outlined text-sm ml-2">arrow_forward</span>
                    </a>
                @endif
                @if(\Illuminate\Support\Facades\Route::has('admin.dokpendukung.index'))
                    <a href="{{ route('admin.dokpendukung.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-50 shadow-sm">
                        <span class="material-symbols-outlined text-sm mr-2">folder_managed</span>
                        Atur Kategori Dokumen
                    </a>
                @endif
            </div>
        </div>
    </section>

</div>
@endsection