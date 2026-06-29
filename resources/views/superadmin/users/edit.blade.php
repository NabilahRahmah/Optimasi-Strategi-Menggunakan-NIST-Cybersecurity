@extends('layouts.dashboard')
@section('content')
    <div class="space-y-6">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-muted-foreground">
            <span>Platform</span>
            <span class="text-border">/</span>
            <a href="{{ route('superadmin.users.index') }}" class="hover:text-foreground">Kelola Karyawan</a>
            <span class="text-border">/</span>
            <span class="font-medium text-white">Edit Karyawan</span>
        </nav>

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white">Edit Karyawan</h1>
                <p class="text-sm text-muted-foreground">Ubah data akun karyawan.</p>
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
                <p class="text-xs text-muted-foreground mt-1">Password dikosongkan jika tidak ingin diubah.</p>
            </div>
            <div class="p-6">
                <form action="{{ route('superadmin.users.update', $user->user_id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">
                            Nama Lengkap <span class="text-destructive">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring @error('name') border-destructive @enderror"
                            placeholder="e.g. Budi Santoso">
                        @error('name')
                            <p class="text-xs text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring @error('username') border-destructive @enderror"
                            placeholder="e.g. budisantoso">
                        @error('username')
                            <p class="text-xs text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIK --}}
                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">NIK Karyawan</label>
                        <input type="text" name="nik" value="{{ old('nik', $user->nik) }}"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            placeholder="e.g. 12345">
                    </div>

                    {{-- Email --}}
                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">
                            Email <span class="text-destructive">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
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
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                Admin (Kelola Checklist & Dokumen)
                            </option>
                            <option value="approver" {{ old('role', $user->role) == 'approver' ? 'selected' : '' }}>
                                Approver (Verifikasi Assessment)
                            </option>
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>
                                User / SOC (Self Assessment)
                            </option>
                            <option value="admin_super" {{ old('role', $user->role) == 'admin_super' ? 'selected' : '' }}>
                                Super Admin (Akses Penuh)
                            </option>
                        </select>
                        @error('role')
                            <p class="text-xs text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password baru (opsional) --}}
                    <div class="rounded-lg border border-dashed border-input p-4 space-y-4">
                        <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                            Ganti Password (opsional — kosongkan jika tidak ingin diubah)
                        </p>

                        <div class="grid gap-2">
                            <label class="text-sm font-medium leading-none">Password Baru</label>
                            <input type="password" name="password"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring @error('password') border-destructive @enderror"
                                placeholder="Min. 8 karakter">
                            @error('password')
                                <p class="text-xs text-destructive">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid gap-2">
                            <label class="text-sm font-medium leading-none">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end gap-2 pt-4 border-t">
                        <a href="{{ route('superadmin.users.index') }}"
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
@endsection