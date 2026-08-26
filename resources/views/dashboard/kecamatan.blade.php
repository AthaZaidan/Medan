@extends('layouts.app')

@section('title', 'Kecamatan ' . $kecamatan->nama)
@section('heading', 'Kecamatan ' . $kecamatan->nama)

@section('content')
<div class="space-y-6">

    {{-- Back navigation --}}
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-blue-900 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
            Kembali ke Dashboard
        </a>

        <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded border border-slate-300 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.6 0-1.104-.467-1.12-1.066L5.88 18m11.78 0H6.34"/></svg>
            Cetak
        </button>
    </div>

    {{-- Header --}}
    <div class="admin-card p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="text-[10px] font-bold text-slate-400 uppercase">Wilayah #{{ $kecamatan->urutan }}</div>
            <h1 class="text-xl font-bold text-slate-900 mt-0.5">Kecamatan {{ $kecamatan->nama }}</h1>
            <p class="text-xs text-slate-500 mt-1">Laporan Hasil Evaluasi QPI-K 2026</p>
        </div>
        <div class="flex items-center gap-4 bg-slate-50 p-3.5 rounded border border-slate-200">
            <div class="text-right">
                <div class="text-[10px] text-slate-400 font-bold uppercase">QPI Inti</div>
                <div class="text-2xl font-bold tabular-nums @if($qpiInti !== null) @include('components._score-color-class', ['score' => $qpiInti]) @else text-slate-300 @endif">
                    {{ $qpiInti !== null ? number_format($qpiInti, 1) : '—' }}
                </div>
            </div>
            @if($kategoriData)
                <div class="flex flex-col items-end">
                    <span class="text-[10px] text-slate-400 font-bold uppercase mb-1">Kategori</span>
                    @include('components._kategori-badge', ['kategori' => $kategoriData['kategori']])
                </div>
            @endif
        </div>
    </div>

    {{-- Floor Warning --}}
    @if($floorStatus === 'FLOOR_AKTIF')
        <div class="p-3.5 rounded bg-amber-50 border border-amber-200 text-xs text-amber-900 font-medium">
            <strong>STATUS FLOOR AKTIF:</strong> Minimal 1 dimensi inti &lt; 50.0. Kategori dibatasi maksimum ke "Perlu Perbaikan (Floor)".
        </div>
    @endif

    {{-- 7 Dimensions Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
        @foreach(['D1','D2','D3','D4','D5','D6','D7'] as $d)
            @php
                $score = $skorDimensi[$d] ?? null;
                $dimNames = ['D1'=>'Kepuasan', 'D2'=>'Tata Kelola', 'D3'=>'Proses Bisnis', 'D4'=>'Digital', 'D5'=>'Anggaran', 'D6'=>'PKH', 'D7'=>'Ketertiban'];
                $isCore = in_array($d, ['D1','D2','D3','D4','D5']);
            @endphp
            <div class="admin-card p-3 text-center {{ !$isCore ? 'bg-slate-50' : '' }}">
                <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $d }}</div>
                <div class="text-xl font-bold mt-1 tabular-nums @if($score !== null) @include('components._score-color-class', ['score' => $score]) @else text-slate-300 @endif">
                    {{ $score !== null ? number_format($score, 1) : '—' }}
                </div>
                <div class="text-[10px] text-slate-500 font-medium mt-1">{{ $dimNames[$d] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Sub-variabel Breakdown --}}
    <div class="admin-card overflow-hidden">
        <div class="admin-card-header">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Breakdown 28 Sub-Variabel</h2>
        </div>
        <div class="divide-y divide-slate-100 bg-white">
            @php $currentKuesioner = null; @endphp
            @foreach($subVariabels as $sv)
                @if($currentKuesioner !== $sv->kuesioner->kode)
                    @php $currentKuesioner = $sv->kuesioner->kode; @endphp
                    <div class="px-5 py-2 bg-slate-100 border-t border-b border-slate-200">
                        <span class="text-[11px] font-bold text-slate-700 uppercase">
                            KUESIONER {{ $sv->kuesioner->kode }} — {{ $sv->kuesioner->nama }}
                        </span>
                    </div>
                @endif
                <div class="px-5 py-3 flex items-center gap-4 hover:bg-slate-50 transition-colors cursor-pointer" x-data="{ open: false }" @click="open = !open">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-slate-900 font-bold truncate">{{ $sv->nama }}</p>
                            <span class="text-xs font-bold ml-3 tabular-nums @if($svScores[$sv->id] !== null) @include('components._score-color-class', ['score' => $svScores[$sv->id]]) @else text-slate-300 @endif">
                                {{ $svScores[$sv->id] !== null ? number_format($svScores[$sv->id], 1) : '—' }}
                            </span>
                        </div>
                        <div class="h-1.5 bg-slate-100 rounded overflow-hidden mt-1.5 border border-slate-200">
                            <div class="h-full transition-all duration-300 @include('components._score-bg-class', ['score' => $svScores[$sv->id] ?? 0])"
                                 style="width: {{ $svScores[$sv->id] ?? 0 }}%"></div>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 transition-transform flex-shrink-0" :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                </div>
                {{-- Indicator details --}}
                <template x-if="open">
                    <div class="px-5 py-2 bg-slate-50 space-y-1 border-t border-slate-200">
                        @foreach($sv->indikators as $ind)
                            @php $indScore = $skorIndikatorCache[$ind->id] ?? null; @endphp
                            <div class="flex items-center gap-3 text-xs py-1 border-b border-slate-200 last:border-0">
                                <span class="w-8 text-slate-500 font-mono font-bold text-[10px]">{{ $ind->kode }}</span>
                                <span class="flex-1 text-slate-700">{{ $ind->pernyataan }}</span>
                                <span class="font-bold text-xs tabular-nums @if($indScore !== null) @include('components._score-color-class', ['score' => $indScore]) @else text-slate-300 @endif">
                                    {{ $indScore !== null ? number_format($indScore, 1) : '—' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </template>
            @endforeach
        </div>
    </div>

</div>
@endsection
