@extends('layouts.dashboard')
@php $pageTitle = 'Dokumen Pendukung' @endphp

@section('content')
    <div class="space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-foreground">Dokumen Pendukung</h1>
            <p class="text-sm text-muted-foreground">
                Total {{ $totalDokumen }} dokumen dari semua user
            </p>
        </div>

        {{-- Statistik per Domain --}}
        <div style="display:grid;grid-template-columns:repeat(6,1fr);gap:12px;">
            @foreach($domains as $domain)
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:16px;text-align:center;">
                    <span
                        style="display:inline-flex;width:36px;height:36px;align-items:center;justify-content:center;background:#0f172a;color:#fff;border-radius:8px;font-size:12px;font-weight:700;margin-bottom:8px;">
                        {{ $domain->kode }}
                    </span>
                    <p style="margin:0;font-size:22px;font-weight:700;color:#0f172a;">
                        {{ $domain->dokumen_pendukungs_count }}
                    </p>
                    <p style="margin:0;font-size:11px;color:#64748b;">{{ $domain->nama_domain }}</p>
                </div>
            @endforeach
        </div>

        {{-- Tab Domain --}}
        <div style="display:flex;gap:8px;flex-wrap:wrap;" id="domainTabs">
            @foreach($domains as $index => $domain)
                <button type="button" onclick="switchTab('panel-{{ $domain->kode }}', this)"
                    style="padding:8px 16px;font-size:13px;font-weight:600;border-radius:6px;border:1px solid #e2e8f0;background:{{ $index === 0 ? '#0f172a' : '#fff' }};color:{{ $index === 0 ? '#fff' : '#64748b' }};cursor:pointer;"
                    class="tab-btn">
                    {{ $domain->kode }}
                    <span style="font-size:11px;opacity:0.7;">({{ $domain->dokumenPendukungs->count() }})</span>
                </button>
            @endforeach
        </div>

        {{-- Panel per Domain --}}
        @foreach($domains as $index => $domain)
            <div id="panel-{{ $domain->kode }}" style="{{ $index === 0 ? '' : 'display:none;' }}">
                <div class="rounded-xl border bg-card overflow-hidden">

                    <div
                        style="display:flex;align-items:center;gap:12px;padding:16px 24px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                        <span
                            style="background:#0f172a;color:#fff;padding:6px 12px;border-radius:8px;font-weight:700;font-size:13px;">
                            {{ $domain->kode }}
                        </span>
                        <div>
                            <p style="margin:0;font-weight:700;color:#0f172a;">{{ $domain->nama_domain }}</p>
                            <p style="margin:0;font-size:12px;color:#64748b;">{{ $domain->dokumenPendukungs->count() }} dokumen
                            </p>
                        </div>
                    </div>

                    @if($domain->dokumenPendukungs->isEmpty())
                        <div style="padding:40px;text-align:center;color:#94a3b8;font-size:14px;">
                            Belum ada dokumen untuk domain ini.
                        </div>
                    @else
                        <table style="width:100%;border-collapse:collapse;font-size:13px;">
                            <thead>
                                <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                                    <th
                                        style="padding:12px 24px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">
                                        Dokumen</th>
                                    <th
                                        style="padding:12px 24px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">
                                        Uploader</th>
                                    <th
                                        style="padding:12px 24px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">
                                        Tanggal</th>
                                    <th
                                        style="padding:12px 24px;text-align:center;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($domain->dokumenPendukungs as $dok)
                                    @php $ext = strtolower(pathinfo($dok->nama_file_asli, PATHINFO_EXTENSION)); @endphp
                                    <tr style="border-bottom:1px solid #f1f5f9;">
                                        <td style="padding:14px 24px;">
                                            <div style="display:flex;align-items:center;gap:10px;">
                                                <div
                                                    style="width:34px;height:34px;border-radius:7px;background:{{ $ext === 'pdf' ? '#fee2e2' : ($ext === 'xlsx' ? '#dcfce7' : ($ext === 'docx' ? '#dbeafe' : '#f3f4f6')) }};display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:800;color:{{ $ext === 'pdf' ? '#dc2626' : ($ext === 'xlsx' ? '#16a34a' : ($ext === 'docx' ? '#2563eb' : '#6b7280')) }};flex-shrink:0;">
                                                    {{ strtoupper($ext) }}
                                                </div>
                                                <div>
                                                    <p style="margin:0;font-weight:600;color:#0f172a;">{{ $dok->nama_dokumen }}</p>
                                                    <p style="margin:0;font-size:11px;color:#64748b;">{{ $dok->nama_file_asli }} •
                                                        {{ $dok->ukuran_format ?? '-' }}</p>
                                                    @if($dok->deskripsi)
                                                        <p style="margin:4px 0 0;font-size:11px;color:#94a3b8;">{{ $dok->deskripsi }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding:14px 24px;">
                                            <p style="margin:0;font-size:13px;color:#0f172a;">{{ $dok->user->name }}</p>
                                            <p style="margin:0;font-size:11px;color:#64748b;">{{ $dok->user->email }}</p>
                                        </td>
                                        <td style="padding:14px 24px;font-size:12px;color:#64748b;">
                                            {{ $dok->created_at->format('d M Y') }}
                                        </td>
                                        <td style="padding:14px 24px;text-align:center;">
                                            <div style="display:flex;gap:8px;justify-content:center;">
                                                @if(in_array($ext, ['pdf', 'jpg', 'jpeg', 'png']))
                                                    <button type="button"
                                                        onclick="openPreview('{{ route('admin.dokpendukung.preview', $dok->dok_id) }}', '{{ $dok->nama_dokumen }}', '{{ $ext }}')"
                                                        style="padding:6px 12px;border:1px solid #e2e8f0;border-radius:6px;font-size:12px;font-weight:600;color:#0f172a;background:#fff;cursor:pointer;">
                                                        👁 Preview
                                                    </button>
                                                @endif
                                                <a href="{{ route('admin.dokpendukung.download', $dok->dok_id) }}"
                                                    style="padding:6px 12px;border:1px solid #e2e8f0;border-radius:6px;font-size:12px;font-weight:600;color:#0f172a;text-decoration:none;background:#fff;">
                                                    ↓ Download
                                                </a>
                                                <form action="{{ route('admin.dokpendukung.destroy', $dok->dok_id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus dokumen ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        style="padding:6px 12px;border:1px solid #fca5a5;border-radius:6px;font-size:12px;font-weight:600;color:#dc2626;background:#fff;cursor:pointer;">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endforeach

    </div>

    {{-- PREVIEW MODAL --}}
    <div id="previewModal"
        style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);"
        onclick="closePreviewOnBackdrop(event)">
        <div
            style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:90vw;max-width:960px;height:90vh;background:#fff;border-radius:16px;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 25px 60px rgba(0,0,0,0.4);">
            <div
                style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid #e2e8f0;background:#f8fafc;flex-shrink:0;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:18px;">📄</span>
                    <span id="previewTitle" style="font-weight:700;font-size:15px;color:#0f172a;"></span>
                </div>
                <button onclick="closePreview()"
                    style="width:32px;height:32px;border:none;background:#e2e8f0;border-radius:8px;font-size:16px;cursor:pointer;color:#64748b;">
                    ✕
                </button>
            </div>
            <div id="previewBody"
                style="flex:1;overflow:auto;background:#f1f5f9;display:flex;align-items:center;justify-content:center;">
            </div>
        </div>
    </div>

    <script>
        function switchTab(panelId, btn) {
            @foreach($domains as $domain)
                document.getElementById('panel-{{ $domain->kode }}').style.display = 'none';
            @endforeach
            document.querySelectorAll('.tab-btn').forEach(b => { b.style.background = '#fff'; b.style.color = '#64748b'; });
            document.getElementById(panelId).style.display = 'block';
            btn.style.background = '#0f172a';
            btn.style.color = '#fff';
        }
        function openPreview(url, nama, ext) {
            document.getElementById('previewTitle').textContent = nama;
            const body = document.getElementById('previewBody');
            body.innerHTML = ext === 'pdf'
                ? `<iframe src="${url}" style="width:100%;height:100%;border:none;" type="application/pdf"></iframe>`
                : `<img src="${url}" alt="${nama}" style="max-width:100%;max-height:100%;object-fit:contain;padding:16px;">`;
            document.getElementById('previewModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        function closePreview() {
            document.getElementById('previewModal').style.display = 'none';
            document.getElementById('previewBody').innerHTML = '';
            document.body.style.overflow = '';
        }
        function closePreviewOnBackdrop(e) {
            if (e.target === document.getElementById('previewModal')) closePreview();
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closePreview(); });
    </script>
@endsection