@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-muted-foreground">
        <span>Platform</span>
        <span class="text-border">/</span>
        <a href="{{ route('superadmin.frameworks.index') }}" class="hover:text-foreground">Framework</a>
        <span class="text-border">/</span>
        <span class="font-medium text-foreground">Edit Framework</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Edit Framework</h1>
            <p class="text-sm text-muted-foreground">Perbarui data framework audit.</p>
        </div>
        <a href="{{ route('superadmin.frameworks.index') }}"
            class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground">
            ← Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    {{-- Form Card --}}
    <div class="rounded-xl border bg-card shadow-sm overflow-hidden">
        <div class="p-6 border-b bg-muted/20">
            <h3 class="text-base font-semibold text-foreground tracking-tight">Data Framework</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('superadmin.frameworks.update', $framework->framework_id) }}"
                  method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Nama Framework --}}
                <div class="grid gap-2">
                    <label class="text-sm font-medium leading-none">Nama Framework <span class="text-destructive">*</span></label>
                    <input type="text" name="name_framework"
                        value="{{ old('name_framework', $framework->name_framework) }}" required
                        placeholder="Contoh: NIST Cybersecurity Framework"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring @error('name_framework') border-destructive @enderror">
                    @error('name_framework')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                </div>

                {{-- Deskripsi --}}
                <div class="grid gap-2">
                    <label class="text-sm font-medium leading-none">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="3"
                        placeholder="Tuliskan deskripsi singkat tentang framework ini..."
                        class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">{{ old('description', $framework->description) }}</textarea>
                </div>

                {{-- PIC --}}
                <div class="grid gap-2">
                    <label class="text-sm font-medium leading-none">Penanggung Jawab</label>
                    <select name="pic_user_id"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring">
                        <option value="">— Pilih User —</option>
                        @foreach($users as $user)
                            <option value="{{ $user->user_id }}"
                                {{ old('pic_user_id', $framework->pic_user_id) == $user->user_id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div class="grid gap-2">
                    <label class="text-sm font-medium leading-none">Status</label>
                    <select name="is_active"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring">
                        <option value="1" {{ old('is_active', $framework->is_active) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $framework->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Domain Section --}}
                <div class="grid gap-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm font-medium leading-none">Domain</span>
                            <p class="text-xs text-muted-foreground mt-1">Edit atau tambahkan domain dalam framework ini.</p>
                        </div>
                        <button type="button" id="btn-add-domain"
                            class="inline-flex items-center gap-1.5 rounded-md bg-primary px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-primary/90">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Domain
                        </button>
                    </div>

                    <div id="domain-header" class="hidden grid-cols-[24px_100px_1fr_32px] gap-2 px-1">
                        <span></span>
                        <span class="text-[10px] font-medium uppercase tracking-wider text-muted-foreground">Kode</span>
                        <span class="text-[10px] font-medium uppercase tracking-wider text-muted-foreground">Nama Domain</span>
                        <span></span>
                    </div>

                    <div id="domain-list" class="space-y-2"></div>

                    {{-- Container untuk track domain yang dihapus --}}
                    <div id="deleted-domain-ids"></div>

                    <div id="domain-empty" class="rounded-lg border border-dashed border-border p-4 text-center">
                        <p class="text-xs text-muted-foreground">Belum ada domain. Klik "Domain" untuk menambahkan.</p>
                    </div>
                </div>

                {{-- Jumlah Domain --}}
                <div class="grid gap-1.5">
                    <label class="text-sm font-medium leading-none text-muted-foreground">Jumlah Domain</label>
                    <div class="flex h-10 w-36 items-center rounded-md border border-input bg-muted/30 px-3 text-sm font-semibold text-foreground">
                        <span id="domain-count">0</span>
                        <span class="ml-1 text-muted-foreground font-normal">domain</span>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-2 pt-4 border-t">
                    <a href="{{ route('superadmin.frameworks.index') }}"
                        class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-primary/90">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnAdd       = document.getElementById('btn-add-domain');
    const domainList   = document.getElementById('domain-list');
    const emptyState   = document.getElementById('domain-empty');
    const domainHeader = document.getElementById('domain-header');
    const countEl      = document.getElementById('domain-count');
    const deletedInput = document.getElementById('deleted_domain_ids');

    let domainCounter = 0;
    let deletedIds    = [];

    function updateUI() {
        const count = domainList.querySelectorAll('.domain-row').length;
        countEl.textContent = count;
        emptyState.style.display = count === 0 ? 'block' : 'none';
        domainHeader.classList.toggle('hidden', count === 0);
        domainHeader.classList.toggle('grid', count > 0);
    }

    function renumber() {
        domainList.querySelectorAll('.domain-row').forEach((row, i) => {
            row.querySelector('.domain-number').textContent = i + 1;
        });
    }

    function addDomainRow(kode = '', nama = '', domainId = null) {
        const isExisting = domainId !== null;
        const idx = domainCounter++;

        const nameKode = isExisting ? `existing_domains[${domainId}][kode_domain]` : `new_domains[${idx}][kode_domain]`;
        const nameNama = isExisting ? `existing_domains[${domainId}][nama_domain]` : `new_domains[${idx}][nama_domain]`;

        const row = document.createElement('div');
        row.className = 'domain-row grid grid-cols-[24px_100px_1fr_32px] gap-2 items-center';
        row.dataset.domainId = domainId ?? '';

        row.innerHTML = `
            <span class="domain-number flex h-6 w-6 items-center justify-center rounded-full bg-primary/10 text-[10px] font-bold text-primary"></span>
            <input type="text" name="${nameKode}" value="${kode}" maxlength="10" placeholder="GV"
                class="flex h-10 rounded-md border border-input bg-background px-3 py-2 text-sm font-mono uppercase ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
            <input type="text" name="${nameNama}" value="${nama}" placeholder="Nama domain, contoh: Govern"
                class="flex h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
            <button type="button" class="btn-remove inline-flex h-8 w-8 items-center justify-center rounded-md border border-input bg-background text-destructive transition-colors hover:bg-destructive hover:text-destructive-foreground">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        `;

        row.querySelector('.btn-remove').addEventListener('click', () => {
            if (isExisting && domainId) {
                deletedIds.push(domainId);
                deletedInput.value = deletedIds.join(',');
            }
            row.remove();
            renumber();
            updateUI();
        });

        row.querySelector('input').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        domainList.appendChild(row);
        renumber();
        updateUI();
    }

    const existingDomains = @json($framework->domains->map(fn($d) => ['id' => $d->domain_id, 'kode' => $d->kode_domain, 'nama' => $d->nama_domain]));
    existingDomains.forEach(d => addDomainRow(d.kode, d.nama, d.id));

    btnAdd.addEventListener('click', () => {
        addDomainRow();
        domainList.lastElementChild?.querySelector('input')?.focus();
    });

    updateUI();
});
</script>
@endpush
@endsection