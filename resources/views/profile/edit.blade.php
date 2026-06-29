@extends('layouts.dashboard')
@section('content')
    <div class="space-y-6 max-w-2xl">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Profil Saya</h1>
            <p class="text-sm text-muted-foreground mt-1">Kelola informasi akun dan keamanan Anda.</p>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- CARD: Info Profil --}}
        <div class="rounded-xl border bg-card shadow-sm overflow-hidden">
            <div class="p-6 border-b bg-muted/20 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-foreground">Informasi Profil</h3>
                    <p class="text-xs text-muted-foreground mt-1">Nama, username, NIK, dan email Anda.</p>
                </div>
                <button type="button" onclick="toggleEdit('profile')"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold border border-input bg-background px-3 py-1.5 rounded-md hover:bg-accent transition-colors">
                    <span class="material-symbols-outlined text-sm">edit</span>
                    Edit Profil
                </button>
            </div>

            {{-- Preview (default tampil) --}}
            <div id="profile-preview" class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-muted-foreground mb-1">Nama Lengkap</p>
                        <p class="font-semibold text-foreground">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground mb-1">Username</p>
                        <p class="font-semibold text-foreground">{{ $user->username ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground mb-1">NIK Karyawan</p>
                        <p class="font-semibold text-foreground">{{ $user->nik ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground mb-1">Email</p>
                        <p class="font-semibold text-foreground">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-muted-foreground mb-1">Role</p>
                        <p class="font-semibold text-foreground">
                            {{ match ($user->role) {
        'admin_super' => 'Super Admin',
        'admin' => 'Admin',
        'approver' => 'Approver',
        'user' => 'User / SOC',
        default => $user->role,
    } }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Form Edit (tersembunyi) --}}
            <div id="profile-form" class="hidden p-6">
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">Nama Lengkap <span
                                class="text-destructive">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring @error('name') border-destructive @enderror">
                        @error('name')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring @error('username') border-destructive @enderror">
                        @error('username')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">NIK Karyawan</label>
                        <input type="text" name="nik" value="{{ old('nik', $user->nik) }}"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    </div>

                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">Email <span
                                class="text-destructive">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring @error('email') border-destructive @enderror">
                        @error('email')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">Role</label>
                        <div
                            class="flex h-10 w-full rounded-md border border-input bg-muted/30 px-3 py-2 text-sm text-muted-foreground items-center">
                            {{ match ($user->role) {
        'admin_super' => 'Super Admin',
        'admin' => 'Admin',
        'approver' => 'Approver',
        'user' => 'User / SOC',
        default => $user->role,
    } }}
                        </div>
                        <p class="text-[10px] text-muted-foreground">Role tidak dapat diubah sendiri.</p>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t">
                        <button type="button" onclick="toggleEdit('profile')"
                            class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-accent transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- CARD: Password --}}
        <div class="rounded-xl border bg-card shadow-sm overflow-hidden">
            <div class="p-6 border-b bg-muted/20 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-foreground">Password</h3>
                    <p class="text-xs text-muted-foreground mt-1">Keamanan akun Anda.</p>
                </div>
                <button type="button" onclick="toggleEdit('password')"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold border border-input bg-background px-3 py-1.5 rounded-md hover:bg-accent transition-colors">
                    <span class="material-symbols-outlined text-sm">lock</span>
                    Ganti Password
                </button>
            </div>

            {{-- Preview password --}}
            <div id="password-preview" class="p-6">
                <div class="flex items-center gap-3 text-sm text-muted-foreground">
                    <span class="material-symbols-outlined text-base">lock</span>
                    <span>Password tersimpan dengan aman. Klik "Ganti Password" untuk mengubahnya.</span>
                </div>
            </div>

            {{-- Form Ganti Password (tersembunyi) --}}
            <div id="password-form" class="hidden p-6">
                <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">Password Saat Ini <span
                                class="text-destructive">*</span></label>
                        <input type="password" name="current_password"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring @error('current_password') border-destructive @enderror"
                            placeholder="Password lama Anda">
                        @error('current_password')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">Password Baru <span
                                class="text-destructive">*</span></label>
                        <input type="password" name="password"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring @error('password') border-destructive @enderror"
                            placeholder="Min. 8 karakter">
                        @error('password')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-2">
                        <label class="text-sm font-medium leading-none">Konfirmasi Password Baru <span
                                class="text-destructive">*</span></label>
                        <input type="password" name="password_confirmation"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            placeholder="Ulangi password baru">
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t">
                        <button type="button" onclick="toggleEdit('password')"
                            class="inline-flex items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-accent transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90 transition-colors">
                            Ganti Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function toggleEdit(section) {
            const preview = document.getElementById(`${section}-preview`);
            const form = document.getElementById(`${section}-form`);
            preview.classList.toggle('hidden');
            form.classList.toggle('hidden');
        }

        // Kalau ada error validasi, langsung tampilkan form yang error
        @if($errors->has('name') || $errors->has('username') || $errors->has('nik') || $errors->has('email'))
            toggleEdit('profile');
        @endif
        @if($errors->has('current_password') || $errors->has('password'))
            toggleEdit('password');
        @endif
    </script>
@endsection