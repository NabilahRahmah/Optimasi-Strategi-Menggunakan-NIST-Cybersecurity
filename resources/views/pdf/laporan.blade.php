<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Assessment</title>
    <style>
        /* CSS Klasik khusus untuk DOMPDF */
        body { font-family: Helvetica, Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #169B82; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #1A7F64; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 5px 0 0; color: #555; }
        
        .section-title { background: #f4f4f4; padding: 8px 12px; border-left: 4px solid #169B82; margin-top: 25px; font-weight: bold; font-size: 14px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #169B82; color: white; font-weight: bold; }
        .text-center { text-align: center; }
        
        /* Memastikan tabel tidak terpotong berantakan antar halaman */
        tr { page-break-inside: avoid; } 
        
        .radar-container { text-align: center; margin: 20px 0; }
        .radar-container img { max-width: 450px; height: auto; }
        
        .badge-tinggi { color: #dc3545; font-weight: bold; }
        .badge-sedang { color: #ffc107; font-weight: bold; }
        .badge-rendah { color: #28a745; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Hasil Pra-Audit NIST CSF 2.0</h2>
        <p>
            <strong>Nama Karyawan:</strong> {{ $assessment->user->name ?? 'Tidak diketahui' }} &nbsp;|&nbsp; 
            <strong>Tanggal Cetak:</strong> {{ date('d F Y') }}
        </p>
    </div>

    <div class="section-title">1. Ringkasan Skor Kematangan</div>
    <p>Berdasarkan jawaban Self-Assessment dan verifikasi yang telah dilakukan, nilai rata-rata kematangan keamanan informasi saat ini adalah <strong>{{ $nilai_total }} dari 5.00</strong>.</p>

    <div class="radar-container">
        <img src="{{ $urlGrafik }}" alt="Grafik Radar Kematangan">
    </div>

    <div class="section-title">2. Rincian Nilai per Domain</div>
    <table>
        <thead>
            <tr>
                <th>Domain NIST</th>
                <th class="text-center">Skor Saat Ini</th>
                <th class="text-center">Target</th>
                <th class="text-center">Gap (Kesenjangan)</th>
                <th>Level Kematangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasils as $hasil)
            <tr>
                <td><strong>{{ $hasil->domain->nama_domain }}</strong></td>
                <td class="text-center">{{ $hasil->nilai_kematangan }}</td>
                <td class="text-center">{{ $hasil->target_nilai }}</td>
                <td class="text-center">
                    <strong style="color: {{ $hasil->gap > 0 ? '#dc3545' : '#28a745' }};">
                        {{ $hasil->gap > 0 ? '-'.$hasil->gap : '0' }}
                    </strong>
                </td>
                <td>{{ $hasil->level_kematangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">3. Rekomendasi Perbaikan Prioritas</div>
    @if($rekomendasis->isEmpty())
        <p style="color: #28a745; font-weight: bold;">Tidak ada rekomendasi perbaikan. Pertahankan standar keamanan Anda!</p>
    @else
        <table>
            <thead>
                <tr>
                    <th width="15%" class="text-center">Prioritas</th>
                    <th width="20%">Domain</th>
                    <th width="65%">Tindakan Perbaikan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekomendasis as $rek)
                <tr>
                    <td class="text-center">
                        @if($rek->prioritas == 'Tinggi')
                            <span class="badge-tinggi">TINGGI</span>
                        @elseif($rek->prioritas == 'Sedang')
                            <span class="badge-sedang">SEDANG</span>
                        @else
                            <span class="badge-rendah">RENDAH</span>
                        @endif
                    </td>
                    <td><strong>{{ $rek->domain->nama_domain }}</strong></td>
                    <td>{{ $rek->deskripsi_perbaikan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>