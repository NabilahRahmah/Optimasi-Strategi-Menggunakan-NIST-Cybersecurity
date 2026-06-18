@extends('layouts.dashboard')

@section('content')

@php
    $levelColor = function($nilai) {
        if ($nilai >= 3.75) return ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Tier 4 – Adaptive'];
        if ($nilai >= 2.50) return ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Tier 3 – Repeatable'];
        if ($nilai >= 1.25) return ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Tier 2 – Risk Informed'];
        if ($nilai >  0.0)  return ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Tier 1 – Partial'];
        return ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Belum Ada Penerapan'];
    };

    $domainColor = [
        'GV' => 'bg-purple-100 text-purple-700',
        'ID' => 'bg-blue-100 text-blue-700',
        'PR' => 'bg-green-100 text-green-700',
        'DE' => 'bg-yellow-100 text-yellow-700',
        'RS' => 'bg-orange-100 text-orange-700',
        'RC' => 'bg-red-100 text-red-700',
    ];

    $nilaiTotal  = round($hasils->avg('nilai_kematangan'), 2);
    $levelTotal  = $levelColor($nilaiTotal);
    $rekOtomatis = $rekomendasis->where('sumber', 'otomatis');
    $rekApprover = $rekomendasis->where('sumber', 'approver');
@endphp

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-on-surface">{{ $assessment->judul_assessment }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                Karyawan: <strong>{{ $assessment->user->name }}</strong>
                &nbsp;·&nbsp; Disubmit: {{ $assessment->updated_at->format('d M Y') }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('approver.rekomendasi.create', $assessment->assessment_id) }}"
               class="inline-flex items-center gap-2 rounded-lg border border-primary text-primary px-4 py-2 text-sm font-bold hover:bg-red-50 transition">
                <span class="material-symbols-outlined text-lg">lightbulb</span>
                Tambah Rekomendasi
            </a>
            <a href="{{ route('approver.hasil.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-200 text-gray-600 px-4 py-2 text-sm font-bold hover:bg-gray-50 transition">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Kembali
            </a>
        </div>
    </div>

    {{-- NILAI TOTAL --}}
    <div class="rounded-xl border bg-white p-6 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Nilai Kematangan Keseluruhan</p>
            <div class="flex items-end gap-2">
                <span class="text-5xl font-black text-on-surface">{{ number_format($nilaiTotal, 2) }}</span>
                <span class="text-lg text-gray-400 mb-1">/ 5.00</span>
            </div>
            <span class="inline-block mt-2 rounded-full px-3 py-1 text-xs font-bold {{ $levelTotal['bg'] }} {{ $levelTotal['text'] }}">
                {{ $levelTotal['label'] }}
            </span>
        </div>
        <div class="text-right text-sm text-gray-500">
            <p>Target: <strong>{{ number_format($hasils->avg('target_nilai'), 2) }}</strong></p>
            <p>Gap rata-rata: <strong class="text-red-600">{{ number_format($hasils->avg('gap'), 2) }}</strong></p>
            <p class="mt-1 text-xs">Framework: NIST CSF 2.0</p>
        </div>
    </div>

    {{-- GRID: Maturity Index + Radar --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="rounded-xl border bg-white p-6 shadow-sm">
            <h3 class="font-bold text-on-surface mb-4">Maturity Index per Domain</h3>
            <div class="space-y-4">
                @foreach($hasils->sortByDesc('nilai_kematangan') as $hasil)
                    @php
                        $pct  = ($hasil->nilai_kematangan / 5) * 100;
                        $lv   = $levelColor($hasil->nilai_kematangan);
                        $kode = $hasil->domain->kode ?? '??';
                        $dc   = $domainColor[$kode] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-bold {{ $dc }}">{{ $kode }}</span>
                                <span class="text-xs font-bold text-on-surface">{{ $hasil->domain->nama_domain }}</span>
                            </div>
                            <span class="text-xs font-black">{{ number_format($hasil->nilai_kematangan, 2) }} / 5.0</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full bg-primary" style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span class="text-[10px] {{ $lv['text'] }} font-semibold">{{ $lv['label'] }}</span>
                            <span class="text-[10px] text-gray-400">Gap: {{ number_format($hasil->gap, 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-xl border bg-white p-6 shadow-sm flex flex-col">
            <h3 class="font-bold text-on-surface mb-4">Radar Visualisasi NIST CSF 2.0</h3>
            <div class="flex-1 flex items-center justify-center" style="min-height:280px">
                <canvas id="radarChart"></canvas>
            </div>
        </div>
    </div>

    {{-- DETAIL PER KATEGORI --}}
    @if($rataRataPerKategori->count() > 0)
    <div class="rounded-xl border bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h3 class="font-bold text-on-surface">Rincian Skor per Kategori NIST CSF 2.0</h3>
        </div>
        <div class="overflow-x-auto" style="max-height:360px; overflow-y:auto;">
            <table class="w-full text-sm">
                <thead class="sticky top-0 bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-bold uppercase text-gray-500">Domain</th>
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase text-gray-500">Kategori</th>
                        <th class="text-center px-4 py-3 text-xs font-bold uppercase text-gray-500">Skor</th>
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase text-gray-500">Level</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($rataRataPerKategori as $data)
                        @php
                            $lv = $levelColor($data->rata_rata_kategori);
                            $dc = $domainColor[$data->kode] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <span class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-bold {{ $dc }}">
                                    {{ $data->kode }} — {{ $data->nama_domain }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <span class="font-bold font-mono text-gray-800">{{ $data->kode_kategori }}</span>
                                <span class="text-gray-500 ml-1">· {{ $data->nama_kategori }}</span>
                            </td>
                            <td class="px-4 py-3 text-center font-black text-sm {{ $lv['text'] }}">
                                {{ number_format($data->rata_rata_kategori, 2) }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block rounded-full px-2 py-0.5 text-[10px] font-bold {{ $lv['bg'] }} {{ $lv['text'] }}">
                                    {{ $lv['label'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- REKOMENDASI DARI APPROVER --}}
    <div class="rounded-xl border bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <div>
                <h3 class="font-bold text-on-surface">Rekomendasi dari Approver</h3>
                <p class="text-xs text-gray-500 mt-0.5">Catatan dan saran dari approver untuk persiapan audit formal</p>
            </div>
            <a href="{{ route('approver.rekomendasi.create', $assessment->assessment_id) }}"
               class="inline-flex items-center gap-1 text-xs font-bold text-primary border border-primary px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                <span class="material-symbols-outlined text-sm">add</span> Tambah
            </a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($rekApprover as $rek)
                @php $bp = match($rek->prioritas) { 'Tinggi' => 'bg-red-100 text-red-700', 'Sedang' => 'bg-yellow-100 text-yellow-700', default => 'bg-green-100 text-green-700' }; @endphp
                <div class="flex gap-4 px-6 py-4">
                    <div class="shrink-0 pt-0.5">
                        <span class="inline-block rounded px-2 py-0.5 text-[10px] font-bold bg-purple-100 text-purple-700">Approver</span>
                    </div>
                    <p class="flex-1 text-sm text-gray-700 leading-relaxed">{{ $rek->deskripsi_perbaikan }}</p>
                    <span class="shrink-0 inline-block rounded-full px-2.5 py-0.5 text-[10px] font-bold self-start {{ $bp }}">{{ $rek->prioritas }}</span>
                </div>
            @empty
                <div class="px-6 py-6 text-center text-sm text-gray-500">
                    Belum ada rekomendasi dari approver.
                    <a href="{{ route('approver.rekomendasi.create', $assessment->assessment_id) }}" class="text-primary font-bold hover:underline ml-1">Tambah sekarang →</a>
                </div>
            @endforelse
        </div>
    </div>

    {{-- REKOMENDASI OTOMATIS --}}
    @if($rekOtomatis->count() > 0)
    <div class="rounded-xl border bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h3 class="font-bold text-on-surface">Rekomendasi Otomatis Sistem</h3>
            <p class="text-xs text-gray-500 mt-0.5">Dihasilkan dari teks deskriptif indeks penilaian</p>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($rekOtomatis->take(10) as $rek)
                @php
                    $bp  = match($rek->prioritas) { 'Tinggi' => 'bg-red-100 text-red-700', 'Sedang' => 'bg-yellow-100 text-yellow-700', default => 'bg-green-100 text-green-700' };
                    $kode = $rek->domain->kode ?? '??';
                    $dc   = $domainColor[$kode] ?? 'bg-gray-100 text-gray-700';
                @endphp
                <div class="flex gap-4 px-6 py-3 hover:bg-gray-50">
                    <span class="shrink-0 inline-flex items-center rounded px-2 py-0.5 text-[10px] font-bold self-start mt-0.5 {{ $dc }}">{{ $kode }}</span>
                    <p class="flex-1 text-xs text-gray-600 leading-relaxed">{{ $rek->deskripsi_perbaikan }}</p>
                    <span class="shrink-0 inline-block rounded-full px-2 py-0.5 text-[10px] font-bold self-start {{ $bp }}">{{ $rek->prioritas }}</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx    = document.getElementById('radarChart');
    const skor   = @json($skorPerDomain);
    const order  = ['GV', 'ID', 'PR', 'DE', 'RS', 'RC'];
    const labels = ['Govern', 'Identify', 'Protect', 'Detect', 'Respond', 'Recover'];
    const values = order.map(k => skor[k] || 0);
    const target = {{ $hasils->avg('target_nilai') }};

    new Chart(ctx, {
        type: 'radar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Maturity Level',
                    data: values,
                    fill: true,
                    backgroundColor: 'rgba(175,16,26,0.15)',
                    borderColor: '#af101a',
                    pointBackgroundColor: '#af101a',
                    borderWidth: 2,
                },
                {
                    label: 'Target',
                    data: order.map(() => target),
                    fill: false,
                    borderColor: 'rgba(0,0,0,0.2)',
                    borderDash: [5,5],
                    pointRadius: 0,
                    borderWidth: 1.5,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { font: { size: 11 } } } },
            scales: {
                r: {
                    min: 0, max: 5,
                    ticks: { stepSize: 1, font: { size: 10 } },
                    pointLabels: { font: { size: 11 } }
                }
            }
        }
    });
</script>
@endsection