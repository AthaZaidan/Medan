@extends('layouts.app')

@section('title', 'Panduan')
@section('heading', 'Panduan & Metodologi')

@section('content')
<div class="space-y-6 max-w-3xl">

    <div class="admin-card p-4">
        <h1 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Panduan & Metodologi QPI-K 2026</h1>
        <p class="text-xs text-slate-500 mt-0.5">Penjelasan teknis perhitungan skor dan struktur instrumen penilaian.</p>
    </div>

    {{-- Section 1: Struktur --}}
    <div class="admin-card p-5 space-y-3">
        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">1. Struktur Instrumen Penilaian</h2>
        <div class="text-xs text-slate-700 space-y-2 leading-relaxed">
            <p>Penilaian kinerja 21 Kecamatan menggunakan 6 modul kuesioner berbasis Skala Guttman (Ya / Tidak):</p>
            <ul class="list-disc list-inside space-y-1 pl-2">
                <li><strong>Kuesioner A (Servqual):</strong> 25 Indikator $\rightarrow$ Dimensi <strong>D1</strong> (100%)</li>
                <li><strong>Kuesioner B (BSC):</strong> 15 Indikator $\rightarrow$ Dipetakan ke <strong>D3, D4, D5</strong></li>
                <li><strong>Kuesioner C (Tata Kelola):</strong> 12 Indikator $\rightarrow$ Dimensi <strong>D2</strong> (100%)</li>
                <li><strong>Kuesioner D (SPBE):</strong> 10 Indikator $\rightarrow$ Digabung bersama B11–B13 ke <strong>D4</strong></li>
                <li><strong>Kuesioner E (PKH Makmur):</strong> 20 Indikator $\rightarrow$ Dimensi <strong>D6</strong> (Modul Tematik)</li>
                <li><strong>Kuesioner F (Ketertiban Umum):</strong> 21 Indikator $\rightarrow$ Dimensi <strong>D7</strong> (Modul Tematik)</li>
            </ul>
            <p class="text-[11px] text-slate-500 pt-2 border-t border-slate-100 font-mono">
                Total: 103 Indikator Induk $\times$ 5 Sub-item = 515 Sub-item Checklist.
            </p>
        </div>
    </div>

    {{-- Section 2: Cara Pengisian --}}
    <div class="admin-card p-5 space-y-3">
        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">2. Cara Pengisian Data</h2>
        <div class="text-xs text-slate-700 space-y-2 leading-relaxed">
            <ol class="list-decimal list-inside space-y-1.5 pl-2">
                <li>Buka menu <strong>Input Data</strong>.</li>
                <li>Pilih kuesioner (A–F) dan kecamatan target.</li>
                <li>Pilih <strong>Ya</strong> atau <strong>Tidak</strong> pada 5 sub-item di bawah setiap indikator.</li>
                <li>Klik tombol <strong>Simpan Jawaban</strong>. Skor indikator, dimensi, dan QPI Inti akan dihitung secara otomatis.</li>
            </ol>
        </div>
    </div>

    {{-- Section 3: Rumus --}}
    <div class="admin-card p-5 space-y-3">
        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">3. Rumus Perhitungan</h2>
        <div class="text-xs text-slate-700 space-y-3 leading-relaxed">
            <div class="p-3 rounded bg-slate-50 border border-slate-200 space-y-1">
                <div class="font-bold text-slate-900">a. Skor Indikator (0 – 100):</div>
                <div class="font-mono text-blue-900 font-bold">Skor = (Jumlah "Ya" / 5) × 100</div>
                <div class="text-[11px] text-slate-500">Minimal 1 sub-item terisi. Jika 5 sub-item belum diisi sama sekali, skor bernilai NULL.</div>
            </div>

            <div class="p-3 rounded bg-slate-50 border border-slate-200 space-y-1">
                <div class="font-bold text-slate-900">b. Skor Dimensi D1 – D7:</div>
                <div class="font-mono text-blue-900 font-bold">Skor Dimensi = Σ(Skor Indikator × Bobot Asli) / Σ(Bobot Indikator Terisi)</div>
                <div class="text-[11px] text-slate-500">Dihitung langsung dari level indikator yang terisi (Dynamic Weighted Average).</div>
            </div>

            <div class="p-3 rounded bg-slate-50 border border-slate-200 space-y-1">
                <div class="font-bold text-slate-900">c. QPI Inti:</div>
                <div class="font-mono text-blue-900 font-bold">QPI Inti = D1×26.19% + D2×14.29% + D3×26.19% + D4×26.19% + D5×7.14%</div>
            </div>

            <div class="p-3 rounded bg-slate-50 border border-slate-200 space-y-1">
                <div class="font-bold text-slate-900">d. Evaluasi Floor Non-Kompensatori:</div>
                <div>Status Floor aktif jika kelima D1–D5 terisi dan minimal satu dimensi &lt; 50.0. Kategori maksimum dibatasi ke "Perlu Perbaikan (Floor)".</div>
            </div>
        </div>
    </div>

</div>
@endsection
