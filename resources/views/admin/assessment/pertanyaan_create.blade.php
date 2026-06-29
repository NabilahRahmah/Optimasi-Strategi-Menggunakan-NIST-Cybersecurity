@extends('layouts.dashboard')
@section('content')
<div class="space-y-6">
    <nav class="flex items-center gap-2 text-sm text-muted-foreground">
        <span>Platform</span><span>/</span>
        <a href="{{ route('admin.assessment.index') }}" class="hover:text-foreground">Assessment</a>
        <span>/</span>
        <span class="font-medium text-foreground">Tambah Pertanyaan</span>
    </nav>

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Tambah Pertanyaan</h1>
            <p class="text-sm text-muted-foreground mt-1">Tambahkan pertanyaan ke dalam kategori yang dipilih.</p>
        </div>
        <a href="{{ route('admin.assessment.edit', $frameworkId) }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800 border border-gray-200 px-4 py-2 rounded-md hover:bg-gray-50 transition">
            ← Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.pertanyaan.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-5">
                <div class="rounded-xl border bg-card shadow-sm p-6 space-y-5">

                    <div>
                        <label class="block text-sm font-semibold text-foreground mb-1.5">Kategori <span class="text-red-500">*</span></label>
                        @if($selectedId)
                            @php
                                $selectedKat = collect($kategoris)->firstWhere('kategori_id', $selectedId);
                            @endphp
                            <input type="hidden" name="kategori_id" value="{{ $selectedId }}">
                            <div class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-gray-100 text-gray-500 cursor-not-allowed">
                                {{ $selectedKat ? '[' . $selectedKat->kode_kategori . '] ' . $selectedKat->nama_kategori : 'Kategori Terpilih' }}
                            </div>
                        @else
                            <select name="kategori_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary/40 @error('kategori_id') border-red-400 @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->kategori_id }}"
                                        {{ (old('kategori_id') == $kat->kategori_id) ? 'selected' : '' }}>
                                        [{{ $kat->kode_kategori }}] {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                        @error('kategori_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-foreground mb-1.5">Kode Pertanyaan <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_pertanyaan" value="{{ old('kode_pertanyaan') }}"
                               placeholder="Contoh: GV.OC-01"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm font-mono bg-white focus:outline-none focus:ring-2 focus:ring-primary/40 @error('kode_pertanyaan') border-red-400 @enderror">
                        @error('kode_pertanyaan')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-foreground mb-1.5">Pertanyaan <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}"
                               placeholder="Masukkan pertanyaan"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary/40 @error('judul') border-red-400 @enderror">
                        @error('judul')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-foreground mb-1.5">
                            Deskripsi <span class="text-xs font-normal text-muted-foreground">(opsional)</span>
                        </label>
                        <textarea name="deskripsi" rows="4"
                                  placeholder="Penjelasan lebih lanjut..."
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary/40 resize-none">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.assessment.edit', $frameworkId) }}"
                       class="px-5 py-2.5 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Batal</a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-primary hover:bg-primary/90 rounded-lg transition">
                        Simpan Pertanyaan
                    </button>
                </div>
            </div>

            <div class="space-y-4">
                <div class="rounded-xl border bg-card shadow-sm p-5">
                    <p class="text-sm font-semibold text-foreground mb-3">Skala Penilaian</p>
                    <div class="space-y-2">
                        @foreach([[0,'Tidak Ada','bg-red-100 text-red-700'],[1,'Awal','bg-orange-100 text-orange-700'],[2,'Berulang','bg-yellow-100 text-yellow-700'],[3,'Terdefinisi','bg-green-100 text-green-700'],[4,'Terkelola','bg-blue-100 text-blue-700'],[5,'Inovatif','bg-purple-100 text-purple-700']] as [$idx, $label, $cls])
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold {{ $cls }} shrink-0">{{ $idx }}</span>
                                <span class="text-xs font-medium text-foreground">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection