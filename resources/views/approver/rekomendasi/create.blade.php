@extends('layouts.dashboard')
@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-on-surface">Kelola Rekomendasi</h1>
            <p class="text-sm text-gray-500 mt-1">
                Assessment: <strong>{{ $assessment->judul_assessment }}</strong>
                · Karyawan: <strong>{{ $assessment->user->name }}</strong>
            </p>
        </div>
        <a href="{{ route('approver.rekomendasi.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 border border-gray-200 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
            <span class="material-symbols-outlined text-base">arrow_back</span> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Form Tambah Rekomendasi --}}
    <div class="rounded-xl border bg-white shadow-sm p-6">
        <h3 class="font-bold text-on-surface mb-4">Tambah Rekomendasi dari Approver</h3>
        <form action="{{ route('approver.rekomendasi.store', $assessment->assessment_id) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Domain NIST CSF</label>
                    <select name="domain_id" required
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
                        <option value="">Pilih domain...</option>
                        @foreach($domains as $domain)
                            <option value="{{ $domain->domain_id }}">
                                {{ $domain->kode }} — {{ $domain->nama_domain }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Prioritas</label>
                    <select name="prioritas" required
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
                        <option value="Tinggi">🔴 Tinggi</option>
                        <option value="Sedang">🟡 Sedang</option>
                        <option value="Rendah">🟢 Rendah</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Deskripsi Rekomendasi</label>
                <textarea name="deskripsi_perbaikan" rows="3" required
                    placeholder="Tuliskan rekomendasi perbaikan yang spesifik untuk persiapan audit..."
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none resize-none"></textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-bold text-white hover:bg-red-800 transition">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Tambah Rekomendasi
                </button>
            </div>
        </form>
    </div>

    {{-- Daftar Rekomendasi --}}
    <div class="rounded-xl border bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="font-bold text-on-surface">Semua Rekomendasi</h3>
            <div class="flex gap-2 text-xs text-gray-500">
                <span class="inline-block rounded-full bg-blue-100 text-blue-700 px-2 py-0.5 font-bold">Otomatis = dari sistem</span>
                <span class="inline-block rounded-full bg-purple-100 text-purple-700 px-2 py-0.5 font-bold">Approver = dari anda</span>
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($rekomendasis as $rek)
                @php
                    $badgePriority = match($rek->prioritas) {
                        'Tinggi' => 'bg-red-100 text-red-700',
                        'Sedang' => 'bg-yellow-100 text-yellow-700',
                        default  => 'bg-green-100 text-green-700',
                    };
                    $badgeSumber = $rek->sumber === 'approver'
                        ? 'bg-purple-100 text-purple-700'
                        : 'bg-blue-100 text-blue-700';
                @endphp
                <div class="flex gap-4 px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="shrink-0 pt-0.5">
                        <span class="inline-block rounded px-2 py-0.5 text-[10px] font-bold bg-gray-100 text-gray-600">
                            {{ $rek->domain->kode ?? '??' }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $rek->deskripsi_perbaikan }}</p>
                    </div>
                    <div class="shrink-0 flex flex-col items-end gap-1.5">
                        <span class="inline-block rounded-full px-2.5 py-0.5 text-[10px] font-bold {{ $badgePriority }}">
                            {{ $rek->prioritas }}
                        </span>
                        <span class="inline-block rounded-full px-2.5 py-0.5 text-[10px] font-bold {{ $badgeSumber }}">
                            {{ ucfirst($rek->sumber) }}
                        </span>
                        @if($rek->sumber === 'approver')
                            <form action="{{ route('approver.rekomendasi.destroy', $rek->rekomendasi_id) }}" method="POST"
                                  onsubmit="return confirm('Hapus rekomendasi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-[10px] text-red-500 hover:text-red-700 font-bold transition">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-sm text-gray-500">
                    Belum ada rekomendasi. Tambahkan rekomendasi di atas.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection