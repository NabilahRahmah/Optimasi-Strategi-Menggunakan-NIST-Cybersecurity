@extends('layouts.dashboard')
@section('content')
<div class="space-y-6">
    <nav class="flex items-center gap-2 text-sm text-muted-foreground">
        <span>Platform</span><span>/</span>
        <a href="{{ route('admin.dokpendukung.index') }}" class="hover:text-foreground">Dokumen Pendukung</a>
        <span>/</span>
        <span class="font-medium text-foreground">Tambah Dokumen</span>
    </nav>

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-foreground">Tambah Dokumen</h1>
        <a href="{{ route('admin.dokpendukung.index') }}"
            class="inline-flex items-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-accent transition-colors">
            ← Kembali
        </a>
    </div>

    <div class="rounded-xl border bg-card shadow-sm max-w-2xl">
        <div class="p-6 border-b bg-muted/20">
            <h3 class="text-base font-semibold">Data Dokumen</h3>
            <p class="text-xs text-muted-foreground mt-1">Field bertanda * wajib diisi.</p>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.dokpendukung.store') }}" method="POST"
                enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid gap-2">
                    <label class="text-sm font-medium">Nama Dokumen <span class="text-destructive">*</span></label>
                    <input type="text" name="nama_dokumen" value="{{ old('nama_dokumen') }}"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm @error('nama_dokumen') border-destructive @enderror"
                        placeholder="Contoh: Kebijakan Keamanan Informasi">
                    @error('nama_dokumen')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-2">
                    <label class="text-sm font-medium">Domain <span class="text-destructive">*</span></label>
                    <select name="domain_id"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm @error('domain_id') border-destructive @enderror">
                        <option value="">-- Pilih Domain --</option>
                        @foreach($domains as $domain)
                            <option value="{{ $domain->domain_id }}" {{ old('domain_id') == $domain->domain_id ? 'selected' : '' }}>
                                {{ $domain->kode_domain }} — {{ $domain->nama_domain }}
                            </option>
                        @endforeach
                    </select>
                    @error('domain_id')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-2">
                    <label class="text-sm font-medium">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                        class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                        placeholder="Deskripsi singkat dokumen...">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="grid gap-2">
                    <label class="text-sm font-medium">File Dokumen <span class="text-destructive">*</span></label>
                    <input type="file" name="file"
                        class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm @error('file') border-destructive @enderror"
                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    <p class="text-[10px] text-muted-foreground">Format: PDF, JPG, PNG, DOC, DOCX. Maks: 10MB.</p>
                    @error('file')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-3 pt-4 border-t">
                    <a href="{{ route('admin.dokpendukung.index') }}"
                        class="inline-flex items-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-accent transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90 transition-colors">
                        Simpan Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection