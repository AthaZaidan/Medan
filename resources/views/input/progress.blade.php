@extends('layouts.app')

@section('title', 'Progress Input')
@section('heading', 'Progress Input Data')

@section('content')
<div class="space-y-6">

    <div class="admin-card p-4">
        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Matriks Progress Kelengkapan Data</h2>
        <p class="text-xs text-slate-500 mt-0.5">Klik sel untuk mengisi atau mengubah data kuesioner kecamatan.</p>
    </div>

    <div class="admin-card overflow-hidden">
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
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-2.5 font-bold text-slate-900 sticky left-0 bg-white z-10 text-xs border-r border-slate-200">
                                {{ $row['kecamatan']->nama }}
                            </td>
                            @foreach($kuesioners as $k)
                                @php
                                    $prog = $row['progress'][$k->kode] ?? ['percent' => 0, 'filled' => 0, 'total' => 0];
                                    $pct = $prog['percent'];
                                    $bgClass = $pct >= 100 ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : ($pct > 0 ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-slate-50 text-slate-400 border-slate-200');
                                @endphp
                                <td class="px-1.5 py-1.5 text-center">
                                    <a href="{{ route('input.show', [$k, $row['kecamatan']]) }}"
                                       class="block rounded p-1.5 border {{ $bgClass }} hover:border-slate-400 transition-colors">
                                        <div class="font-bold tabular-nums">{{ number_format($pct, 0) }}%</div>
                                        <div class="text-[9px] opacity-70 font-mono mt-0.5">{{ $prog['filled'] }}/{{ $prog['total'] }}</div>
                                    </a>
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
