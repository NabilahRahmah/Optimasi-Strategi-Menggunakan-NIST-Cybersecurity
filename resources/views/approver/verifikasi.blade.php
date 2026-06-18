@extends('layouts.master')

@section('title', 'Verifikasi Dokumen')

@section('additional_css')
<style>
    /* Desain Modal (Pop-up) */
    .modal-overlay {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 1000;
        align-items: center; justify-content: center;
    }
    .modal-box {
        background: var(--bg2); width: 450px; border-radius: 12px;
        border: 1px solid var(--border); padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .modal-box h3 { margin: 0 0 16px 0; font-size: 18px; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 12px; color: var(--text2); margin-bottom: 8px; }
    .form-control {
        width: 100%; padding: 10px; background: var(--bg); border: 1px solid var(--border);
        color: var(--text); border-radius: 8px; font-family: var(--font);
    }
    .btn-group { display: flex; gap: 10px; margin-top: 20px; }
    .btn { padding: 10px 16px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; width: 100%; }
    .btn-success { background: var(--success); color: #000; }
    .btn-danger { background: var(--danger); color: #fff; }
    .btn-cancel { background: transparent; border: 1px solid var(--border); color: var(--text); }
</style>
@endsection

@section('content')
<div class="page-header mb-4">
    <h1>Antrean Verifikasi</h1>
    <p>Mohon periksa kesesuaian indeks dengan bukti dokumen yang dilampirkan.</p>
</div>

@if(session('success'))
    <div style="padding: 12px; background: rgba(52, 211, 153, 0.1); color: var(--success); border-left: 4px solid var(--success); margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

<div class="sa-table">
    <div class="sa-thead">
        <div>Karyawan</div>
        <div>Domain NIST</div>
        <div>Indeks</div>
        <div>Bukti Dokumen</div>
        <div>Aksi</div>
    </div>

    @forelse($antrean as $item)
    <div class="sa-row">
        <div>
            <strong style="color: var(--accent);">{{ $item->user->name }}</strong><br>
            <small style="color: var(--text2);">{{ $item->created_at->format('d M Y') }}</small>
        </div>
        <div>{{ $item->framework->domainName }}</div>
        <div>
            <span style="background: var(--surface2); padding: 4px 12px; border-radius: 20px; font-weight: bold;">
                Level {{ $item->nilaiAssessment }}
            </span>
        </div>
        <div>
            @if($item->fileBukti)
                <a href="{{ asset('storage/' . $item->fileBukti) }}" target="_blank" style="color: var(--success); text-decoration: none;">
                    📄 Lihat PDF/Foto
                </a>
            @else
                <span style="color: var(--warning);">Tidak ada lampiran</span>
            @endif
        </div>
        <div>
            <button class="btn btn-cancel" style="padding: 6px 12px; font-size: 12px;" 
                    onclick="openModal({{ $item->id }}, '{{ $item->user->name }}')">
                Review & Putuskan
            </button>
        </div>
    </div>
    @empty
    <div style="padding: 40px; text-align: center; color: var(--text2);">
        Tidak ada dokumen yang menunggu verifikasi saat ini.
    </div>
    @endforelse
</div>

{{-- MODAL REVIEW --}}
<div class="modal-overlay" id="reviewModal">
    <div class="modal-box">
        <h3>Keputusan Verifikasi <span id="modalUserName" style="color: var(--accent);"></span></h3>
        
        <form id="verifikasiForm" method="POST" action="">
            @csrf
            <div class="form-group">
                <label>Komentar / Rekomendasi Perbaikan (Opsional)</label>
                <textarea name="komentar" class="form-control" rows="4" placeholder="Tuliskan alasan jika ditolak, atau rekomendasi jika disetujui..."></textarea>
            </div>

            <div class="btn-group">
                <button type="submit" name="status_keputusan" value="rejected" class="btn btn-danger">Tolak & Revisi</button>
                <button type="submit" name="status_keputusan" value="verified" class="btn btn-success">Setujui Bukti</button>
            </div>
            <button type="button" class="btn btn-cancel" style="margin-top: 10px;" onclick="closeModal()">Batal</button>
        </form>
    </div>
</div>
@endsection

@section('additional_scripts')
<script>
    function openModal(assessmentId, userName) {
        document.getElementById('modalUserName').innerText = '- ' + userName;
        
        // Ubah action URL pada form secara dinamis menggunakan ID assessment
        const form = document.getElementById('verifikasiForm');
        form.action = `/approval/verifikasi/${assessmentId}`;
        
        document.getElementById('reviewModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('reviewModal').style.display = 'none';
    }
</script>
@endsection