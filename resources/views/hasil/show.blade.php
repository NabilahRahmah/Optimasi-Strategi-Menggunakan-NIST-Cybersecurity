@extends('layouts.master')

@section('content')
<div class="container py-4">

    <h4 class="mb-4">Hasil Assessment</h4>
    <p class="text-muted">{{ $assessment->judul_assessment ?? 'Audit Keamanan Siber' }}</p>

    {{-- Nilai Total --}}
    <div class="card mb-4 border-primary shadow-sm">
        <div class="card-body text-center">
            <h6 class="text-muted">Total Nilai Kematangan</h6>
            <h1 class="text-primary fw-bold">{{ number_format($nilai_total, 2) }}</h1>
            <p class="text-muted">dari target 4.00</p>
        </div>
    </div>

    <div class="row">

        {{-- Radar Chart --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white fw-bold">Grafik Radar Kematangan</div>
                <div class="card-body">
                    <canvas id="radarChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Tabel Hasil Per Domain --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white fw-bold">Nilai Per Domain</div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Domain</th>
                                <th>Nilai</th>
                                <th>Target</th>
                                <th>Gap</th>
                                <th>Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasils as $hasil)
                            <tr>
                                <td>{{ $hasil->domain->nama_domain }}</td>
                                <td>
                                    <span class="{{ $hasil->nilai_kematangan >= $hasil->target_nilai ? 'text-success' : 'text-danger' }} fw-bold">
                                        {{ number_format($hasil->nilai_kematangan, 2) }}
                                    </span>
                                </td>
                                <td>{{ number_format($hasil->target_nilai, 2) }}</td>
                                <td>
                                    @if($hasil->gap > 0)
                                        <span class="badge bg-danger">-{{ number_format($hasil->gap, 2) }}</span>
                                    @else
                                        <span class="badge bg-success">✓</span>
                                    @endif
                                </td>
                                <td><small>{{ $hasil->level_kematangan }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- Tabel Hasil Per Kategori (Detail) --}}
    @if(isset($rataRataPerKategori) && count($rataRataPerKategori) > 0)
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white fw-bold">Rincian Nilai Per Kategori (Detail NIST CSF)</div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th>Fungsi / Domain</th>
                            <th>Kategori</th>
                            <th class="text-center">Skor Aktual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rataRataPerKategori as $data)
                        <tr>
                            <td class="fw-bold">{{ $data->nama_domain }}</td>
                            <td>{{ $data->nama_kategori }}</td>
                            <td class="text-center fw-bold {{ $data->rata_rata_kategori >= 4.0 ? 'text-success' : ($data->rata_rata_kategori >= 3.0 ? 'text-warning' : 'text-danger') }}">
                                {{ number_format($data->rata_rata_kategori, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Rekomendasi Perbaikan --}}
    @if($rekomendasis->count() > 0)
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white fw-bold">Rekomendasi Perbaikan</div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Domain</th>
                        <th>Rekomendasi</th>
                        <th>Prioritas</th>
                        <th>Sumber</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekomendasis as $rek)
                    <tr>
                        <td>{{ $rek->domain->nama_domain }}</td>
                        <td>{{ $rek->deskripsi_perbaikan }}</td>
                        <td>
                            @if($rek->prioritas == 'Tinggi')
                                <span class="badge bg-danger">Tinggi</span>
                            @elseif($rek->prioritas == 'Sedang')
                                <span class="badge bg-warning text-dark">Sedang</span>
                            @else
                                <span class="badge bg-success">Rendah</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $rek->sumber == 'otomatis' ? 'bg-secondary' : 'bg-primary' }}">
                                {{ ucfirst($rek->sumber) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Tombol Cetak --}}
    <button onclick="window.print()" class="btn btn-outline-primary mb-4">
        <i class="fas fa-print"></i> Cetak Laporan Hasil
    </button>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('radarChart').getContext('2d');
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: {!! $chart_labels !!},
            datasets: [
                {
                    label: 'Nilai Kematangan',
                    data: {!! $chart_values !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                },
                {
                    label: 'Target (4.00)',
                    data: Array({{ $hasils->count() }}).fill(4),
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    borderColor: 'rgba(255, 99, 132, 0.6)',
                    borderDash: [5, 5],
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    min: 0,
                    max: 5,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
});
</script>
@endsection