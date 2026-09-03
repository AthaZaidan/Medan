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

    {{-- Filter Periode --}}
    <div class="admin-card p-4 no-print">
        <form method="GET" action="{{ route('kecamatan.detail', $kecamatan->id) }}" class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L4.35 21l3.39-.62C9.28 20.76 10.6 21 12 21c4.97 0 9-4.03 9-9s-4.03-9-9-9z"/></svg>
                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Filter Periode Evaluasi</span>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <select name="bulan" class="text-xs rounded border-slate-300 bg-white py-1.5 px-3 font-medium text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Semua Bulan (Rata-rata) --</option>
                    @foreach([1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'] as $bNum => $bName)
                        <option value="{{ $bNum }}" {{ $bulan == $bNum ? 'selected' : '' }}>{{ $bName }}</option>
                    @endforeach
                </select>

                <select name="tahun" class="text-xs rounded border-slate-300 bg-white py-1.5 px-3 font-medium text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Semua Tahun --</option>
                    @foreach([2026, 2025, 2024] as $tVal)
                        <option value="{{ $tVal }}" {{ $tahun == $tVal ? 'selected' : '' }}>Tahun {{ $tVal }}</option>
                    @endforeach
                </select>

                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded bg-blue-900 text-white text-xs font-bold hover:bg-blue-800 transition-colors shadow-sm cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    Terapkan Filter
                </button>

                @if($bulan || $tahun)
                    <a href="{{ route('kecamatan.detail', $kecamatan->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded border border-slate-300 bg-slate-100 text-slate-700 text-xs font-semibold hover:bg-slate-200 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
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

    {{-- Grafik Tren Perkembangan QPI Per Periode --}}
    @if($trenKecamatan->count() > 0)
        <div class="admin-card overflow-hidden">
            <div class="admin-card-header flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Grafik Tren Perkembangan QPI-K (Per Periode)</h2>
                    <p class="text-[11px] text-slate-500 mt-0.5">Riwayat Perkembangan Nilai QPI Inti di Kecamatan {{ $kecamatan->nama }}</p>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded bg-blue-50 text-blue-900 border border-blue-200">
                    {{ $trenKecamatan->count() }} Periode Evaluasi
                </span>
            </div>
            <div class="p-5 bg-white space-y-4">
                <div class="relative h-64 w-full">
                    <canvas id="trenChart"></canvas>
                </div>
            </div>
        </div>
    @endif

    {{-- Grafik Penilaian Per Modul Kuesioner --}}
    <div class="admin-card overflow-hidden">
        <div class="admin-card-header flex items-center justify-between">
            <div>
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Grafik Penilaian Per Modul Kuesioner (Modul A – F)</h2>
                <p class="text-[11px] text-slate-500 mt-0.5">Perbandingan Nilai Evaluasi Per Modul di Kecamatan {{ $kecamatan->nama }}</p>
            </div>
            <span class="text-xs font-bold px-2.5 py-1 rounded bg-blue-50 text-blue-900 border border-blue-200">
                6 Modul Kuesioner
            </span>
        </div>
        <div class="p-5 bg-white space-y-4">
            <div class="relative h-72 w-full">
                <canvas id="kecamatanModulChart"></canvas>
            </div>

            {{-- Rekap Ringkas 6 Modul Kecamatan --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 pt-3 border-t border-slate-100">
                @foreach($skorModulKecamatan as $item)
                    @php
                        $score = $item['skor'];
                        $modulNames = [
                            'A' => 'Kepuasan Masyarakat',
                            'B' => 'Kinerja Aparatur',
                            'C' => 'Tata Kelola',
                            'D' => 'Kematangan Digital',
                            'E' => 'Tematik PKH',
                            'F' => 'Ketertiban Umum',
                        ];
                    @endphp
                    <div class="p-2.5 rounded border border-slate-200 bg-slate-50 text-center">
                        <div class="text-[10px] font-bold text-slate-500 uppercase">Modul {{ $item['kuesioner']->kode }}</div>
                        <div class="text-base font-extrabold mt-0.5 tabular-nums @if($score !== null) @include('components._score-color-class', ['score' => $score]) @else text-slate-300 @endif">
                            {{ $score !== null ? number_format($score, 1) : '—' }}
                        </div>
                        <div class="text-[10px] text-slate-500 font-medium truncate mt-0.5" title="{{ $item['kuesioner']->nama }}">
                            {{ $modulNames[$item['kuesioner']->kode] ?? $item['kuesioner']->nama }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
    const rawData = @json($skorModulKecamatan);
    const canvas = document.getElementById('kecamatanModulChart');
    if (canvas && rawData.length > 0) {
        const labels = rawData.map(m => `Modul ${m.kuesioner.kode}`);
        const values = rawData.map(m => m.skor !== null ? Number(m.skor.toFixed(1)) : 0);

        const valueLabelPlugin = {
            id: 'valueLabelPlugin',
            afterDatasetDraw(chart) {
                const { ctx } = chart;
                chart.data.datasets.forEach((dataset, i) => {
                    const meta = chart.getDatasetMeta(i);
                    meta.data.forEach((bar, index) => {
                        const value = dataset.data[index];
                        if (value !== null && value !== undefined && value > 0) {
                            ctx.save();
                            ctx.font = 'bold 10px "Plus Jakarta Sans", sans-serif';
                            ctx.fillStyle = '#334155';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';
                            ctx.fillText(Number(value).toFixed(1), bar.x, bar.y - 4);
                            ctx.restore();
                        }
                    });
                });
            }
        };

        new Chart(canvas, {
            type: 'bar',
            plugins: [valueLabelPlugin],
            data: {
                labels,
                datasets: [{
                    label: 'Skor Modul',
                    data: values,
                    backgroundColor: [
                        'rgba(30, 64, 175, 0.85)',
                        'rgba(2, 132, 199, 0.85)',
                        'rgba(5, 150, 105, 0.85)',
                        'rgba(217, 119, 6, 0.85)',
                        'rgba(124, 58, 237, 0.85)',
                        'rgba(225, 29, 72, 0.85)'
                    ],
                    borderColor: [
                        '#1e40af', '#0284c7', '#059669', '#d97706', '#7c3aed', '#e11d48'
                    ],
                    borderWidth: 1.5,
                    borderRadius: { topLeft: 6, topRight: 6 },
                    barPercentage: 0.55,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'x',
                layout: { padding: { top: 22 } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 10,
                        callbacks: {
                            title: ctx => {
                                const m = rawData[ctx[0].dataIndex];
                                return `Modul ${m.kuesioner.kode} — ${m.kuesioner.nama}`;
                            },
                            label: ctx => ` Skor Modul: ${ctx.raw} / 100`
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            font: { size: 11, weight: 'bold' },
                            color: '#1e293b'
                        },
                        grid: { display: false }
                    },
                    y: {
                        min: 0,
                        max: 105,
                        ticks: {
                            font: { size: 10 },
                            stepSize: 20,
                            callback: v => v > 100 ? '' : v
                        },
                        grid: { color: 'rgba(226, 232, 240, 0.7)' }
                    }
                }
            }
        });
    }

    // ─── Line Chart: Tren Perkembangan QPI Per Periode ───────────────────
    const trenRaw = @json($trenKecamatan);
    const trenCanvas = document.getElementById('trenChart');
    if (trenCanvas && trenRaw.length > 0) {
        const trenLabels = trenRaw.map(t => t.periode);
        const trenValues = trenRaw.map(t => t.qpi);

        new Chart(trenCanvas, {
            type: 'line',
            data: {
                labels: trenLabels,
                datasets: [{
                    label: 'Skor QPI Inti',
                    data: trenValues,
                    borderColor: '#1e40af',
                    backgroundColor: 'rgba(30, 64, 175, 0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#1e40af',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 10,
                        callbacks: {
                            label: ctx => {
                                const row = trenRaw[ctx.dataIndex];
                                return ` QPI Inti: ${ctx.raw} (${row.kategori})`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { font: { size: 11, weight: 'bold' }, color: '#1e293b' },
                        grid: { display: false }
                    },
                    y: {
                        min: 0,
                        max: 100,
                        ticks: { font: { size: 10 }, stepSize: 20 },
                        grid: { color: 'rgba(226, 232, 240, 0.7)' }
                    }
                }
            }
        });
    }
})();
</script>
@endsection
