@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('heading', 'Dashboard Utama')

@section('content')

@php
    $namaBulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    $labelPeriode = ($bulan && $tahun) ? ($namaBulan[$bulan] . ' ' . $tahun) : 'Semua Periode';
    $currentYear = date('Y');
@endphp

<div class="space-y-6">

    {{-- ============================================================ --}}
    {{-- Filter Periode                                               --}}
    {{-- ============================================================ --}}
    <div class="admin-card p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Filter Periode</h2>
                <p class="text-[11px] text-slate-500 mt-0.5">
                    Data ditampilkan:
                    <span class="font-bold text-blue-900">{{ $labelPeriode }}</span>
                </p>
            </div>
            <form id="form-filter-periode" method="GET" action="{{ route('dashboard') }}"
                  class="flex flex-wrap items-end gap-2">
                <div class="flex flex-col gap-0.5">
                    <label for="filter-bulan" class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Bulan</label>
                    <select id="filter-bulan" name="bulan"
                            class="px-3 py-1.5 text-xs border border-slate-300 rounded bg-white text-slate-900 font-semibold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">-- Semua --</option>
                        @foreach($namaBulan as $num => $nama)
                            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-0.5">
                    <label for="filter-tahun" class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Tahun</label>
                    <select id="filter-tahun" name="tahun"
                            class="px-3 py-1.5 text-xs border border-slate-300 rounded bg-white text-slate-900 font-semibold focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">-- Semua --</option>
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
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded text-xs transition-colors border border-slate-300">
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- KPI Cards Grid                                               --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        {{-- Rata-rata QPI --}}
        <div class="admin-card p-4">
            <div class="text-xs font-semibold text-slate-500">Rata-rata QPI Kota</div>
            <div class="text-3xl font-bold text-slate-900 mt-2 tabular-nums">
                {{ $statistik['rata_rata_qpi'] !== null ? number_format($statistik['rata_rata_qpi'], 1) : '—' }}
            </div>
            <div class="text-[11px] text-slate-400 mt-1">21 Kecamatan · {{ $labelPeriode }}</div>
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

    {{-- ============================================================ --}}
    {{-- Chart Section: Bar Chart + Pie Chart                         --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Bar Chart: QPI per Kecamatan --}}
        <div class="admin-card overflow-hidden xl:col-span-2">
            <div class="admin-card-header flex items-center justify-between">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Chart Batang — QPI Per Kecamatan</h2>
                <span class="text-[11px] text-slate-500">{{ $labelPeriode }}</span>
            </div>
            <div class="p-4 bg-white">
                @if($chartBarData->whereNotNull('qpi')->count() > 0)
                    <div style="height: 340px; position: relative;">
                        <canvas id="barChart"></canvas>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-16 text-slate-400">
                        <svg class="w-10 h-10 mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                        </svg>
                        <p class="text-xs font-semibold">Belum Ada Data</p>
                        <p class="text-[11px] mt-1">Pilih periode yang memiliki data, atau input data terlebih dahulu.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Pie Chart: Distribusi Kategori --}}
        <div class="admin-card overflow-hidden">
            <div class="admin-card-header">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Distribusi Kategori</h2>
            </div>
            <div class="p-4 bg-white">
                @php
                    $totalDenganData = array_sum(array_filter($distribusiKategori, fn($v, $k) => $k !== 'Belum Ada Data', ARRAY_FILTER_USE_BOTH));
                @endphp
                @if($totalDenganData > 0)
                    <div style="height: 220px; position: relative;" class="mb-4">
                        <canvas id="pieChart"></canvas>
                    </div>
                    {{-- Legenda --}}
                    <div class="space-y-1.5 mt-2">
                        @php
                            $legendColors = [
                                'Sangat Baik' => ['bg' => '#059669', 'text' => 'text-emerald-800'],
                                'Baik' => ['bg' => '#2563eb', 'text' => 'text-blue-800'],
                                'Cukup' => ['bg' => '#d97706', 'text' => 'text-amber-800'],
                                'Perlu Perbaikan' => ['bg' => '#ea580c', 'text' => 'text-orange-800'],
                                'Kritis' => ['bg' => '#dc2626', 'text' => 'text-red-800'],
                                'Belum Ada Data' => ['bg' => '#94a3b8', 'text' => 'text-slate-600'],
                            ];
                        @endphp
                        @foreach($distribusiKategori as $kat => $jumlah)
                            @if($jumlah > 0)
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-sm flex-shrink-0"
                                          style="background-color: {{ $legendColors[$kat]['bg'] ?? '#94a3b8' }}"></span>
                                    <span class="text-slate-700 font-medium">{{ $kat }}</span>
                                </div>
                                <span class="font-bold tabular-nums text-slate-900">{{ $jumlah }}</span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                        <svg class="w-10 h-10 mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/>
                        </svg>
                        <p class="text-xs font-semibold">Belum Ada Data</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ============================================================ --}}
    {{-- Tabel Periode                                                 --}}
    {{-- ============================================================ --}}
    <div class="admin-card overflow-hidden">
        <div class="admin-card-header flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Tabel Periode Data</h2>
            <span class="text-[11px] text-slate-500">Rekap periode yang sudah diinput</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider">
                        <th class="px-4 py-2.5 text-center w-10">No</th>
                        <th class="px-4 py-2.5 text-left">Periode</th>
                        <th class="px-4 py-2.5 text-center">Kecamatan Terinput</th>
                        <th class="px-4 py-2.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($periodeList as $i => $p)
                        <tr class="hover:bg-slate-50/80 transition-colors {{ ($bulan == $p['bulan'] && $tahun == $p['tahun']) ? 'bg-blue-50' : '' }}">
                            <td class="px-4 py-2.5 text-center font-bold text-slate-400 tabular-nums">{{ $i + 1 }}</td>
                            <td class="px-4 py-2.5 font-bold text-slate-900">
                                @if($bulan == $p['bulan'] && $tahun == $p['tahun'])
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 inline-block"></span>
                                        {{ $p['label'] }}
                                    </span>
                                @else
                                    {{ $p['label'] }}
                                @endif
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <span class="font-bold tabular-nums text-slate-900">{{ $p['jumlah_kecamatan'] }}</span>
                                <span class="text-slate-400"> / 21</span>
                            </td>
                            <td class="px-4 py-2.5 text-center">
                                <a href="{{ route('dashboard', ['bulan' => $p['bulan'], 'tahun' => $p['tahun']]) }}"
                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-[11px] font-bold border transition-colors
                                   {{ ($bulan == $p['bulan'] && $tahun == $p['tahun']) ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-blue-900 border-blue-300 hover:bg-blue-50' }}">
                                    @if($bulan == $p['bulan'] && $tahun == $p['tahun'])
                                        ● Aktif
                                    @else
                                        Lihat
                                    @endif
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-400 text-xs italic">
                                Belum ada periode yang diinput. Silakan input data terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- Tabel Peringkat                                              --}}
    {{-- ============================================================ --}}
    <div class="admin-card overflow-hidden">
        <div class="admin-card-header flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Peringkat 21 Kecamatan</h2>
            <span class="text-[11px] text-slate-500">QPI Inti · {{ $labelPeriode }}</span>
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

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
    // ─── Data dari Laravel ──────────────────────────────────────────
    const barRaw = @json($chartBarData);
    const distribusi = @json($distribusiKategori);

    // ─── Warna berdasarkan kategori ─────────────────────────────────
    const KATEGORI_COLOR = {
        'Sangat Baik':        { bg: 'rgba(5, 150, 105, 0.85)',   border: '#059669' },
        'Baik':               { bg: 'rgba(37, 99, 235, 0.85)',   border: '#2563eb' },
        'Cukup':              { bg: 'rgba(217, 119, 6, 0.85)',   border: '#d97706' },
        'Perlu Perbaikan':    { bg: 'rgba(234, 88, 12, 0.85)',   border: '#ea580c' },
        'Perlu Perbaikan (Floor)': { bg: 'rgba(234, 88, 12, 0.75)', border: '#ea580c' },
        'Kritis':             { bg: 'rgba(220, 38, 38, 0.85)',   border: '#dc2626' },
        'Kritis (Floor)':     { bg: 'rgba(220, 38, 38, 0.75)',   border: '#dc2626' },
        'default':            { bg: 'rgba(148, 163, 184, 0.6)', border: '#94a3b8' },
    };

    function getKategoriColor(kat) {
        return KATEGORI_COLOR[kat] || KATEGORI_COLOR['default'];
    }

    // ─── Bar Chart ──────────────────────────────────────────────────
    const barCanvas = document.getElementById('barChart');
    if (barCanvas && barRaw.length > 0) {
        const hasData = barRaw.some(d => d.qpi !== null);
        if (hasData) {
            const labels = barRaw.map(d => d.nama);
            const values = barRaw.map(d => d.qpi ?? 0);
            const bgColors = barRaw.map(d => getKategoriColor(d.kategori).bg);
            const bdColors = barRaw.map(d => getKategoriColor(d.kategori).border);

            new Chart(barCanvas, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'QPI Inti',
                        data: values,
                        backgroundColor: bgColors,
                        borderColor: bdColors,
                        borderWidth: 1.5,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => {
                                    const kat = barRaw[ctx.dataIndex].kategori ?? 'Belum Ada Data';
                                    const val = ctx.raw !== null ? ctx.raw.toFixed(1) : '—';
                                    return [`QPI: ${val}`, `Kategori: ${kat}`];
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                font: { size: 9 },
                                maxRotation: 45,
                                minRotation: 30,
                            },
                            grid: { display: false },
                        },
                        y: {
                            min: 0,
                            max: 100,
                            ticks: {
                                font: { size: 10 },
                                callback: v => v,
                            },
                            grid: { color: 'rgba(148,163,184,0.15)' },
                        }
                    }
                }
            });
        }
    }

    // ─── Pie Chart ──────────────────────────────────────────────────
    const pieCanvas = document.getElementById('pieChart');
    if (pieCanvas) {
        const pieLabels = Object.keys(distribusi).filter(k => distribusi[k] > 0);
        const pieValues = pieLabels.map(k => distribusi[k]);
        const pieBg = pieLabels.map(k => getKategoriColor(k).bg);
        const pieBd = pieLabels.map(k => getKategoriColor(k).border);

        if (pieValues.length > 0) {
            new Chart(pieCanvas, {
                type: 'doughnut',
                data: {
                    labels: pieLabels,
                    datasets: [{
                        data: pieValues,
                        backgroundColor: pieBg,
                        borderColor: pieBd,
                        borderWidth: 1.5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => {
                                    const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const pct = ((ctx.raw / total) * 100).toFixed(1);
                                    return ` ${ctx.label}: ${ctx.raw} kecamatan (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    }
})();
</script>

@endsection
