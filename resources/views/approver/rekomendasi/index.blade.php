@extends('layouts.dashboard')
@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-on-surface">Rekomendasi</h1>
        <p class="text-sm text-gray-500 mt-1">Tambahkan rekomendasi perbaikan untuk persiapan audit formal.</p>
    </div>

    <div class="rounded-xl border bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-500">Karyawan</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase text-gray-500">Assessment</th>
                    <th class="px-6 py-3 text-center text-xs font-bold uppercase text-gray-500">Rekomendasi Approver</th>
                    <th class="px-6 py-3 text-right text-xs font-bold uppercase text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assessments as $assessment)
                    @php
                        $rekApprover = $assessment->rekomendasis->where('sumber', 'approver')->count();
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-on-surface">{{ $assessment->user->name ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">{{ $assessment->judul_assessment }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($rekApprover > 0)
                                <span class="inline-block rounded-full bg-green-100 text-green-700 text-xs font-bold px-3 py-1">
                                    {{ $rekApprover }} rekomendasi
                                </span>
                            @else
                                <span class="inline-block rounded-full bg-gray-100 text-gray-500 text-xs px-3 py-1">
                                    Belum ada
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('approver.rekomendasi.create', $assessment->assessment_id) }}"
                               class="inline-flex items-center gap-1 text-xs font-bold text-white bg-primary hover:bg-red-800 px-3 py-1.5 rounded-lg transition">
                                <span class="material-symbols-outlined text-sm">add</span>
                                Kelola Rekomendasi
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                            Belum ada assessment yang bisa diberi rekomendasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection