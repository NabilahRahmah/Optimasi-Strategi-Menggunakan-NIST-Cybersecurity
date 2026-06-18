@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">Framework Catalog</h1>
                <p class="text-sm text-muted-foreground">Kelola master data framework audit, domain, dan kategori
                    pertanyaan.</p>
            </div>
            <a href="{{ route('superadmin.frameworks.create') }}"
                class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    class="mr-2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Framework
            </a>
        </div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tabel --}}
        <div class="rounded-xl border bg-card text-card-foreground shadow-sm overflow-hidden">
            <div class="flex flex-col gap-4 p-6 md:flex-row md:items-center md:justify-between border-b">
                <div class="relative w-full max-w-sm">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="search"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-9 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        placeholder="Cari framework...">
                </div>
                <p class="text-xs text-muted-foreground tracking-tight">Total: {{ $frameworks->count() }} Data</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="border-b bg-muted/50">
                            <th
                                class="h-12 px-6 text-left align-middle font-medium text-muted-foreground uppercase tracking-wider text-[10px]">
                                No</th>
                            <th
                                class="h-12 px-6 text-left align-middle font-medium text-muted-foreground uppercase tracking-wider text-[10px]">
                                Nama Framework</th>
                            <th
                                class="h-12 px-6 text-left align-middle font-medium text-muted-foreground uppercase tracking-wider text-[10px]">
                                Jumlah Domain</th>
                            <th
                                class="h-12 px-6 text-left align-middle font-medium text-muted-foreground uppercase tracking-wider text-[10px]">
                                PIC</th>
                            <th
                                class="h-12 px-6 text-left align-middle font-medium text-muted-foreground uppercase tracking-wider text-[10px]">
                                Status</th>
                            <th
                                class="h-12 px-6 text-right align-middle font-medium text-muted-foreground uppercase tracking-wider text-[10px]">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="[&_tr:last-child]:border-0">
                        @forelse($frameworks as $index => $fw)
                            <tr class="border-b transition-colors hover:bg-muted/50">
                                <td class="p-6 align-middle font-mono text-xs text-muted-foreground">
                                    {{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}
                                </td>

                                <td class="p-6 align-middle">
                                    <div class="font-semibold text-foreground">{{ $fw->name_framework }}</div>
                                    <div class="text-xs text-muted-foreground">{{ Str::limit($fw->description, 60) }}</div>
                                </td>

                                {{-- Jumlah Domain dari relasi --}}
                                <td class="p-6 align-middle">
                                    <span
                                        class="inline-flex items-center rounded-md bg-secondary px-2 py-1 text-xs font-medium text-secondary-foreground">
                                        {{ $fw->domains_count ?? $fw->domains->count() }} Domain
                                    </span>
                                </td>

                                {{-- PIC / User Bertanggung Jawab --}}
                                <td class="p-6 align-middle text-sm text-foreground">
                                    {{ $fw->picUser->name ?? '-' }}
                                </td>

                                {{-- Status --}}
                                <td class="p-6 align-middle">
                                    @if($fw->is_active)
                                        <span
                                            class="inline-flex items-center rounded-full border border-green-200 bg-green-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-green-700">Active</span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full border border-gray-200 bg-gray-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-gray-500">Inactive</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="p-6 align-middle text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('superadmin.frameworks.edit', $fw->framework_id) }}"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-input bg-background transition-colors hover:bg-accent hover:text-accent-foreground"
                                            title="Edit">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('superadmin.frameworks.destroy', $fw->framework_id) }}"
                                            method="POST" onsubmit="return confirm('Yakin mau hapus framework ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-input bg-background text-destructive transition-colors hover:bg-destructive hover:text-destructive-foreground"
                                                title="Hapus">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center align-middle">
                                    <div class="flex flex-col items-center justify-center opacity-50">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.5" class="mb-4">
                                            <path
                                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                            </path>
                                        </svg>
                                        <p class="text-sm font-medium">Belum ada data Framework.</p>
                                        <p class="text-xs">Klik "Tambah Framework" untuk memulai.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection