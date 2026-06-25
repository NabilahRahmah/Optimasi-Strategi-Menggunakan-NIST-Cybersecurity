<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Assessment NIST CSF 2.0</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #1a1a1a;
            line-height: 1.5;
            /* Tambahkan padding bawah agar konten tidak tertimpa footer */
            padding-bottom: 30px;
        }

        .header {
            background: #1F3864;
            color: white;
            padding: 20px 24px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 9px;
            opacity: 0.8;
        }

        .header-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 9px;
            opacity: 0.9;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #1F3864;
            border-bottom: 2px solid #1F3864;
            padding-bottom: 4px;
            margin: 16px 0 10px 0;
            /* Cegah judul terpisah dari tabel di halaman baru */
            page-break-after: avoid;
        }

        .total-card {
            background: #F0F4FF;
            border: 1px solid #C7D7F7;
            border-radius: 6px;
            padding: 14px 18px;
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        .total-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-score {
            font-size: 32px;
            font-weight: bold;
            color: #1F3864;
        }

        .total-label {
            font-size: 9px;
            color: #666;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .level-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: bold;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        /* Cegah row tabel kepotong di tengah-tengah halaman */
        tr {
            page-break-inside: avoid;
        }

        th {
            background: #1F3864;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }

        td {
            padding: 5px 8px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 9px;
        }

        tr:nth-child(even) td {
            background: #F9FAFB;
        }

        .domain-badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        /* Domain Colors */
        .badge-GV {
            background: #EDE9FE;
            color: #5B21B6;
        }

        .badge-ID {
            background: #DBEAFE;
            color: #1D4ED8;
        }

        .badge-PR {
            background: #DCFCE7;
            color: #15803D;
        }

        .badge-DE {
            background: #FEF9C3;
            color: #A16207;
        }

        .badge-RS {
            background: #FFEDD5;
            color: #C2410C;
        }

        .badge-RC {
            background: #FEE2E2;
            color: #B91C1C;
        }

        .progress-wrap {
            background: #E5E7EB;
            border-radius: 4px;
            height: 6px;
            margin-top: 2px;
        }

        .progress-bar {
            background: #1F3864;
            border-radius: 4px;
            height: 6px;
        }

        .grid-2 {
            display: table;
            width: 100%;
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        .col-left {
            display: table-cell;
            width: 55%;
            padding-right: 12px;
            vertical-align: top;
        }

        .col-right {
            display: table-cell;
            width: 45%;
            vertical-align: top;
        }

        .card {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            padding: 12px;
        }

        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            padding: 6px 24px;
            background: #F3F4F6;
            border-top: 1px solid #E5E7EB;
            font-size: 8px;
            color: #9CA3AF;
            display: flex;
            justify-content: space-between;
        }

        @page {
            margin: 18mm 16mm 22mm 16mm;
        }
    </style>
</head>

<body>

    {{-- FOOTER DILETAKKAN DI SINI (Di bawah body langsung) --}}
    {{-- Ini best practice untuk library PDF agar fixed bottom berfungsi di tiap halaman --}}
    <div class="footer">
        <span>CyberAudit Pro — Sistem Informasi Pra-Audit Keamanan Siber</span>
        <span>Dicetak: {{ now()->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
    </div>

    {{-- HEADER --}}
    <div class="header">
        <h1>Laporan Self Assessment Keamanan Siber</h1>
        <p>Framework: NIST CSF 2.0</p>
        <div class="header-meta">
            <span>Nama: {{ $assessment->user->name ?? '-' }}</span>
            <span>Judul: {{ $assessment->judul_assessment }}</span>
            <span>Dicetak: {{ now()->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
        </div>
    </div>

    @php
        $levelColor = function ($nilai) {
            if ($nilai >= 4.5)
                return ['label' => 'Tier 5 – Optimal', 'bg' => '#DBEAFE', 'text' => '#1D4ED8'];
            if ($nilai >= 3.5)
                return ['label' => 'Tier 4 – Adaptive', 'bg' => '#DCFCE7', 'text' => '#15803D'];
            if ($nilai >= 2.5)
                return ['label' => 'Tier 3 – Repeatable', 'bg' => '#FEF9C3', 'text' => '#A16207'];
            if ($nilai >= 1.5)
                return ['label' => 'Tier 2 – Risk Informed', 'bg' => '#FFEDD5', 'text' => '#C2410C'];
            if ($nilai > 0)
                return ['label' => 'Tier 1 – Partial', 'bg' => '#FEE2E2', 'text' => '#B91C1C'];
            return ['label' => 'Tier 0 – Tidak Ada', 'bg' => '#F3F4F6', 'text' => '#6B7280'];
        };

        $lvTotal = $levelColor($nilai_total);
        $target = round($hasils->avg('target_nilai'), 2);
        $gap = round($hasils->avg('gap'), 2);
    @endphp

    {{-- NILAI TOTAL --}}
    <div class="total-card">
        <div class="total-inner">
            <div>
                <div class="total-label">Nilai Kematangan Keseluruhan</div>
                <div class="total-score">{{ number_format($nilai_total, 2) }} <span style="font-size:14px;color:#666;">/
                        5.00</span></div>
                <span class="level-badge" style="background:{{ $lvTotal['bg'] }};color:{{ $lvTotal['text'] }};">
                    {{ $lvTotal['label'] }}
                </span>
            </div>
            <div style="text-align:right;font-size:9px;color:#555;">
                <div>Target: <strong>{{ number_format($target, 2) }}</strong></div>
                <div>Gap rata-rata: <strong style="color:#B91C1C;">{{ number_format($gap, 2) }}</strong></div>
                <div style="margin-top:4px;font-size:8px;color:#999;">Framework: NIST CSF 2.0</div>
            </div>
        </div>
    </div>

    {{-- GRID: Maturity Index + Radar Chart --}}
    <div class="grid-2">

        {{-- Kiri: Maturity Index --}}
        <div class="col-left">
            <div class="card">
                <div style="font-size:10px;font-weight:bold;color:#1F3864;margin-bottom:8px;">Maturity Index per Domain
                </div>
                @foreach($hasils->sortByDesc('nilai_kematangan') as $hasil)
                    @php
                        $kode = $hasil->domain->kode_domain ?? '??';
                        $lv = $levelColor($hasil->nilai_kematangan);
                        $pct = min(($hasil->nilai_kematangan / 5) * 100, 100);
                    @endphp
                    <div style="margin-bottom:7px;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:2px;">
                            <span>
                                <span class="domain-badge badge-{{ $kode }}">{{ $kode }}</span>
                                <span
                                    style="font-size:9px;font-weight:bold;margin-left:4px;">{{ $hasil->domain->nama_domain }}</span>
                            </span>
                            <span
                                style="font-size:9px;font-weight:bold;">{{ number_format($hasil->nilai_kematangan, 2) }}/5.0</span>
                        </div>
                        <div class="progress-wrap">
                            <div class="progress-bar" style="width:{{ $pct }}%;"></div>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-top:1px;">
                            <span style="font-size:8px;color:{{ $lv['text'] }};font-weight:bold;">{{ $lv['label'] }}</span>
                            <span style="font-size:8px;color:#9CA3AF;">Gap: {{ number_format($hasil->gap, 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Kanan: Radar Chart dari Canvas --}}
        <div class="col-right">
            <div class="card" style="text-align:center;">
                <div style="font-size:10px;font-weight:bold;color:#1F3864;margin-bottom:6px;">Radar NIST CSF 2.0</div>
                @if(!empty($radarImage))
                    <img src="{{ $radarImage }}" style="width:280px;height:280px;display:block;margin:0 auto;">
                @else
                    <p style="font-size:9px;color:#999;margin-top:40px;">Grafik tidak tersedia</p>
                @endif
            </div>
        </div>

    </div> {{-- <-- INI TAG PENUTUP DIV GRID-2 YANG SEBELUMNYA HILANG --}} {{-- RINCIAN PER KATEGORI --}}
        @if($rataRataPerKategori->count() > 0) <div class="section-title">Rincian Skor per Kategori NIST CSF 2.0</div>
            <table>
                <thead>
                    <tr>
                        <th style="width:8%">Domain</th>
                        <th style="width:14%">Kode</th>
                        <th style="width:46%">Nama Kategori</th>
                        <th style="width:13%;text-align:center">Skor</th>
                        <th style="width:19%">Level</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rataRataPerKategori as $data)
                        @php $lv = $levelColor($data->rata_rata_kategori); @endphp
                        <tr>
                            <td><span class="domain-badge badge-{{ $data->kode_domain }}">{{ $data->kode_domain }}</span></td>
                            <td style="font-family:monospace;font-weight:bold;font-size:8.5px;">{{ $data->kode_kategori }}</td>
                            <td>{{ $data->nama_kategori }}</td>
                            <td style="text-align:center;font-weight:bold;">{{ number_format($data->rata_rata_kategori, 2) }}
                            </td>
                            <td>
                                <span
                                    style="background:{{ $lv['bg'] }};color:{{ $lv['text'] }};padding:1px 5px;border-radius:10px;font-size:8px;font-weight:bold;">
                                    {{ $lv['label'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- REKOMENDASI --}}
        @if($rekomendasis->count() > 0)
            <div class="section-title">Rekomendasi Perbaikan Otomatis ({{ $rekomendasis->count() }} item)</div>
            <table>
                <thead>
                    <tr>
                        <th style="width:6%">Domain</th>
                        <th style="width:75%">Deskripsi Rekomendasi</th>
                        <th style="width:19%;text-align:center">Prioritas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekomendasis as $rek)
                        @php
                            $kodeRek = $rek->domain->kode_domain ?? '??';
                            $badgeCls = strtolower($rek->prioritas);
                            $badgeColors = [
                                'tinggi' => ['bg' => '#FEE2E2', 'text' => '#B91C1C'],
                                'sedang' => ['bg' => '#FEF9C3', 'text' => '#A16207'],
                                'rendah' => ['bg' => '#DCFCE7', 'text' => '#15803D'],
                            ];
                            $bc = $badgeColors[$badgeCls] ?? ['bg' => '#F3F4F6', 'text' => '#6B7280'];
                        @endphp
                        <tr>
                            <td><span class="domain-badge badge-{{ $kodeRek }}">{{ $kodeRek }}</span></td>
                            <td style="font-size:8.5px;line-height:1.4;">{{ $rek->deskripsi_perbaikan }}</td>
                            <td style="text-align:center;">
                                <span
                                    style="background:{{ $bc['bg'] }};color:{{ $bc['text'] }};padding:1px 6px;border-radius:10px;font-size:8px;font-weight:bold;">
                                    {{ ucfirst($rek->prioritas) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

</body>

</html>