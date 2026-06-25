@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">
                {{ $pageTitle ?? 'Verifikasi' }}
            </h1>
            <p class="text-sm text-muted-foreground mt-1">
                Kelola dan pantau status verifikasi assessment keamanan siber.
            </p>
        </div>
    </div>

    {{-- Tab navigasi --}}
    <div class="flex gap-2 border-b border-gray-200">
        <a href="{{ route('approver.verifikasi.index') }}"
           class="px-4 py-2 text-sm font-semibold rounded-t-lg border-b-2 transition
               {{ ($activeTab ?? 'antrian') === 'antrian'
                   ? 'border-primary text-primary bg-primary/5'
                   : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            ⏳ Antrian
        </a>
        <a href="{{ route('approver.verifikasi.disetujui') }}"
           class="px-4 py-2 text-sm font-semibold rounded-t-lg border-b-2 transition
               {{ ($activeTab ?? '') === 'disetujui'
                   ? 'border-primary text-primary bg-primary/5'
                   : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            ✅ Selesai Diverifikasi
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl border bg-card shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-muted/30 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase">Karyawan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase">Judul Assessment</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-muted-foreground uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-muted-foreground uppercase">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-muted-foreground uppercase">Progres</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-muted-foreground uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assessments as $assessment)
                    @php
                        $total     = $assessment->jawabans->count();
                        $disetujui = $assessment->jawabans->where('status_verifikasi', 'disetujui')->count();
                        $ditolak   = $assessment->jawabans->where('status_verifikasi', 'ditolak')->count();
                        $pct       = $total > 0 ? round((($disetujui + $ditolak) / $total) * 100) : 0;
                    @endphp
                    <tr class="hover:bg-muted/30 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-foreground">{{ $assessment->user->name ?? '-' }}</p>
                            <p class="text-xs text-muted-foreground">{{ $assessment->user->email ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-foreground">{{ $assessment->judul_assessment }}</td>
                        <td class="px-6 py-4 text-muted-foreground text-xs">
                            {{ $assessment->updated_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($assessment->status === 'submitted')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Menunggu</span>
                            @elseif($assessment->status === 'in_review')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Sedang Direview</span>
                            @elseif($assessment->status === 'disetujui')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">✓ disetujui</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-primary h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="text-xs text-muted-foreground shrink-0">{{ $pct }}%</span>
                            </div>
                            <p class="text-[10px] text-muted-foreground mt-1">
                                {{ $disetujui }} setuju · {{ $ditolak }} tolak · {{ $total - $disetujui - $ditolak }} pending
                            </p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('approver.verifikasi.show', $assessment->assessment_id) }}"
                               class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-primary hover:bg-red-800 px-3 py-1.5 rounded-lg transition">
                                <span class="material-symbols-outlined text-sm">rate_review</span>
                                {{ $assessment->status === 'disetujui' ? 'Lihat Detail' : 'Review' }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-muted-foreground">
                            Tidak ada assessment di kategori ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection