<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\SubVariabel;
use App\Services\QpiCalculationService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private QpiCalculationService $qpiService,
    ) {}

    /**
     * Main dashboard: KPI cards, ranking table, top-N weakest.
     */
    public function index(): View
    {
        $statistik = $this->qpiService->statistikKota();
        $peringkat = $this->qpiService->peringkat();
        $topIndikator = $this->qpiService->topNIndikatorTermurah(10);
        $topSubVariabel = $this->qpiService->topNSubVariabelTermurah(5);

        return view('dashboard.index', compact(
            'statistik',
            'peringkat',
            'topIndikator',
            'topSubVariabel',
        ));
    }

    /**
     * Detail page for a single kecamatan.
     */
    public function kecamatan(Kecamatan $kecamatan): View
    {
        $skorDimensi = $this->qpiService->allSkorDimensi($kecamatan->id);
        $qpiInti = $this->qpiService->qpiInti($kecamatan->id, $skorDimensi);
        $kategoriData = $this->qpiService->kategori($kecamatan->id, $skorDimensi);
        $floorStatus = $this->qpiService->floorStatus($kecamatan->id, $skorDimensi);

        // Get sub-variabel scores for breakdown
        $skorIndikatorCache = $this->qpiService->allSkorIndikator($kecamatan->id);
        $subVariabels = SubVariabel::with(['kuesioner', 'indikators'])->orderBy('id')->get();
        $svScores = [];

        foreach ($subVariabels as $sv) {
            $svScores[$sv->id] = $this->qpiService->skorSubVariabel($sv->id, $kecamatan->id, $skorIndikatorCache);
        }

        return view('dashboard.kecamatan', compact(
            'kecamatan',
            'skorDimensi',
            'qpiInti',
            'kategoriData',
            'floorStatus',
            'subVariabels',
            'svScores',
            'skorIndikatorCache',
        ));
    }
}
