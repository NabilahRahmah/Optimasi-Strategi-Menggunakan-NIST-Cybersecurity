@extends('layouts.dashboard')
@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-on-surface">Hasil Assessment</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar assessment yang sudah diverifikasi dan disetujui.</p>
    </div>

    <div class="rounded-xl border bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-500">Karyawan</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-500">Assessment</th>
                    <th class="px-6 py-3 text-center text-xs font-bold uppercase text-gray-500">Nilai Total</th>
                    <th class="px-6 py-3 text-center text-xs font-bold uppercase text-gray-500">Tanggal</th>
                    <th class="px-6 py-3 text-right text-xs font-bold uppercase text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assessments as $assessment)
                    @php
                        $nilaiTotal = round($assessment->hasils->avg('nilai_kematangan'), 2);
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-on-surface">{{ $assessment->user->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $assessment->user->email ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-on-surface">{{ $assessment->judul_assessment }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-black text-lg text-on-surface">{{ number_format($nilaiTotal, 2) }}</span>
                            <span class="text-xs text-gray-400">/ 5.00</span>
                        </td>
                        <td class="px-6 py-4 text-center text-xs text-gray-500">
                            {{ $assessment->updated_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                            <a href="{{ route('approver.hasil.show', $assessment->assessment_id) }}"
                               class="inline-flex items-center gap-1 text-xs font-bold text-white bg-primary hover:bg-red-800 px-3 py-1.5 rounded-lg transition">
                                <span class="material-symbols-outlined text-sm">bar_chart</span>
                                Lihat Hasil
                            </a>
                            <a href="{{ route('approver.rekomendasi.create', $assessment->assessment_id) }}"
                               class="inline-flex items-center gap-1 text-xs font-bold text-primary border border-primary hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                                <span class="material-symbols-outlined text-sm">lightbulb</span>
                                Rekomendasi
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                            Belum ada assessment yang disetujui.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection