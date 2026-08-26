@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('heading', 'Dashboard Utama')

@section('content')
<div class="space-y-6">

    {{-- KPI Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        {{-- Rata-rata QPI --}}
        <div class="admin-card p-4">
            <div class="text-xs font-semibold text-slate-500">Rata-rata QPI Kota</div>
            <div class="text-3xl font-bold text-slate-900 mt-2 tabular-nums">
                {{ $statistik['rata_rata_qpi'] !== null ? number_format($statistik['rata_rata_qpi'], 1) : '—' }}
            </div>
            <div class="text-[11px] text-slate-400 mt-1">21 Kecamatan</div>
        </div>

        {{-- Sangat Baik --}}
        <div class="admin-card p-4">
            <div class="text-xs font-semibold text-slate-500">Sangat Baik</div>
            <div class="text-3xl font-bold text-emerald-700 mt-2 tabular-nums">
                {{ $statistik['jumlah_sangat_baik'] }}
            </div>
            <div class="text-[11px] text-slate-400 mt-1">QPI ≥ 85.0</div>
        </div>

        {{-- Floor Aktif --}}
        <div class="admin-card p-4">
            <div class="text-xs font-semibold text-slate-500">Floor Aktif</div>
            <div class="text-3xl font-bold {{ $statistik['jumlah_floor'] > 0 ? 'text-amber-700' : 'text-slate-900' }} mt-2 tabular-nums">
                {{ $statistik['jumlah_floor'] }}
            </div>
            <div class="text-[11px] text-slate-400 mt-1">Dimensi &lt; 50.0</div>
        </div>

        {{-- Kritis --}}
        <div class="admin-card p-4">
            <div class="text-xs font-semibold text-slate-500">Kritis</div>
            <div class="text-3xl font-bold {{ $statistik['jumlah_kritis'] > 0 ? 'text-red-700' : 'text-slate-900' }} mt-2 tabular-nums">
                {{ $statistik['jumlah_kritis'] }}
            </div>
            <div class="text-[11px] text-slate-400 mt-1">Termasuk Floor</div>
        </div>
    </div>

    {{-- Alert Warning jika ada kecamatan terendah --}}
    @if($statistik['kecamatan_terendah'])
    <div class="p-3 bg-red-50 border border-red-200 rounded text-xs text-red-900 font-medium">
        Kecamatan dengan skor terendah: <strong class="font-bold underline">{{ $statistik['kecamatan_terendah'] }}</strong> (Skor: {{ number_format($statistik['qpi_terendah'], 1) }}).
    </div>
    @endif

    {{-- Tabel Peringkat --}}
    <div class="admin-card overflow-hidden">
        <div class="admin-card-header flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Peringkat 21 Kecamatan</h2>
            <span class="text-[11px] text-slate-500">QPI Inti</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider">
                        <th class="px-4 py-2.5 text-center w-12">No</th>
                        <th class="px-4 py-2.5 text-left">Kecamatan</th>
                        <th class="px-4 py-2.5 text-center">QPI Inti</th>
                        <th class="px-4 py-2.5 text-center">Kategori</th>
                        @foreach(['D1','D2','D3','D4','D5','D6','D7'] as $d)
                            <th class="px-2.5 py-2.5 text-center">{{ $d }}</th>
                        @endforeach
                        <th class="px-4 py-2.5 text-center">Floor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach($peringkat as $row)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-4 py-2.5 text-center font-bold text-slate-400 tabular-nums">
                            {{ $row['rank'] ?? '—' }}
                        </td>
                        <td class="px-4 py-2.5 font-bold text-slate-900">
                            <a href="{{ route('kecamatan.detail', $row['kecamatan']) }}" class="text-blue-900 hover:underline">
                                {{ $row['kecamatan']->nama }}
                            </a>
                        </td>
                        <td class="px-4 py-2.5 text-center tabular-nums">
                            @if($row['qpi_inti'] !== null)
                                <span class="font-bold text-sm @include('components._score-color-class', ['score' => $row['qpi_inti']])">
                                    {{ number_format($row['qpi_inti'], 1) }}
                                </span>
                            @else
                                <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            @if($row['kategori'])
                                @include('components._kategori-badge', ['kategori' => $row['kategori']])
                            @else
                                <span class="text-slate-300 italic">—</span>
                            @endif
                        </td>
                        @foreach(['D1','D2','D3','D4','D5','D6','D7'] as $d)
                            <td class="px-2.5 py-2.5 text-center tabular-nums font-semibold">
                                @if($row['dimensi'][$d] !== null)
                                    <span class="@include('components._score-color-class', ['score' => $row['dimensi'][$d]])">
                                        {{ number_format($row['dimensi'][$d], 1) }}
                                    </span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                        @endforeach
                        <td class="px-4 py-2.5 text-center">
                            @if($row['floor_aktif'] === true)
                                <span class="badge badge-perlu-perbaikan">AKTIF</span>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bottom Priority Analysis Section --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        {{-- Top 10 Indikator Terlemah --}}
        <div class="admin-card overflow-hidden">
            <div class="admin-card-header">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">10 Indikator Terlemah (Rata-rata Kota)</h3>
            </div>
            <div class="p-4 space-y-3 bg-white">
                @forelse($topIndikator as $item)
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2 min-w-0 pr-2">
                                <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-700 font-mono font-bold text-[10px] border border-slate-200">
                                    {{ $item['indikator']->kode }}
                                </span>
                                <span class="text-slate-800 font-medium truncate">{{ $item['indikator']->pernyataan }}</span>
                            </div>
                            <span class="font-bold flex-shrink-0 text-xs tabular-nums @include('components._score-color-class', ['score' => $item['rata_rata_kota'] ?? 0])">
                                {{ $item['rata_rata_kota'] !== null ? number_format($item['rata_rata_kota'], 1) : '—' }}
                            </span>
                        </div>
                        <div class="h-1.5 bg-slate-100 rounded overflow-hidden">
                            <div class="h-full transition-all duration-300 @include('components._score-bg-class', ['score' => $item['rata_rata_kota'] ?? 0])"
                                 style="width: {{ $item['rata_rata_kota'] ?? 0 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">Belum Ada Data</p>
                @endforelse
            </div>
        </div>

        {{-- Top 5 Sub-Variabel Terlemah --}}
        <div class="admin-card overflow-hidden">
            <div class="admin-card-header">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">5 Sub-Variabel Terlemah (Rata-rata Kota)</h3>
            </div>
            <div class="p-4 space-y-3 bg-white">
                @forelse($topSubVariabel as $item)
                    <div class="p-3 rounded bg-slate-50 border border-slate-200">
                        <div class="flex items-start justify-between">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-slate-900 font-bold truncate">{{ $item['sub_variabel']->nama }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $item['sub_variabel']->kuesioner->nama }}</p>
                            </div>
                            <span class="text-base font-bold flex-shrink-0 ml-3 tabular-nums @include('components._score-color-class', ['score' => $item['rata_rata_kota'] ?? 0])">
                                {{ $item['rata_rata_kota'] !== null ? number_format($item['rata_rata_kota'], 1) : '—' }}
                            </span>
                        </div>
                        <div class="h-1.5 bg-slate-200 rounded overflow-hidden mt-2">
                            <div class="h-full transition-all duration-300 @include('components._score-bg-class', ['score' => $item['rata_rata_kota'] ?? 0])"
                                 style="width: {{ $item['rata_rata_kota'] ?? 0 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">Belum Ada Data</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
