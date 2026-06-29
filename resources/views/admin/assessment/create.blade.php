@extends('layouts.dashboard')
@section('content')
    <div class="space-y-6">

        <nav class="flex items-center gap-2 text-sm text-muted-foreground">
            <span>Platform</span><span>/</span>
            <a href="{{ route('admin.assessment.index') }}" class="hover:text-foreground">Assessment</a>
            <span>/</span>
            <span class="font-medium text-foreground">Tambah Kategori</span>
        </nav>

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">Tambah Kategori</h1>
                <p class="text-sm text-muted-foreground">Buat kategori pertanyaan baru untuk form audit.</p>
            </div>
            <a href="{{ route('admin.assessment.edit', $frameworkId) }}"
                class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium transition-colors hover:bg-accent">
                ← Kembali
            </a>
        </div>

        <div class="rounded-xl border bg-card shadow-sm overflow-hidden max-w-2xl">
            <div class="p-6 border-b bg-muted/20">
                <h3 class="text-base font-semibold text-foreground">Data Kategori</h3>
                <p class="text-xs text-muted-foreground mt-1">Field bertanda * wajib diisi.</p>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.assessment.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">Domain <span
                                class="text-destructive">*</span></label>

                        @if($domainId)
                            @php
                                $selectedDomain = collect($domains)->firstWhere('domain_id', $domainId);
                            @endphp
                            <input type="hidden" name="domain_id" id="domain-select" value="{{ $domainId }}">
                            <div class="flex h-10 w-full items-center rounded-md border border-input bg-muted px-3 py-2 text-sm text-muted-foreground cursor-not-allowed opacity-80">
                                {{ $selectedDomain ? $selectedDomain->kode_domain . ' — ' . $selectedDomain->nama_domain : 'Domain Terpilih' }}
                            </div>
                        @else
                            {{-- Hidden select asli, tetap dikirim ke server --}}
                            <select name="domain_id" id="domain-select" class="hidden">
                                <option value="">-- Pilih Domain --</option>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->domain_id }}" {{ old('domain_id') == $domain->domain_id ? 'selected' : '' }}>
                                        {{ $domain->kode_domain }} — {{ $domain->nama_domain }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Custom dropdown --}}
                            <div class="relative" id="domain-dropdown">
                                <button type="button" id="domain-dropdown-btn"
                                    class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring @error('domain_id') border-destructive @enderror">
                                    <span id="domain-dropdown-label" class="text-muted-foreground">-- Pilih Domain --</span>
                                    <svg class="h-4 w-4 opacity-50" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M6 9l6 6 6-6" />
                                    </svg>
                                </button>

                                <div id="domain-dropdown-panel"
                                    class="hidden absolute z-50 mt-1 w-full rounded-md border border-input bg-white shadow-lg">
                                    <div class="p-2 border-b bg-white">
                                        <input type="text" id="domain-search"
                                            placeholder="Cari domain..."
                                            class="w-full h-8 rounded-md border border-input bg-white px-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                    </div>
                                    <ul id="domain-options" class="max-h-60 overflow-y-auto py-1 text-sm bg-white">
                                    {{-- diisi via JS --}}
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @error('domain_id')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">Kode Kategori <span
                                class="text-destructive">*</span></label>
                        <input type="text" name="kode_kategori" value="{{ old('kode_kategori') }}"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm font-mono ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring @error('kode_kategori') border-destructive @enderror"
                            placeholder="Contoh: GV.OC-01">
                        <p class="text-[10px] text-muted-foreground">Format: [KodeDomain].[SubDomain]-[Nomor] — contoh:
                            GV.OC-01</p>
                        @error('kode_kategori')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">Nama Kategori <span
                                class="text-destructive">*</span></label>
                        <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring @error('nama_kategori') border-destructive @enderror"
                            placeholder="Contoh: Kebijakan Keamanan Informasi">
                        @error('nama_kategori')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">Deskripsi</label>
                        <textarea name="deskripsi" rows="4"
                            class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            placeholder="Deskripsi singkat kategori ini...">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t">
                        <a href="{{ route('admin.assessment.edit', $frameworkId) }}"
                            class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium transition-colors hover:bg-accent">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-primary/90">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('domain-select');
        const btn = document.getElementById('domain-dropdown-btn');
        const panel = document.getElementById('domain-dropdown-panel');
        const search = document.getElementById('domain-search');
        const optionsList = document.getElementById('domain-options');
        const label = document.getElementById('domain-dropdown-label');

        const options = Array.from(select.options).map(opt => ({
            value: opt.value,
            text: opt.textContent.trim(),
            selected: opt.selected
        }));

        function renderOptions(filter = '') {
            optionsList.innerHTML = '';
            const filtered = options.filter(o =>
                o.text.toLowerCase().includes(filter.toLowerCase()) || o.value === ''
            );

            if (filtered.length === 0) {
                optionsList.innerHTML = '<li class="px-3 py-2 text-muted-foreground">Tidak ditemukan</li>';
                return;
            }

            filtered.forEach(opt => {
                if (opt.value === '') return;
                const li = document.createElement('li');
                li.textContent = opt.text;
                li.dataset.value = opt.value;
                li.className = 'px-3 py-2 cursor-pointer hover:bg-accent hover:text-accent-foreground' +
                    (opt.value === select.value ? ' bg-accent text-accent-foreground' : '');
                li.addEventListener('click', () => {
                    select.value = opt.value;
                    label.textContent = opt.text;
                    label.classList.remove('text-muted-foreground');
                    closePanel();
                });
                optionsList.appendChild(li);
            });
        }

        function openPanel() {
            panel.classList.remove('hidden');
            search.value = '';
            renderOptions();
            search.focus();
        }

        function closePanel() {
            panel.classList.add('hidden');
        }

        btn.addEventListener('click', () => {
            panel.classList.contains('hidden') ? openPanel() : closePanel();
        });

        search.addEventListener('input', (e) => renderOptions(e.target.value));

        document.addEventListener('click', (e) => {
            if (!document.getElementById('domain-dropdown').contains(e.target)) {
                closePanel();
            }
        });

    // Set label awal kalau ada domain yang sudah ter-pilih (dari old input)
        const selectedOpt = options.find(o => o.selected && o.value !== '');
        if (selectedOpt) {
            label.textContent = selectedOpt.text;
            label.classList.remove('text-muted-foreground');
        }
    });
    </script>

@endsection