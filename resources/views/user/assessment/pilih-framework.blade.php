@extends('layouts.dashboard')
@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold tracking-tight text-foreground">Self Assessment</h1>
        <p class="text-sm text-muted-foreground mt-1">Pilih framework yang ingin Anda assessment.</p>
    </div>

    @if(session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if($frameworks->isEmpty())
        <div class="rounded-xl border bg-white shadow-sm p-12 text-center">
            <div class="flex flex-col items-center gap-3">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-300">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <p class="text-sm font-semibold text-gray-600">Belum ada framework yang ditugaskan</p>
                <p class="text-xs text-gray-400">Hubungi Admin untuk mendapatkan akses ke framework assessment.</p>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($frameworks as $fw)
                @php
                    $totalKategori   = $fw->domains->sum(fn($d) => $d->kategoris->count());
                    $totalPertanyaan = $fw->domains->sum(fn($d) => $d->kategoris->sum(fn($k) => $k->pertanyaans->count()));
                @endphp
                <a href="{{ route('user.assessment.index', ['framework_id' => $fw->framework_id]) }}"
                    class="block rounded-xl border bg-white shadow-sm hover:shadow-md hover:border-primary/30 transition-all group p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 bg-primary/10 text-primary rounded-lg group-hover:bg-primary group-hover:text-white transition-colors">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium px-2 py-1 rounded-full bg-green-100 text-green-700">Aktif</span>
                    </div>
                    <h3 class="text-base font-bold text-gray-800 mb-1">{{ $fw->name_framework }}</h3>
                    @if($fw->description)
                        <p class="text-xs text-gray-500 mb-4 line-clamp-2">{{ $fw->description }}</p>
                    @endif
                    <div class="flex items-center gap-4 text-xs text-gray-400 border-t pt-3 mt-3">
                        <span><span class="font-semibold text-gray-600">{{ $fw->domains->count() }}</span> Domain</span>
                        <span><span class="font-semibold text-gray-600">{{ $totalKategori }}</span> Kategori</span>
                        <span><span class="font-semibold text-gray-600">{{ $totalPertanyaan }}</span> Pertanyaan</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

</div>
@endsection