@extends('layouts.dashboard')
@section('content')
<div class="space-y-6">
 
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-muted-foreground">
        <span>Platform</span>
        <span class="text-border">/</span>
        <a href="{{ route('superadmin.users.index') }}" class="hover:text-foreground">Kelola Karyawan</a>
        <span class="text-border">/</span>
        <span class="font-medium text-white">Tambah Karyawan</span>
    </nav>
 
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Tambah Karyawan</h1>
            <p class="text-sm text-muted-foreground">Daftarkan akun karyawan baru ke sistem.</p>
        </div>
        <a href="{{ route('superadmin.users.index') }}"
            class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground">
            ← Kembali
        </a>
    </div>
 
    {{-- Form --}}
    <div class="rounded-xl border bg-card shadow-sm overflow-hidden max-w-2xl">
        <div class="p-6 border-b bg-muted/20">
            <h3 class="text-base font-semibold text-foreground tracking-tight">Data Karyawan</h3>
            <p class="text-xs text-muted-foreground mt-1">Semua field bertanda * wajib diisi.</p>
        </div>
        <div class="p-6">
            <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-4">
                @csrf
 
                {{-- Nama --}}
                <div class="grid gap-2">
                    <label class="text-sm font-medium leading-none">
                        Nama Lengkap <span class="text-destructive">*</span>
                    </label>
                    <input type="text" name="name"
                        value="{{ old('name') }}"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring @error('name') border-destructive @enderror"
                        placeholder="e.g. Budi Santoso">
                    @error('name')
                        <p class="text-xs text-destructive">{{ $message }}</p>
                    @enderror
                </div>
 
                {{-- Username --}}
                <div class="grid gap-2">
                    <label class="text-sm font-medium leading-none">Username</label>
                    <input type="text" name="username"
                        value="{{ old('username') }}"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        placeholder="e.g. budisantoso">
                </div>
 
                {{-- NIK --}}
                <div class="grid gap-2">
                    <label class="text-sm font-medium leading-none">NIK Karyawan</label>
                    <input type="text" name="nik"
                        value="{{ old('nik') }}"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        placeholder="e.g. 12345">
                </div>
 
                {{-- Email --}}
                <div class="grid gap-2">
                    <label class="text-sm font-medium leading-none">
                        Email <span class="text-destructive">*</span>
                    </label>
                    <input type="email" name="email"
                        value="{{ old('email') }}"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring @error('email') border-destructive @enderror"
                        placeholder="e.g. budi@perusahaan.com">
                    @error('email')
                        <p class="text-xs text-destructive">{{ $message }}</p>
                    @enderror
                </div>
 
                {{-- Role --}}
                <div class="grid gap-2">
                    <label class="text-sm font-medium leading-none">
                        Role / Jabatan <span class="text-destructive">*</span>
                    </label>
                    <select name="role"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring @error('role') border-destructive @enderror">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                            Admin (Kelola Checklist & Dokumen)
                        </option>
                        <option value="approver" {{ old('role') == 'approver' ? 'selected' : '' }}>
                            Approver (Verifikasi Assessment)
                        </option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>
                            User / SOC (Self Assessment)
                        </option>
                        <option value="admin_super" {{ old('role') == 'admin_super' ? 'selected' : '' }}>
                            Super Admin (Akses Penuh)
                        </option>
                    </select>
                    @error('role')
                        <p class="text-xs text-destructive">{{ $message }}</p>
                    @enderror
                </div>
 
                {{-- Password --}}
                <div class="grid gap-2">
                    <label class="text-sm font-medium leading-none">
                        Password <span class="text-destructive">*</span>
                    </label>
                    <input type="password" name="password"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring @error('password') border-destructive @enderror"
                        placeholder="Min. 8 karakter">
                    @error('password')
                        <p class="text-xs text-destructive">{{ $message }}</p>
                    @enderror
                </div>
 
                {{-- Konfirmasi Password --}}
                <div class="grid gap-2">
                    <label class="text-sm font-medium leading-none">
                        Konfirmasi Password <span class="text-destructive">*</span>
                    </label>
                    <input type="password" name="password_confirmation"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        placeholder="Ulangi password">
                </div>
 
                {{-- Tombol --}}
                <div class="flex justify-end gap-2 pt-4 border-t">
                    <a href="{{ route('superadmin.users.index') }}"
                        class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90">
                        Simpan Karyawan
                    </button>
                </div>
            </form>
        </div>
    </div>
 
</div>
@endsection