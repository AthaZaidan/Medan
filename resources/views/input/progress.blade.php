@extends('layouts.app')

@section('title', 'Progress Input Data')
@section('heading', 'Progress Input Data')

@section('content')
@php
    $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    $labelPeriode = ($bulan && $tahun) ? ($namaBulan[$bulan] . ' ' . $tahun) : null;
    $currentYear = date('Y');
@endphp
<div class="space-y-6">

    {{-- ============================================================ --}}
    {{-- Filter Periode (WAJIB dipilih sebelum input)                 --}}
    {{-- ============================================================ --}}
    <div class="admin-card p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                    Pilih Periode Input
                </h2>
                <p class="text-[11px] text-slate-500 mt-0.5">
                    @if($labelPeriode)
                        Periode aktif: <strong class="text-blue-900 font-bold">{{ $labelPeriode }}</strong>. Klik sel untuk mengisi data.
                    @else
                        <span class="text-amber-700 font-semibold">⚠ Pilih bulan dan tahun terlebih dahulu sebelum menginput data.</span>
                    @endif
                </p>
            </div>
            <form method="GET" action="{{ route('input.progress') }}"
                  class="flex flex-wrap items-end gap-2">
                <div class="flex flex-col gap-0.5">
                    <label for="prog-bulan" class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Bulan</label>
                    <select id="prog-bulan" name="bulan"
                            class="px-3 py-1.5 text-xs border border-slate-300 rounded bg-white text-slate-900 font-semibold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">-- Pilih --</option>
                        @foreach($namaBulan as $num => $nama)
                            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-0.5">
                    <label for="prog-tahun" class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Tahun</label>
                    <select id="prog-tahun" name="tahun"
                            class="px-3 py-1.5 text-xs border border-slate-300 rounded bg-white text-slate-900 font-semibold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">-- Pilih --</option>
                        @for($y = $currentYear; $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit"
                        class="px-4 py-1.5 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded text-xs transition-colors">
                    Terapkan
                </button>
                @if($bulan || $tahun)
                    <a href="{{ route('input.progress') }}"
                       class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded text-xs transition-colors border border-slate-300">
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Periode tersedia --}}
    @if($periodeList->isNotEmpty())
    <div class="admin-card p-3">
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Periode Tersedia</p>
        <div class="flex flex-wrap gap-2">
            @foreach($periodeList as $p)
                <a href="{{ route('input.progress', ['bulan' => $p['bulan'], 'tahun' => $p['tahun']]) }}"
                   class="px-2.5 py-1 rounded text-[11px] font-bold border transition-colors
                   {{ ($bulan == $p['bulan'] && $tahun == $p['tahun']) ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-slate-700 border-slate-300 hover:bg-blue-50 hover:border-blue-400' }}">
                    {{ $p['label'] }}
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- Matriks Progress                                              --}}
    {{-- ============================================================ --}}
    <div class="admin-card overflow-hidden">
        <div class="admin-card-header flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Matriks Progress Kelengkapan Data</h2>
            <span class="text-[11px] text-slate-500">
                @if($labelPeriode)
                    Periode: <strong class="text-slate-800">{{ $labelPeriode }}</strong>
                @else
                    Pilih periode di atas terlebih dahulu
                @endif
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold uppercase tracking-wider">
                        <th class="px-4 py-3 text-left sticky left-0 bg-slate-50 z-10 border-r border-slate-200">Kecamatan</th>
                        @foreach($kuesioners as $k)
                            <th class="px-3 py-3 text-center">
                                <div class="text-slate-900 font-bold">Modul {{ $k->kode }}</div>
                                <div class="text-[10px] normal-case text-slate-500 font-normal mt-0.5">{{ Str::limit($k->nama, 16) }}</div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach($progressData as $row)
                        @php
                            $isAssignedArea = auth()->check() && auth()->user()->isUserArea() && (int) auth()->user()->kecamatan_id === (int) $row['kecamatan']->id;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $isAssignedArea ? 'bg-blue-50/50' : '' }}">
                            <td class="px-4 py-2.5 font-bold text-slate-900 sticky left-0 bg-white z-10 text-xs border-r border-slate-200">
                                <div class="flex items-center gap-1.5">
                                    <span>{{ $row['kecamatan']->nama }}</span>
                                    @if($isAssignedArea)
                                        <span class="text-[9px] font-bold text-blue-900 bg-blue-100 px-1.5 py-0.5 rounded border border-blue-200">Wilayah Anda</span>
                                    @endif
                                </div>
                            </td>
                            @foreach($kuesioners as $k)
                                @php
                                    $prog = $row['progress'][$k->kode] ?? ['percent' => 0, 'filled' => 0, 'total' => 0];
                                    $pct = $prog['percent'];
                                    $bgClass = $pct >= 100 ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : ($pct > 0 ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-slate-50 text-slate-400 border-slate-200');
                                    $canInput = $bulan && $tahun;
                                    $isUserAllowed = !auth()->check() || auth()->user()->isAdmin() || (auth()->user()->isUserArea() && (int) auth()->user()->kecamatan_id === (int) $row['kecamatan']->id);
                                    $linkUrl = ($canInput && $isUserAllowed)
                                        ? route('input.show', [$k, $row['kecamatan'], 'bulan' => $bulan, 'tahun' => $tahun])
                                        : '#';
                                @endphp
                                <td class="px-1.5 py-1.5 text-center">
                                    @if($canInput && $isUserAllowed)
                                        <a href="{{ $linkUrl }}"
                                           class="block rounded p-1.5 border {{ $bgClass }} hover:border-slate-400 transition-colors">
                                            <div class="font-bold tabular-nums">{{ number_format($pct, 0) }}%</div>
                                            <div class="text-[9px] opacity-70 font-mono mt-0.5">{{ $prog['filled'] }}/{{ $prog['total'] }}</div>
                                        </a>
                                    @elseif($canInput && !$isUserAllowed)
                                        <div class="block rounded p-1.5 border bg-slate-50 text-slate-300 border-slate-200 opacity-60 cursor-not-allowed"
                                             title="Hanya dapat diinput oleh Operator wilayah {{ $row['kecamatan']->nama }}">
                                            <div class="font-bold tabular-nums">{{ number_format($pct, 0) }}%</div>
                                            <div class="text-[9px] font-mono mt-0.5">{{ $prog['filled'] }}/{{ $prog['total'] }}</div>
                                        </div>
                                    @else
                                        <div class="block rounded p-1.5 border bg-slate-50 text-slate-300 border-slate-200 cursor-not-allowed"
                                             title="Pilih periode terlebih dahulu">
                                            <div class="font-bold tabular-nums">—</div>
                                            <div class="text-[9px] font-mono mt-0.5">0/{{ $prog['total'] }}</div>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
