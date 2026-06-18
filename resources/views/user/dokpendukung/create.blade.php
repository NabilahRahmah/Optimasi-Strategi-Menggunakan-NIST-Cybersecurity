@extends('layouts.dashboard')
@php $pageTitle = 'Upload Dokumen' @endphp

@section('content')
<div class="space-y-6">

    <div>
        <a href="{{ route('user.dokpendukung.index') }}"
           style="font-size:13px;color:#64748b;text-decoration:none;">
            ← Dokumen Pendukung
        </a>
        <h1 style="font-size:22px;font-weight:700;margin:8px 0 4px;color:#0f172a;">
            Upload Dokumen
        </h1>
        <p style="font-size:13px;color:#64748b;margin:0;">
            Upload dokumen bukti pendukung per domain NIST CSF 2.0
        </p>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;width:100%;max-width:100%;box-sizing:border-box;">
        <form action="{{ route('user.dokpendukung.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            {{-- Domain --}}
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:11px;font-weight:700;text-transform:uppercase;color:#64748b;margin-bottom:8px;">
                    Domain NIST CSF <span style="color:red;">*</span>
                </label>
                <select name="domain_id" required
                    style="width:100%;height:40px;border:1px solid #cbd5e1;border-radius:8px;padding:0 12px;font-size:14px;background:#fff;outline:none;">
                    <option value="">-- Pilih Domain --</option>
                    @foreach($domains as $domain)
                    <option value="{{ $domain->domain_id }}">
                        {{ $domain->kode }} — {{ $domain->nama_domain }}
                    </option>
                    @endforeach
                </select>
                @error('domain_id')
                <p style="color:red;font-size:12px;margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama Dokumen --}}
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:11px;font-weight:700;text-transform:uppercase;color:#64748b;margin-bottom:8px;">
                    Nama Dokumen <span style="color:red;">*</span>
                </label>
                <input type="text" name="nama_dokumen" required
                       value="{{ old('nama_dokumen') }}"
                       placeholder="Contoh: SOP Pengelolaan Akses User"
                       style="width:100%;height:40px;border:1px solid #cbd5e1;border-radius:8px;padding:0 12px;font-size:14px;box-sizing:border-box;outline:none;">
                @error('nama_dokumen')
                <p style="color:red;font-size:12px;margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- {{-- Jenis Dokumen --}}
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:11px;font-weight:700;text-transform:uppercase;color:#64748b;margin-bottom:8px;">
                    Jenis Dokumen <span style="color:red;">*</span>
                </label>
                <select name="jenis_dokumen" required
                    style="width:100%;height:40px;border:1px solid #cbd5e1;border-radius:8px;padding:0 12px;font-size:14px;background:#fff;outline:none;">
                    <option value="">-- Pilih Jenis --</option>
                    @foreach(['SOP', 'Kebijakan', 'Laporan', 'Screenshot', 'Sertifikat', 'Lainnya'] as $jenis)
                    <option value="{{ $jenis }}" {{ old('jenis_dokumen') === $jenis ? 'selected' : '' }}>
                        {{ $jenis }}
                    </option>
                    @endforeach
                </select>
            </div> -->

            {{-- Deskripsi --}}
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:11px;font-weight:700;text-transform:uppercase;color:#64748b;margin-bottom:8px;">
                    Deskripsi (Opsional)
                </label>
                <textarea name="deskripsi" rows="3"
                          placeholder="Jelaskan isi dokumen ini..."
                          style="width:100%;border:1px solid #cbd5e1;border-radius:8px;padding:10px 12px;font-size:14px;box-sizing:border-box;outline:none;resize:vertical;">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- Upload File --}}
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:11px;font-weight:700;text-transform:uppercase;color:#64748b;margin-bottom:8px;">
                    File Dokumen <span style="color:red;">*</span>
                </label>
                <div style="border:1px dashed #cbd5e1;border-radius:8px;padding:16px;background:#f8fafc;">
                    <input type="file" name="file" required
                           accept=".pdf,.docx,.xlsx,.png,.jpg,.jpeg"
                           style="width:100%;font-size:13px;color:#64748b;">
                    <p style="margin:8px 0 0;font-size:11px;color:#94a3b8;">
                        Format: PDF, DOCX, XLSX, PNG, JPG • Maks: 10MB
                    </p>
                </div>
                @error('file')
                <p style="color:red;font-size:12px;margin-top:4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div style="display:flex;gap:12px;padding-top:16px;border-top:1px solid #f1f5f9;">
                <a href="{{ route('user.dokpendukung.index') }}"
                   style="padding:10px 20px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;font-weight:600;color:#0f172a;text-decoration:none;background:#fff;">
                    Batal
                </a>
                <button type="submit"
                    style="padding:10px 20px;background:#0f172a;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;" class="shadow-md">
                    Upload Dokumen
                </button>
            </div>

        </form>
    </div>
</div>
@endsection