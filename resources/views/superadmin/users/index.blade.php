@extends('layouts.dashboard')
@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">Kelola Karyawan</h1>
                <p class="text-sm text-muted-foreground">Manajemen akses dan role karyawan (Admin, Approver, User).</p>
            </div>
            {{-- ✅ FIX: route name disesuaikan --}}
            <a href="{{ route('superadmin.users.create') }}"
                class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90">
                Tambah Karyawan
            </a>
        </div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Tabel --}}
        <div class="rounded-xl border bg-card text-card-foreground shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="border-b bg-muted/50">
                            <th class="h-12 px-6 text-left font-medium text-muted-foreground uppercase text-[10px]">Nama
                                Karyawan</th>
                            <th class="h-12 px-6 text-left font-medium text-muted-foreground uppercase text-[10px]">Email
                            </th>
                            <th class="h-12 px-6 text-left font-medium text-muted-foreground uppercase text-[10px]">Role /
                                Jabatan</th>
                            <th class="h-12 px-6 text-right font-medium text-muted-foreground uppercase text-[10px]">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $karyawan)
                            <tr class="border-b hover:bg-muted/50">
                                <td class="p-6 font-semibold text-foreground">{{ $karyawan->name }}</td>
                                <td class="p-6 text-muted-foreground">{{ $karyawan->email }}</td>
                                <td class="p-6">
                                    @if($karyawan->role == 'admin_super')
                                        <span
                                            class="px-2 py-1 bg-purple-100 text-purple-700 rounded-md text-xs font-bold uppercase">Super
                                            Admin</span>
                                    @elseif($karyawan->role == 'admin')
                                        <span
                                            class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md text-xs font-bold uppercase">Admin</span>
                                    @elseif($karyawan->role == 'approver')
                                        <span
                                            class="px-2 py-1 bg-amber-100 text-amber-700 rounded-md text-xs font-bold uppercase">Approver</span>
                                    @else
                                        <span
                                            class="px-2 py-1 bg-gray-100 text-gray-700 rounded-md text-xs font-bold uppercase">User
                                            (SOC)</span>
                                    @endif
                                </td>
                                <td class="p-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('superadmin.users.edit', $karyawan->user_id) }}"
                                            class="text-xs font-bold border border-input bg-background px-3 py-1 rounded-md hover:bg-accent transition-colors">
                                            Edit
                                        </a>
                                        <form action="{{ route('superadmin.users.destroy', $karyawan->user_id) }}" method="POST"
                                            onsubmit="return confirm('Hapus akses akun ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-white text-xs font-bold bg-primary border border-primary px-3 py-1 rounded-md hover:bg-red-800 hover:border-red-800 transition-colors">
                                                Cabut Akses
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-12 text-center text-muted-foreground text-sm">
                                    Belum ada data karyawan selain Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection