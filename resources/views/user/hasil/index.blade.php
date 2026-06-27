@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">Hasil Assessment Saya</h1>
                <p class="text-sm text-muted-foreground">Riwayat audit NIST CSF 2.0 yang sudah Anda kerjakan.</p>
            </div>
        </div>

        <div class="rounded-xl border bg-card text-card-foreground shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="border-b bg-muted/50">
                            <th class="h-12 px-6 text-left font-medium text-muted-foreground uppercase text-[10px]">Judul Assessment</th>
                            <th class="h-12 px-6 text-left font-medium text-muted-foreground uppercase text-[10px]">Tanggal Submit</th>
                            <th class="h-12 px-6 text-left font-medium text-muted-foreground uppercase text-[10px]">Status</th>
                            <th class="h-12 px-6 text-right font-medium text-muted-foreground uppercase text-[10px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assessments as $item)
                            @php
                                $statusMap = [
                                    'draft'     => ['bg-gray-100 text-gray-600',   'Draft'],
                                    'submitted' => ['bg-blue-100 text-blue-700',   'Menunggu Verifikasi'],
                                    'in_review' => ['bg-amber-100 text-amber-700', 'Sedang Direview'],
                                    'disetujui' => ['bg-green-100 text-green-700', 'Selesai'],
                                    'ditolak'   => ['bg-red-100 text-red-700',     'Perlu Revisi'],
                                ];
                                [$statusClass, $statusLabel] = $statusMap[$item->status] ?? ['bg-gray-100 text-gray-600', $item->status];
                            @endphp
                            <tr class="border-b hover:bg-muted/50 {{ $item->status === 'ditolak' ? 'bg-red-50/30' : '' }}">
                                <td class="p-6 font-semibold text-foreground">
                                    {{ $item->judul_assessment }}
                                    @if($item->status === 'ditolak')
                                        <span class="ml-2 text-[10px] font-bold text-red-500 uppercase tracking-wider">
                                            ← perlu diperbaiki
                                        </span>
                                    @endif
                                </td>
                                <td class="p-6 text-muted-foreground">
                                    {{ $item->updated_at ? $item->updated_at->format('d M Y') : '-' }}
                                </td>
                                <td class="p-6">
                                    <span class="px-2 py-1 rounded-md text-xs font-bold uppercase {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="p-6 text-right">
                                    <div class="flex items-center justify-end gap-2">

                                        {{-- 
                                            LOGIKA TOMBOL:
                                            - ditolak   → 1 tombol: "Revisi" (merah) → ke halaman assessment
                                            - draft     → 1 tombol: "Lanjut Isi" → ke halaman assessment  
                                            - submitted/in_review → 1 tombol: "Lihat" (abu) → ke halaman assessment (read-only)
                                                                  + 1 tombol: "Lihat Hasil" (merah muda) → ke hasil skor
                                            - disetujui → 1 tombol: "Lihat" → ke assessment (read-only)
                                                        + 1 tombol: "Lihat Hasil" → ke hasil skor
                                        --}}

                                        @if($item->status === 'ditolak')
                                            {{-- Satu tombol menonjol: langsung ke revisi --}}
                                            <a href="{{ route('user.assessment.index', ['framework_id' => $item->framework_id, 'assessment_id' => $item->assessment_id]) }}"
                                                class="inline-flex items-center gap-1.5 rounded-md bg-red-600 text-white px-3 py-1.5 text-xs font-bold transition-colors hover:bg-red-700 shadow-sm">
                                                <span class="material-symbols-outlined text-sm">edit_note</span>
                                                Revisi Sekarang
                                            </a>

                                        @elseif($item->status === 'draft')
                                            <a href="{{ route('user.assessment.index', ['framework_id' => $item->framework_id, 'assessment_id' => $item->assessment_id]) }}"
                                                class="inline-flex items-center gap-1.5 rounded-md bg-gray-100 text-gray-700 px-3 py-1.5 text-xs font-bold transition-colors hover:bg-gray-200">
                                                <span class="material-symbols-outlined text-sm">assignment</span>
                                                Lanjut Isi
                                            </a>

                                        @else
                                            {{-- submitted / in_review / disetujui --}}
                                            <a href="{{ route('user.assessment.index', ['framework_id' => $item->framework_id, 'assessment_id' => $item->assessment_id]) }}"
                                                class="inline-flex items-center gap-1.5 rounded-md bg-gray-100 text-gray-700 px-3 py-1.5 text-xs font-bold transition-colors hover:bg-gray-200">
                                                <span class="material-symbols-outlined text-sm">assignment</span>
                                                Lihat
                                            </a>

                                            <a href="{{ route('user.hasil.show', $item->assessment_id) }}"
                                                class="inline-flex items-center gap-1.5 rounded-md bg-primary/10 text-primary px-3 py-1.5 text-xs font-bold transition-colors hover:bg-primary hover:text-white">
                                                <span class="material-symbols-outlined text-sm">bar_chart</span>
                                                Lihat Hasil
                                            </a>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-12 text-center text-muted-foreground text-sm">
                                    <div class="flex flex-col items-center justify-center opacity-50">
                                        <span class="material-symbols-outlined text-4xl mb-3">inventory_2</span>
                                        <p class="font-medium">Belum ada assessment yang disubmit.</p>
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