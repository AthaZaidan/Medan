<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\SubVariabel;
use App\Services\QpiCalculationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private QpiCalculationService $qpiService,
    ) {}

    /**
     * Main dashboard: KPI cards, ranking table, charts, top-N weakest.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        if ($user && $user->isUserArea() && $user->kecamatan_id) {
            return redirect()->route('kecamatan.detail', $user->kecamatan_id);
        }

        $bulan = $request->integer('bulan') ?: null;
        $tahun = $request->integer('tahun') ?: null;

        $statistik = $this->qpiService->statistikKota($bulan, $tahun);
        $peringkat = $this->qpiService->peringkat($bulan, $tahun);
        $topIndikator = $this->qpiService->topNIndikatorTermurah(10, $bulan, $tahun);
        $topSubVariabel = $this->qpiService->topNSubVariabelTermurah(5, $bulan, $tahun);
        $periodeList = $this->qpiService->getPeriodeList();
        $distribusiKategori = $this->qpiService->distribusiKategori($bulan, $tahun);
        $skorModul = $this->qpiService->skorModulKota($bulan, $tahun);

        // Data for bar chart: QPI per kecamatan
        $chartBarData = $peringkat->map(fn ($row) => [
            'nama' => $row['kecamatan']->nama,
            'qpi' => $row['qpi_inti'],
            'kategori' => $row['kategori'],
        ])->values();

        return view('dashboard.index', compact(
            'statistik',
            'peringkat',
            'topIndikator',
            'topSubVariabel',
            'periodeList',
            'distribusiKategori',
            'chartBarData',
            'skorModul',
            'bulan',
            'tahun',
        ));
    }

    /**
     * Detail page for a single kecamatan.
     */
    public function kecamatan(Request $request, Kecamatan $kecamatan): View|RedirectResponse
    {
        $user = $request->user();
        if ($user && $user->isUserArea() && (int) $user->kecamatan_id !== (int) $kecamatan->id) {
            return redirect()->route('kecamatan.detail', $user->kecamatan_id)
                ->with('error', 'Akses terbatas. Anda hanya berhak mengakses dashboard wilayah '.($user->kecamatan->nama ?? 'kecamatan Anda').'.');
        }

        $bulan = $request->integer('bulan') ?: null;
        $tahun = $request->integer('tahun') ?: null;

        $skorDimensi = $this->qpiService->allSkorDimensi($kecamatan->id, $bulan, $tahun);
        $qpiInti = $this->qpiService->qpiInti($kecamatan->id, $skorDimensi);
        $kategoriData = $this->qpiService->kategori($kecamatan->id, $skorDimensi);
        $floorStatus = $this->qpiService->floorStatus($kecamatan->id, $skorDimensi);
        $skorModulKecamatan = $this->qpiService->skorModulKecamatan($kecamatan->id, $bulan, $tahun);

        // Get sub-variabel scores for breakdown
        $skorIndikatorCache = $this->qpiService->allSkorIndikator($kecamatan->id, $bulan, $tahun);
        $subVariabels = SubVariabel::with(['kuesioner', 'indikators'])->orderBy('id')->get();
        $svScores = [];

        foreach ($subVariabels as $sv) {
            $svScores[$sv->id] = $this->qpiService->skorSubVariabel($sv->id, $kecamatan->id, $skorIndikatorCache, $bulan, $tahun);
        }

        $periodeList = $this->qpiService->getPeriodeList();
        $trenKecamatan = $this->qpiService->trenKecamatan($kecamatan->id);

        return view('dashboard.kecamatan', compact(
            'kecamatan',
            'skorDimensi',
            'qpiInti',
            'kategoriData',
            'floorStatus',
            'subVariabels',
            'svScores',
            'skorIndikatorCache',
            'skorModulKecamatan',
            'periodeList',
            'trenKecamatan',
            'bulan',
            'tahun',
        ));
    }
}
