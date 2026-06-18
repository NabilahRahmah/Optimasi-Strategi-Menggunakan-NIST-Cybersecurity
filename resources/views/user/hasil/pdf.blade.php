<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Assessment NIST CSF 2.0</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; color: #1a1a1a; line-height: 1.5; }

        .header { background: #1F3864; color: white; padding: 20px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
        .header p  { font-size: 9px; opacity: 0.8; }
        .header-meta { display: flex; justify-content: space-between; margin-top: 10px; font-size: 9px; opacity: 0.9; }

        .section-title {
            font-size: 11px; font-weight: bold; color: #1F3864;
            border-bottom: 2px solid #1F3864; padding-bottom: 4px; margin: 16px 0 10px 0;
        }

        .total-card {
            background: #F0F4FF; border: 1px solid #C7D7F7; border-radius: 6px;
            padding: 14px 18px; margin-bottom: 16px;
        }
        .total-inner { display: flex; justify-content: space-between; align-items: center; }
        .total-score { font-size: 32px; font-weight: bold; color: #1F3864; }
        .total-label { font-size: 9px; color: #666; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
        .level-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 9px; font-weight: bold; margin-top: 4px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th { background: #1F3864; color: white; padding: 6px 8px; text-align: left; font-size: 9px; font-weight: bold; }
        td { padding: 5px 8px; border-bottom: 1px solid #E5E7EB; font-size: 9px; }
        tr:nth-child(even) td { background: #F9FAFB; }

        .domain-badge { display: inline-block; padding: 1px 6px; border-radius: 3px; font-size: 8px; font-weight: bold; }
        .badge-GV { background: #EDE9FE; color: #5B21B6; }
        .badge-ID { background: #DBEAFE; color: #1D4ED8; }
        .badge-PR { background: #DCFCE7; color: #15803D; }
        .badge-DE { background: #FEF9C3; color: #A16207; }
        .badge-RS { background: #FFEDD5; color: #C2410C; }
        .badge-RC { background: #FEE2E2; color: #B91C1C; }

        .progress-wrap { background: #E5E7EB; border-radius: 4px; height: 6px; margin-top: 2px; }
        .progress-bar  { background: #1F3864; border-radius: 4px; height: 6px; }

        .grid-2 { display: table; width: 100%; margin-bottom: 16px; }
        .col-left  { display: table-cell; width: 55%; padding-right: 12px; vertical-align: top; }
        .col-right { display: table-cell; width: 45%; vertical-align: top; }

        .card { background: white; border: 1px solid #E5E7EB; border-radius: 6px; padding: 12px; }

        .footer {
            position: fixed; bottom: 0; left: 0; right: 0;
            padding: 6px 24px; background: #F3F4F6; border-top: 1px solid #E5E7EB;
            font-size: 8px; color: #9CA3AF; display: flex; justify-content: space-between;
        }

        @page { margin: 18mm 16mm 22mm 16mm; }
    </style>
</head>
<body>

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
    $levelColor = function($nilai) {
        if ($nilai >= 4.5) return ['label'=>'Tier 5 – Optimal',       'bg'=>'#DBEAFE','text'=>'#1D4ED8'];
        if ($nilai >= 3.5) return ['label'=>'Tier 4 – Adaptive',      'bg'=>'#DCFCE7','text'=>'#15803D'];
        if ($nilai >= 2.5) return ['label'=>'Tier 3 – Repeatable',    'bg'=>'#FEF9C3','text'=>'#A16207'];
        if ($nilai >= 1.5) return ['label'=>'Tier 2 – Risk Informed', 'bg'=>'#FFEDD5','text'=>'#C2410C'];
        if ($nilai > 0)    return ['label'=>'Tier 1 – Partial',       'bg'=>'#FEE2E2','text'=>'#B91C1C'];
        return                     ['label'=>'Tier 0 – Tidak Ada',     'bg'=>'#F3F4F6','text'=>'#6B7280'];
    };

    $lvTotal = $levelColor($nilai_total);
    $target  = round($hasils->avg('target_nilai'), 2);
    $gap     = round($hasils->avg('gap'), 2);

    // ── Radar SVG data ──────────────────────────────────────────
    $radarOrder  = ['GV','ID','PR','DE','RS','RC'];
    $radarLabels = ['Govern','Identify','Protect','Detect','Respond','Recover'];
    $radarValues = [];
    $radarTargets = [];

    foreach ($radarOrder as $kode) {
        $h = $hasils->first(fn($h) => ($h->domain->kode ?? '') === $kode);
        $radarValues[]  = $h ? (float)$h->nilai_kematangan : 0;
        $radarTargets[] = $h ? (float)$h->target_nilai     : $target;
    }

    $cx = 170; $cy = 170; $r = 130; $n = count($radarOrder); $maxVal = 5.0;

    $radarPoint = function($val, $idx) use ($cx, $cy, $r, $n, $maxVal) {
        $angle = M_PI / 2 + (2 * M_PI * $idx / $n);
        $ratio = $val / $maxVal;
        $x = $cx + $r * $ratio * cos($angle);
        $y = $cy - $r * $ratio * sin($angle);
        return ['x' => round($x, 2), 'y' => round($y, 2)];
    };

    // Grid lines (level 1-5)
    $gridPolygons = [];
    for ($lv = 1; $lv <= 5; $lv++) {
        $pts = [];
        for ($i = 0; $i < $n; $i++) {
            $p = $radarPoint($lv, $i);
            $pts[] = "{$p['x']},{$p['y']}";
        }
        $gridPolygons[] = implode(' ', $pts);
    }

    // Axis lines
    $axisLines = [];
    for ($i = 0; $i < $n; $i++) {
        $p = $radarPoint($maxVal, $i);
        $axisLines[] = ['x' => $p['x'], 'y' => $p['y']];
    }

    // Label positions (slightly outside max grid)
    $labelPositions = [];
    for ($i = 0; $i < $n; $i++) {
        $angle = M_PI / 2 + (2 * M_PI * $i / $n);
        $lx = $cx + ($r + 22) * cos($angle);
        $ly = $cy - ($r + 22) * sin($angle);
        $labelPositions[] = ['x' => round($lx,1), 'y' => round($ly,1), 'label' => $radarLabels[$i]];
    }

    // Value polygon
    $valPoints = [];
    for ($i = 0; $i < $n; $i++) {
        $p = $radarPoint($radarValues[$i], $i);
        $valPoints[] = "{$p['x']},{$p['y']}";
    }

    // Target polygon
    $tgtPoints = [];
    for ($i = 0; $i < $n; $i++) {
        $p = $radarPoint($radarTargets[$i], $i);
        $tgtPoints[] = "{$p['x']},{$p['y']}";
    }
@endphp

{{-- NILAI TOTAL --}}
<div class="total-card">
    <div class="total-inner">
        <div>
            <div class="total-label">Nilai Kematangan Keseluruhan</div>
            <div class="total-score">{{ number_format($nilai_total, 2) }} <span style="font-size:14px;color:#666;">/ 5.00</span></div>
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
            <div style="font-size:10px;font-weight:bold;color:#1F3864;margin-bottom:8px;">Maturity Index per Domain</div>
            @foreach($hasils->sortByDesc('nilai_kematangan') as $hasil)
                @php
                    $kode = $hasil->domain->kode ?? '??';
                    $lv   = $levelColor($hasil->nilai_kematangan);
                    $pct  = min(($hasil->nilai_kematangan / 5) * 100, 100);
                @endphp
                <div style="margin-bottom:7px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:2px;">
                        <span>
                            <span class="domain-badge badge-{{ $kode }}">{{ $kode }}</span>
                            <span style="font-size:9px;font-weight:bold;margin-left:4px;">{{ $hasil->domain->nama_domain }}</span>
                        </span>
                        <span style="font-size:9px;font-weight:bold;">{{ number_format($hasil->nilai_kematangan, 2) }}/5.0</span>
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

    {{-- Kanan: Radar Chart SVG --}}
    <div class="col-right">
        <div class="card" style="text-align:center;">
            <div style="font-size:10px;font-weight:bold;color:#1F3864;margin-bottom:6px;">Radar NIST CSF 2.0</div>

            <svg width="340" height="340" viewBox="0 0 340 340" xmlns="http://www.w3.org/2000/svg">

                {{-- Grid polygons --}}
                @foreach($gridPolygons as $i => $pts)
                    <polygon
                        points="{{ $pts }}"
                        fill="none"
                        stroke="{{ $i === 4 ? '#94A3B8' : '#CBD5E1' }}"
                        stroke-width="{{ $i === 4 ? 1.5 : 0.8 }}"
                        stroke-dasharray="{{ $i < 4 ? '3,3' : 'none' }}"
                    />
                @endforeach

                {{-- Axis lines --}}
                @foreach($axisLines as $pt)
                    <line x1="{{ $cx }}" y1="{{ $cy }}" x2="{{ $pt['x'] }}" y2="{{ $pt['y'] }}"
                          stroke="#CBD5E1" stroke-width="0.8"/>
                @endforeach

                {{-- Grid level labels --}}
                @for($lv = 1; $lv <= 5; $lv++)
                    @php $gp = $radarPoint($lv, 0); @endphp
                    <text x="{{ $gp['x'] + 3 }}" y="{{ $gp['y'] - 2 }}"
                          font-size="7" fill="#94A3B8">{{ $lv }}</text>
                @endfor

                {{-- Target polygon --}}
                <polygon
                    points="{{ implode(' ', $tgtPoints) }}"
                    fill="rgba(239,68,68,0.05)"
                    stroke="#EF4444"
                    stroke-width="1.5"
                    stroke-dasharray="5,3"
                />

                {{-- Value polygon --}}
                <polygon
                    points="{{ implode(' ', $valPoints) }}"
                    fill="rgba(20,184,166,0.25)"
                    stroke="#0D9488"
                    stroke-width="2"
                />

                {{-- Value dots --}}
                @for($i = 0; $i < $n; $i++)
                    @php $vp = $radarPoint($radarValues[$i], $i); @endphp
                    <circle cx="{{ $vp['x'] }}" cy="{{ $vp['y'] }}" r="4" fill="#0D9488" stroke="white" stroke-width="1.5"/>
                    <text x="{{ $vp['x'] }}" y="{{ $vp['y'] - 7 }}"
                          font-size="8" font-weight="bold" fill="#0F766E" text-anchor="middle">
                        {{ number_format($radarValues[$i], 1) }}
                    </text>
                @endfor

                {{-- Axis labels --}}
                @foreach($labelPositions as $lp)
                    <text x="{{ $lp['x'] }}" y="{{ $lp['y'] }}"
                          font-size="9" font-weight="bold" fill="#1F3864"
                          text-anchor="middle" dominant-baseline="middle">
                        {{ $lp['label'] }}
                    </text>
                @endforeach

            </svg>

            {{-- Legend --}}
            <div style="display:flex;justify-content:center;gap:16px;margin-top:4px;font-size:8px;">
                <span>
                    <svg width="20" height="8" style="vertical-align:middle;">
                        <line x1="0" y1="4" x2="20" y2="4" stroke="#0D9488" stroke-width="2"/>
                        <circle cx="10" cy="4" r="3" fill="#0D9488"/>
                    </svg>
                    Nilai Aktual
                </span>
                <span>
                    <svg width="20" height="8" style="vertical-align:middle;">
                        <line x1="0" y1="4" x2="20" y2="4" stroke="#EF4444" stroke-width="1.5" stroke-dasharray="4,2"/>
                    </svg>
                    Target
                </span>
            </div>
        </div>
    </div>
</div>

{{-- RINCIAN PER KATEGORI --}}
@if($rataRataPerKategori->count() > 0)
<div class="section-title">Rincian Skor per Kategori NIST CSF 2.0</div>
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
                <td><span class="domain-badge badge-{{ $data->kode }}">{{ $data->kode }}</span></td>
                <td style="font-family:monospace;font-weight:bold;font-size:8.5px;">{{ $data->kode_kategori }}</td>
                <td>{{ $data->nama_kategori }}</td>
                <td style="text-align:center;font-weight:bold;">{{ number_format($data->rata_rata_kategori, 2) }}</td>
                <td>
                    <span style="background:{{ $lv['bg'] }};color:{{ $lv['text'] }};padding:1px 5px;border-radius:10px;font-size:8px;font-weight:bold;">
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
                $kodeRek  = $rek->domain->kode ?? '??';
                $badgeCls = strtolower($rek->prioritas);
                $badgeColors = [
                    'tinggi' => ['bg'=>'#FEE2E2','text'=>'#B91C1C'],
                    'sedang' => ['bg'=>'#FEF9C3','text'=>'#A16207'],
                    'rendah' => ['bg'=>'#DCFCE7','text'=>'#15803D'],
                ];
                $bc = $badgeColors[$badgeCls] ?? ['bg'=>'#F3F4F6','text'=>'#6B7280'];
            @endphp
            <tr>
                <td><span class="domain-badge badge-{{ $kodeRek }}">{{ $kodeRek }}</span></td>
                <td style="font-size:8.5px;line-height:1.4;">{{ $rek->deskripsi_perbaikan }}</td>
                <td style="text-align:center;">
                    <span style="background:{{ $bc['bg'] }};color:{{ $bc['text'] }};padding:1px 6px;border-radius:10px;font-size:8px;font-weight:bold;">
                        {{ $rek->prioritas }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- FOOTER --}}
<div class="footer">
    <span>CyberAudit Pro — Sistem Informasi Pra-Audit Keamanan Siber</span>
    <span>Dicetak: {{ now()->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
</div>

</body>
</html>