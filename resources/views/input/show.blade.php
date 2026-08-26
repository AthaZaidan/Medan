@extends('layouts.app')

@section('title', 'Form Checklist — ' . $kuesioner->kode)
@section('heading', 'Form Evaluasi Kuesioner ' . $kuesioner->kode . ' — ' . $kecamatan->nama)

@section('content')
<div class="space-y-6">

    {{-- Back --}}
    <a href="{{ route('input.progress') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-blue-900 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
        Kembali ke Progress
    </a>

    <div class="admin-card p-4 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase">KUESIONER {{ $kuesioner->kode }}</span>
            <h1 class="text-base font-bold text-slate-900">{{ $kuesioner->nama }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kecamatan: <strong class="font-bold text-slate-800">{{ $kecamatan->nama }}</strong></p>
        </div>
        <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1.5 rounded border border-slate-200">
            Skala Guttman (Ya / Tidak)
        </span>
    </div>

    <form action="{{ route('input.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="kecamatan_id" value="{{ $kecamatan->id }}">
        <input type="hidden" name="kuesioner_id" value="{{ $kuesioner->id }}">

        @foreach($subVariabels as $sv)
            <div class="admin-card overflow-hidden">
                <div class="admin-card-header">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">{{ $sv->nama }}</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Bobot Subtotal Instrumen: {{ $sv->bobot_subtotal }}%</p>
                </div>

                @foreach($sv->indikators as $ind)
                    <div class="border-b border-slate-200 last:border-0" x-data="{ collapsed: false }">
                        <div class="px-5 py-3 bg-slate-50 flex items-center gap-3 cursor-pointer hover:bg-slate-100 transition-colors" @click="collapsed = !collapsed">
                            <span class="text-xs font-mono font-bold text-slate-700 w-8 px-1.5 py-0.5 bg-slate-200 rounded text-center">{{ $ind->kode }}</span>
                            <span class="text-xs text-slate-900 font-bold flex-1">{{ $ind->pernyataan }}</span>
                            <span class="text-[11px] text-slate-500 font-medium">Bobot: {{ $ind->bobot_asli }}%</span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="collapsed && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                        </div>

                        <div x-show="!collapsed" x-transition class="px-5 py-2.5 bg-white space-y-2">
                            @foreach($ind->subItems as $si)
                                @php $currentVal = $existingAnswers[$si->id] ?? null; @endphp
                                <div class="flex items-center gap-4 py-1.5 border-b border-slate-100 last:border-0">
                                    <span class="text-xs font-bold text-slate-400 font-mono w-4">{{ $si->kode }}</span>
                                    <span class="text-xs text-slate-800 font-medium flex-1 leading-relaxed">{{ $si->teks }}</span>
                                    <div class="flex items-center gap-3 flex-shrink-0">
                                        <label class="flex items-center gap-1.5 cursor-pointer px-2.5 py-1 rounded bg-slate-50 border border-slate-300 hover:bg-emerald-50 hover:border-emerald-300 transition-colors">
                                            <input type="radio" name="jawaban[{{ $si->id }}]" value="1"
                                                   {{ $currentVal === true || $currentVal === 1 ? 'checked' : '' }}
                                                   class="w-3.5 h-3.5 text-emerald-600 border-slate-300 focus:ring-emerald-500">
                                            <span class="text-xs font-bold text-slate-800">Ya</span>
                                        </label>
                                        <label class="flex items-center gap-1.5 cursor-pointer px-2.5 py-1 rounded bg-slate-50 border border-slate-300 hover:bg-red-50 hover:border-red-300 transition-colors">
                                            <input type="radio" name="jawaban[{{ $si->id }}]" value="0"
                                                   {{ $currentVal === false || $currentVal === 0 ? 'checked' : '' }}
                                                   class="w-3.5 h-3.5 text-red-600 border-slate-300 focus:ring-red-500">
                                            <span class="text-xs font-bold text-slate-800">Tidak</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        {{-- Submit Button --}}
        <div class="flex justify-end">
            <button type="submit"
                    class="px-6 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded shadow-xs transition-colors text-xs">
                Simpan Jawaban
            </button>
        </div>
    </form>

</div>
@endsection
