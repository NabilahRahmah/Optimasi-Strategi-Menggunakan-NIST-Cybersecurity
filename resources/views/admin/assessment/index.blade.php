@extends('layouts.dashboard')
@section('content')
    <div class="space-y-6">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">Assessment Frameworks</h1>
                <p class="text-sm text-muted-foreground">Kelola struktur parameter dan akses pengguna untuk setiap
                    framework.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
        @endif

        <div class="rounded-xl border bg-card shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b bg-muted/20">
                <h2 class="text-base font-bold text-foreground">Daftar Framework Assessment</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/40 border-b border-border">
                        <tr>
                            <th class="h-12 px-6 font-semibold align-middle text-muted-foreground w-16 text-center">No</th>
                            <th class="h-12 px-6 font-semibold align-middle text-muted-foreground">Nama Framework</th>
                            <th class="h-12 px-6 font-semibold align-middle text-muted-foreground text-center">Jumlah
                                Kategori</th>
                            <th class="h-12 px-6 font-semibold align-middle text-muted-foreground text-center">Jumlah
                                Pertanyaan</th>
                            <th class="h-12 px-6 font-semibold align-middle text-muted-foreground text-center w-48">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($frameworks as $fw)
                            @php
                                // Hitung total kategori dan pertanyaan dari relasi domain
                                $totalKategori = 0;
                                $totalPertanyaan = 0;
                                foreach ($fw->domains as $domain) {
                                    $totalKategori += $domain->kategoris->count();
                                    $totalPertanyaan += $domain->kategoris->sum(fn($k) => $k->pertanyaans->count());
                                }
                            @endphp
                            <tr class="even:bg-muted/10 hover:bg-muted/30 transition-colors">
                                <td class="p-4 px-6 text-center font-medium text-muted-foreground">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="p-4 px-6">
                                    <p class="font-semibold text-foreground">{{ $fw->name_framework }}</p>
                                    <p class="text-xs text-muted-foreground mt-0.5">
                                        {{ $fw->assignedUsers->count() }} User Assigned
                                    </p>
                                </td>
                                <td class="p-4 px-6 text-center text-muted-foreground font-medium">
                                    {{ $totalKategori }}
                                </td>
                                <td class="p-4 px-6 text-center text-muted-foreground font-medium">
                                    {{ $totalPertanyaan }}
                                </td>
                                <td class="p-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">

                                        <a href="{{ route('admin.assessment.edit', $fw->framework_id) }}"
                                            class="inline-flex items-center justify-center rounded bg-primary px-3 py-1.5 text-xs font-semibold text-primary-foreground text-white hover:bg-primary/90 transition-colors shadow-sm"
                                            title="Edit & Mapping Parameter">
                                            Edit Struktur
                                        </a>

                                        <button type="button" onclick="openModal('modal-akses-{{ $fw->framework_id }}')"
                                            class="inline-flex items-center justify-center rounded bg-primary px-3 py-1.5 text-xs font-semibold text-primary-foreground text-white hover:bg-primary/90 transition-colors shadow-sm"
                                            title="Assign User & Approver">
                                            Kelola Akses
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-sm text-muted-foreground italic">
                                    Belum ada data framework.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach($frameworks as $fw)
        <div id="modal-akses-{{ $fw->framework_id }}" 
        class="fixed inset-0 z-50 hidden backdrop-blur-sm transition-opacity"
            style="background: rgba(0,0,0,0.6);">
        <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6">
            <div class="w-full max-w-2xl rounded-xl border border-border bg-white shadow-lg flex flex-col max-h-[90vh]">

                <div class="flex items-center justify-between border-b border-border px-6 py-4">
                    <div>
                        <h3 class="text-lg font-bold text-foreground">Kelola Akses Pengguna</h3>
                        <p class="text-xs text-muted-foreground">Framework: <span
                                class="font-semibold">{{ $fw->name_framework }}</span></p>
                    </div>
                    <button type="button" onclick="closeModal('modal-akses-{{ $fw->framework_id }}')"
                        class="rounded-md p-1 hover:bg-muted text-muted-foreground transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="overflow-y-auto px-6 py-4">
                    <form id="form-akses-{{ $fw->framework_id }}"
                        action="{{ route('admin.assessment.assign', $fw->framework_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @php
                            $users = $assignableUsers->where('role', 'user');
                            $approvers = $assignableUsers->where('role', 'approver');
                            $assignedIds = $fw->assignedUsers->pluck('user_id')->toArray();
                        @endphp

                        <div class="space-y-6">
                            {{-- Checkbox Users --}}
                            @if($users->count() > 0)
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-muted-foreground mb-3 border-b pb-1">
                                        Role: User</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($users as $u)
                                            <label
                                                class="flex items-center gap-3 rounded-lg border border-input bg-background p-3 cursor-pointer hover:bg-accent transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                                <input type="checkbox" name="user_ids[]" value="{{ $u->user_id }}" {{ in_array($u->user_id, $assignedIds) ? 'checked' : '' }}
                                                    class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-semibold text-foreground">{{ $u->name }}</span>
                                                    <span class="text-xs text-muted-foreground">{{ $u->email }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Checkbox Approvers --}}
                            @if($approvers->count() > 0)
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-muted-foreground mb-3 border-b pb-1">
                                        Role: Approver</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($approvers as $u)
                                            <label
                                                class="flex items-center gap-3 rounded-lg border border-input bg-background p-3 cursor-pointer hover:bg-accent transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                                <input type="checkbox" name="user_ids[]" value="{{ $u->user_id }}" {{ in_array($u->user_id, $assignedIds) ? 'checked' : '' }}
                                                    class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-semibold text-foreground">{{ $u->name }}</span>
                                                    <span class="text-xs text-muted-foreground">{{ $u->email }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-border px-6 py-4 bg-muted/10">
                    <button type="button" onclick="closeModal('modal-akses-{{ $fw->framework_id }}')"
                        class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 text-sm font-medium transition-colors hover:bg-accent">
                        Batal
                    </button>
                    <button type="submit" form="form-akses-{{ $fw->framework_id }}"
                        class="inline-flex h-9 items-center justify-center rounded-md bg-primary px-4 text-sm font-medium text-white transition-colors hover:bg-primary/90">
                        Simpan Perubahan Akses
                    </button>
                </div>

            </div>
        </div>
        </div>
    @endforeach

    <script>
        // Fungsi untuk membuka modal Pop-up
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Mencegah background scroll saat pop-up terbuka
        }

        // Fungsi untuk menutup modal Pop-up
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = 'auto'; // Mengembalikan scroll
        }

        window.onclick = function (event) {
            if (event.target.classList.contains('fixed', 'inset-0')) {
                event.target.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }
    </script>
@endsection