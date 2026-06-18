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
                        <tr class="border-b hover:bg-muted/50">
                            <td class="p-6 font-semibold text-foreground">{{ $item->judul_assessment }}</td>
                            <td class="p-6 text-muted-foreground">
                                {{-- Nampilin tanggal, kalau created_at kosong pakai tgl_pelaksanaan --}}
                                {{ $item->created_at ? $item->created_at->format('d M Y') : ($item->tgl_pelaksanaan ?? '-') }}
                            </td>
                            <td class="p-6">
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-md text-xs font-bold uppercase">Selesai</span>
                            </td>
                            <td class="p-6 text-right">
                                {{-- Tombol ini yang bakal ngebuka Radar Chart lu! --}}
                                <a href="{{ route('user.hasil.show', $item->id ?? $item->assessment_id) }}" class="inline-flex items-center justify-center rounded-md bg-primary/10 text-primary px-4 py-2 text-sm font-bold transition-colors hover:bg-primary hover:text-white">
                                    Lihat Grafik Radar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center text-muted-foreground text-sm">
                                <div class="flex flex-col items-center justify-center opacity-50">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-4"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
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