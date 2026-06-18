@extends('layouts.dashboard')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-2">
            <div>
                <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-3">
                    <span>Platform</span>
                    <span class="text-gray-300">/</span>
                    <span class="text-primary">Overview Dashboard</span>
                </nav>
                <h1 class="text-3xl font-bold tracking-tight text-slate-800">Overview — Super Admin</h1>
                <p class="text-sm text-gray-500 mt-2">Pantau dan kelola seluruh konfigurasi sistem CyberAudit.</p>
            </div>
            <div class="flex items-center bg-amber-50 border border-amber-100 rounded-full px-4 py-1.5 shadow-sm">
                <span class="material-symbols-outlined text-amber-600 text-sm mr-2"
                    style="font-variation-settings: 'FILL' 1;">calendar_month</span>
                <span class="text-xs font-bold text-amber-800">Q2 2026 Period</span>
            </div>
        </div>

        {{-- Metric Cards --}}
        <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

            {{-- Total User --}}
            <div
                class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-lg group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined">group</span>
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Total Karyawan</p>
                </div>
                <div class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ $total_user ?? 0 }}</div>
                <p class="mt-2 text-xs text-gray-500">Pengguna terdaftar di sistem</p>
                <div class="absolute bottom-0 left-0 h-1 w-full bg-blue-500"></div>
            </div>

            {{-- Total Framework --}}
            <div
                class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-red-50 text-primary rounded-lg group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined">schema</span>
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-primary">Total Framework</p>
                </div>
                <div class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ $total_framework ?? 0 }}</div>
                <p class="mt-2 text-xs text-gray-500">Framework audit terdaftar</p>
                <div class="absolute bottom-0 left-0 h-1 w-full bg-primary"></div>
            </div>

            {{-- Total Assessment --}}
            <div
                class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined">bar_chart</span>
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Total Assessment</p>
                </div>
                <div class="mt-2 text-4xl font-black tracking-tight text-slate-800">{{ $total_assessment ?? 0 }}</div>
                <p class="mt-2 text-xs text-gray-500">Seluruh assessment di sistem</p>
                <div class="absolute bottom-0 left-0 h-1 w-full bg-emerald-500"></div>
            </div>

        </section>

        {{-- Quick Access --}}
        <section class="rounded-2xl border border-red-100 bg-red-50/50 p-6 shadow-sm">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-white text-primary shadow-sm border border-red-100">
                        <span class="material-symbols-outlined text-2xl">admin_panel_settings</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 tracking-tight">Quick Access</h3>
                        <p class="text-xs text-gray-600 mt-1">Aksi cepat untuk konfigurasi sistem <strong>Super
                                Admin</strong>.</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    @if(\Illuminate\Support\Facades\Route::has('superadmin.users.index'))
                        <a href="{{ route('superadmin.users.index') }}"
                            class="inline-flex items-center justify-center rounded-lg bg-primary px-5 py-2.5 text-sm font-bold text-white transition-colors hover:bg-red-800 shadow-md shadow-red-200">
                            Kelola Karyawan
                            <span class="material-symbols-outlined text-sm ml-2">arrow_forward</span>
                        </a>
                    @endif
                    @if(\Illuminate\Support\Facades\Route::has('superadmin.frameworks.index'))
                        <a href="{{ route('superadmin.frameworks.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-bold text-gray-700 transition-colors hover:bg-gray-50 shadow-sm">
                            <span class="material-symbols-outlined text-sm mr-2">inventory_2</span>
                            Kelola Framework
                        </a>
                    @endif
                </div>
            </div>
        </section>

    </div>
@endsection