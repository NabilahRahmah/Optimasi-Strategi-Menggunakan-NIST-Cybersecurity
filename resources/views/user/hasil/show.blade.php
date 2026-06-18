@extends('layouts.dashboard')

@section('content')

    @php
        $levelColor = function ($nilai) {
            if ($nilai >= 4.5)
                return ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Tier 5 – Optimal'];
            if ($nilai >= 3.5)
                return ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Tier 4 – Adaptive'];
            if ($nilai >= 2.5)
                return ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Tier 3 – Repeatable'];
            if ($nilai >= 1.5)
                return ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Tier 2 – Risk Informed'];
            if ($nilai >= 0.5)
                return ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Tier 1 – Partial'];
            return ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Tier 0 – Tidak Ada'];
        };

        $domainColor = [
            'GV' => 'bg-purple-100 text-purple-700',
            'ID' => 'bg-blue-100 text-blue-700',
            'PR' => 'bg-green-100 text-green-700',
            'DE' => 'bg-yellow-100 text-yellow-700',
            'RS' => 'bg-orange-100 text-orange-700',
            'RC' => 'bg-red-100 text-red-700',
        ];

        $nilaiTotal = round($hasils->avg('nilai_kematangan'), 2);
        $levelTotal = $levelColor($nilaiTotal);
    @endphp

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-on-surface">{{ $assessment->judul_assessment }}</h1>
                <p class="text-sm text-gray-500 mt-1">Disubmit pada: {{ $assessment->updated_at->format('d M Y') }}</p>
            </div>
            <a href="{{ route('user.hasil.cetakPDF', $assessment->assessment_id) }}"
                class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-red-800 transition-colors shadow-md">
                <span class="material-symbols-outlined text-lg">picture_as_pdf</span>
                Cetak Laporan PDF
            </a>
        </div>

        {{-- NILAI TOTAL --}}
        <div class="rounded-xl border bg-white p-6 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Nilai Kematangan Keseluruhan</p>
                <div class="flex items-end gap-2">
                    <span class="text-5xl font-black text-on-surface">{{ number_format($nilaiTotal, 2) }}</span>
                    <span class="text-lg text-gray-400 mb-1">/ 5.00</span>
                </div>
                <span
                    class="inline-block mt-2 rounded-full px-3 py-1 text-xs font-bold {{ $levelTotal['bg'] }} {{ $levelTotal['text'] }}">
                    {{ $levelTotal['label'] }}
                </span>
            </div>
            <div class="text-right text-sm text-gray-500">
                <p>Target: <strong>{{ number_format($hasils->avg('target_nilai'), 2) }}</strong></p>
                <p>Gap rata-rata: <strong class="text-red-600">{{ number_format($hasils->avg('gap'), 2) }}</strong></p>
                <p class="mt-1 text-xs">Framework: NIST CSF 2.0</p>
            </div>
        </div>

        {{-- GRID: Maturity Index + Radar Chart --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Maturity Index per Domain --}}
            <div class="rounded-xl border bg-white p-6 shadow-sm">
                <h3 class="font-bold text-on-surface mb-4">Maturity Index per Domain</h3>
                <div class="space-y-4">
                    @foreach($hasils->sortByDesc('nilai_kematangan') as $hasil)
                        @php
                            $pct = ($hasil->nilai_kematangan / 5) * 100;
                            $lv = $levelColor($hasil->nilai_kematangan);
                            $kode = $hasil->domain->kode ?? '??';
                            $dc = $domainColor[$kode] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-bold {{ $dc }}">{{ $kode }}</span>
                                    <span class="text-xs font-bold text-on-surface">{{ $hasil->domain->nama_domain }}</span>
                                </div>
                                <span
                                    class="text-xs font-black text-on-surface">{{ number_format($hasil->nilai_kematangan, 2) }}
                                    / 5.0</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full bg-primary transition-all" style="width: {{ $pct }}%"></div>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-[10px] {{ $lv['text'] }} font-semibold">{{ $lv['label'] }}</span>
                                <span class="text-[10px] text-gray-400">Gap: {{ number_format($hasil->gap, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Radar Chart --}}
            <div class="rounded-xl border bg-white p-6 shadow-sm flex flex-col">
                <h3 class="font-bold text-on-surface mb-4">Radar Visualisasi NIST CSF 2.0</h3>
                <div class="flex-1 flex items-center justify-center" style="min-height:280px">
                    <canvas id="radarChart"></canvas>
                </div>
            </div>
        </div>

        {{-- DETAIL PER KATEGORI NIST CSF --}}
        @if($rataRataPerKategori->count() > 0)
            <div class="rounded-xl border bg-white shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-bold text-on-surface">Rincian Skor per Kategori NIST CSF 2.0</h3>
                    <p class="text-xs text-gray-500 mt-1">Rata-rata nilai per kategori berdasarkan jawaban yang telah
                        diverifikasi</p>
                </div>
                <div class="overflow-x-auto" style="max-height: 360px; overflow-y: auto;">
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 bg-gray-50 border-b">
                            <tr>
                                <th class="text-left px-6 py-3 text-xs font-bold uppercase tracking-wider text-gray-500">Domain
                                </th>
                                <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-gray-500">
                                    Kategori NIST CSF</th>
                                <th class="text-center px-4 py-3 text-xs font-bold uppercase tracking-wider text-gray-500">Skor
                                    Aktual</th>
                                <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-gray-500">Level
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($rataRataPerKategori as $data)
                                @php
                                    $lv = $levelColor($data->rata_rata_kategori);
                                    $dc = $domainColor[$data->kode] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3">
                                        <span class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-bold {{ $dc }}">
                                            {{ $data->kode }} — {{ $data->nama_domain }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-mono text-gray-600">
                                        <span class="font-bold text-gray-800">{{ $data->kode_kategori }}</span>
                                        <span class="ml-1 text-gray-500">· {{ $data->nama_kategori }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-block font-black text-sm {{ $lv['text'] }}">
                                            {{ number_format($data->rata_rata_kategori, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-block rounded-full px-2 py-0.5 text-[10px] font-bold {{ $lv['bg'] }} {{ $lv['text'] }}">
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

        {{-- GAP ANALYSIS --}}
        <div class="rounded-xl border bg-white shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="font-bold text-on-surface">Gap Analysis per Domain</h3>
                <p class="text-xs text-gray-500 mt-1">Selisih antara skor aktual dan target — semakin besar gap, semakin
                    perlu perhatian</p>
            </div>
            <div class="p-6 grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($hasils->sortByDesc('gap') as $hasil)
                    @php
                        $kode = $hasil->domain->kode ?? '??';
                        $dc = $domainColor[$kode] ?? 'bg-gray-100 text-gray-700';
                        $gapPriority = $hasil->gap >= 1.5 ? ['bg-red-50 border-red-200', 'text-red-700', 'Tinggi']
                            : ($hasil->gap >= 0.5 ? ['bg-yellow-50 border-yellow-200', 'text-yellow-700', 'Sedang']
                                : ['bg-green-50 border-green-200', 'text-green-700', 'Rendah']);
                    @endphp
                    <div class="rounded-xl border p-4 {{ $gapPriority[0] }}">
                        <div class="flex items-center justify-between mb-2">
                            <span
                                class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-bold {{ $dc }}">{{ $kode }}</span>
                            <span class="text-[10px] font-bold {{ $gapPriority[1] }}">{{ $gapPriority[2] }}</span>
                        </div>
                        <p class="text-xs text-gray-600 mb-1">{{ $hasil->domain->nama_domain }}</p>
                        <p class="text-2xl font-black {{ $gapPriority[1] }}">{{ number_format($hasil->gap, 2) }}</p>
                        <p class="text-[10px] text-gray-500 mt-1">{{ number_format($hasil->nilai_kematangan, 2) }} →
                            {{ number_format($hasil->target_nilai, 2) }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- REKOMENDASI OTOMATIS --}}
        @if($rekomendasis->count() > 0)
            <div class="rounded-xl border bg-white shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-on-surface">Rekomendasi Perbaikan Otomatis</h3>
                        <p class="text-xs text-gray-500 mt-1">Dihasilkan dari teks deskriptif indeks penilaian — urutkan dari
                            prioritas tertinggi</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-600">
                        {{ $rekomendasis->count() }} rekomendasi
                    </span>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($rekomendasis as $rek)
                        @php
                            $badgePriority = match ($rek->prioritas) {
                                'Tinggi' => 'bg-red-100 text-red-700',
                                'Sedang' => 'bg-yellow-100 text-yellow-700',
                                default => 'bg-green-100 text-green-700',
                            };
                            $kodeRek = $rek->domain->kode ?? '??';
                            $dcRek = $domainColor[$kodeRek] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <div class="flex gap-4 px-6 py-4 hover:bg-gray-50 transition-colors">
                            <div class="shrink-0 pt-0.5">
                                <span class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-bold {{ $dcRek }}">
                                    {{ $kodeRek }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $rek->deskripsi_perbaikan }}</p>
                            </div>
                            <div class="shrink-0">
                                <span class="inline-block rounded-full px-2.5 py-1 text-[10px] font-bold {{ $badgePriority }}">
                                    {{ $rek->prioritas }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="rounded-xl border bg-green-50 border-green-200 p-6 text-center">
                <span class="material-symbols-outlined text-green-600 text-3xl">check_circle</span>
                <p class="text-sm font-bold text-green-700 mt-2">Semua domain sudah mencapai level optimal!</p>
            </div>
        @endif

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('radarChart');
        const dataSkor = @json($skorPerDomain);
        const order = ['GV', 'ID', 'PR', 'DE', 'RS', 'RC'];
        const labels = ['Govern', 'Identify', 'Protect', 'Detect', 'Respond', 'Recover'];
        const values = order.map(k => dataSkor[k] || 0);
        const targets = order.map(() => {{ $hasils->avg('target_nilai') }});

        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Maturity Level',
                        data: values,
                        fill: true,
                        backgroundColor: 'rgba(20, 184, 166, 0.2)',
                        borderColor: 'rgb(20, 184, 166)',
                        pointBackgroundColor: 'rgb(20, 184, 166)',
                        borderWidth: 2,
                    },
                    {
                        label: 'Target',
                        data: targets,
                        fill: false,
                        backgroundColor: 'rgba(239,68,68,0.05)',
                        borderColor: 'rgba(239,68,68,0.5)',
                        borderDash: [5, 5],
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