<?php

namespace App\Services;

use App\Models\Dimensi;
use App\Models\Indikator;
use App\Models\Jawaban;
use App\Models\Kecamatan;
use App\Models\Parameter;
use App\Models\SubVariabel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class QpiCalculationService
{
    /**
     * Calculate indicator score for a kecamatan.
     *
     * Replicates Excel formula: IF(COUNTA(5 cells)=0, null, COUNTIF(cells,"Ya")/5*100)
     * When ≥1 sub-item is filled, empty sub-items are implicitly treated as "Tidak".
     */
    public function skorIndikator(int $indikatorId, int $kecamatanId): ?float
    {
        $jawabans = Jawaban::whereHas('subItem', fn ($q) => $q->where('indikator_id', $indikatorId))
            ->where('kecamatan_id', $kecamatanId)
            ->get();

        if ($jawabans->isEmpty()) {
            return null;
        }

        // Count how many have nilai = true (Ya)
        $yaCount = $jawabans->where('nilai', true)->count();

        // Divisor is always 5 (total sub-items), replicating Excel behavior
        return ($yaCount / 5) * 100;
    }

    /**
     * Batch calculate all indicator scores for a kecamatan.
     * Much more efficient than calling skorIndikator() one by one.
     *
     * @return array<int, float|null> keyed by indikator_id
     */
    public function allSkorIndikator(int $kecamatanId): array
    {
        // Get all jawabans for this kecamatan, grouped by indikator
        $jawabans = Jawaban::where('kecamatan_id', $kecamatanId)
            ->join('sub_items', 'jawabans.sub_item_id', '=', 'sub_items.id')
            ->select('sub_items.indikator_id', 'jawabans.nilai')
            ->get()
            ->groupBy('indikator_id');

        $allIndikatorIds = Indikator::pluck('id')->toArray();
        $result = [];

        foreach ($allIndikatorIds as $indId) {
            if (! isset($jawabans[$indId]) || $jawabans[$indId]->isEmpty()) {
                $result[$indId] = null;
            } else {
                $yaCount = $jawabans[$indId]->where('nilai', true)->count();
                $result[$indId] = ($yaCount / 5) * 100;
            }
        }

        return $result;
    }

    /**
     * Calculate sub-variable score for a kecamatan (display only, not used in dimension calc).
     *
     * Weighted average of indicator scores within the sub-variable.
     * Only indicators with numeric scores are included (dynamic denominator).
     */
    public function skorSubVariabel(int $subVariabelId, int $kecamatanId, ?array $skorIndikatorCache = null): ?float
    {
        $indikators = Indikator::where('sub_variabel_id', $subVariabelId)->get();

        $sumWeighted = 0;
        $sumBobot = 0;

        foreach ($indikators as $ind) {
            $skor = $skorIndikatorCache !== null
                ? ($skorIndikatorCache[$ind->id] ?? null)
                : $this->skorIndikator($ind->id, $kecamatanId);

            if ($skor !== null) {
                $sumWeighted += $skor * (float) $ind->bobot_asli;
                $sumBobot += (float) $ind->bobot_asli;
            }
        }

        if ($sumBobot <= 0) {
            return null;
        }

        return $sumWeighted / $sumBobot;
    }

    /**
     * Calculate dimension score for a kecamatan.
     *
     * Uses dimensi_indikator_bobot pivot (2-tier aggregation: indicator → dimension).
     * Dynamic weighted average: only indicators with numeric scores are included.
     */
    public function skorDimensi(string $dimensiKode, int $kecamatanId, ?array $skorIndikatorCache = null): ?float
    {
        $dimensi = Dimensi::where('kode', $dimensiKode)->first();

        if (! $dimensi) {
            return null;
        }

        $pivotRows = DB::table('dimensi_indikator_bobot')
            ->where('dimensi_id', $dimensi->id)
            ->get();

        $sumWeighted = 0;
        $sumBobot = 0;

        foreach ($pivotRows as $row) {
            $skor = $skorIndikatorCache !== null
                ? ($skorIndikatorCache[$row->indikator_id] ?? null)
                : $this->skorIndikator($row->indikator_id, $kecamatanId);

            if ($skor !== null) {
                $sumWeighted += $skor * (float) $row->bobot;
                $sumBobot += (float) $row->bobot;
            }
        }

        if ($sumBobot <= 0) {
            return null;
        }

        return $sumWeighted / $sumBobot;
    }

    /**
     * Calculate all 7 dimension scores for a kecamatan.
     *
     * @return array<string, float|null> keyed by dimension kode (D1..D7)
     */
    public function allSkorDimensi(int $kecamatanId): array
    {
        $skorIndikatorCache = $this->allSkorIndikator($kecamatanId);
        $result = [];

        foreach (['D1', 'D2', 'D3', 'D4', 'D5', 'D6', 'D7'] as $kode) {
            $result[$kode] = $this->skorDimensi($kode, $kecamatanId, $skorIndikatorCache);
        }

        return $result;
    }

    /**
     * Calculate QPI Inti for a kecamatan.
     *
     * Weighted average of D1–D5 only (D6, D7 excluded).
     * Uses editable dimension weights from Parameter table.
     */
    public function qpiInti(int $kecamatanId, ?array $skorDimensiCache = null): ?float
    {
        $bobot = Parameter::getGroup('bobot_dimensi');
        $dimensiScores = $skorDimensiCache ?? $this->allSkorDimensi($kecamatanId);

        $mapping = [
            'D1' => 'bobot_d1',
            'D2' => 'bobot_d2',
            'D3' => 'bobot_d3',
            'D4' => 'bobot_d4',
            'D5' => 'bobot_d5',
        ];

        $sumWeighted = 0;
        $sumBobot = 0;

        foreach ($mapping as $dimKode => $bobotKey) {
            $skor = $dimensiScores[$dimKode] ?? null;

            if ($skor !== null) {
                $w = $bobot[$bobotKey] ?? 0;
                $sumWeighted += $skor * $w;
                $sumBobot += $w;
            }
        }

        if ($sumBobot <= 0) {
            return null;
        }

        return $sumWeighted / $sumBobot;
    }

    /**
     * Determine floor status for a kecamatan.
     *
     * Floor can only be determined when ALL 5 core dimensions (D1–D5) have scores.
     *
     * @return string|null null=not determinable, 'FLOOR_AKTIF', 'TIDAK_AKTIF'
     */
    public function floorStatus(int $kecamatanId, ?array $skorDimensiCache = null): ?string
    {
        $dimensiScores = $skorDimensiCache ?? $this->allSkorDimensi($kecamatanId);
        $ambangFloor = Parameter::getValue('ambang_floor', 50);

        // Check all 5 core dimensions are filled
        $coreScores = [];
        foreach (['D1', 'D2', 'D3', 'D4', 'D5'] as $kode) {
            $score = $dimensiScores[$kode] ?? null;

            if ($score === null) {
                return null; // Cannot determine floor until all 5 are filled
            }

            $coreScores[] = $score;
        }

        return min($coreScores) < $ambangFloor ? 'FLOOR_AKTIF' : 'TIDAK_AKTIF';
    }

    /**
     * Determine category for a kecamatan.
     *
     * @return array{kategori: string, floor_aktif: bool}|null null if not determinable
     */
    public function kategori(int $kecamatanId, ?array $skorDimensiCache = null): ?array
    {
        $skorDimensi = $skorDimensiCache ?? $this->allSkorDimensi($kecamatanId);
        $qpi = $this->qpiInti($kecamatanId, $skorDimensi);

        if ($qpi === null) {
            return null;
        }

        $floor = $this->floorStatus($kecamatanId, $skorDimensi);
        $ambang = Parameter::getGroup('ambang_kategori');

        $floorAktif = $floor === 'FLOOR_AKTIF';

        if ($floorAktif) {
            // Floor active: cap at "Perlu Perbaikan" max
            $perluPerbaikan = $ambang['ambang_perlu_perbaikan'] ?? 50;
            $kategori = $qpi >= $perluPerbaikan ? 'Perlu Perbaikan (Floor)' : 'Kritis (Floor)';
        } else {
            // Normal categorization
            $kategori = $this->kategoriFull($qpi, $ambang);
        }

        return [
            'kategori' => $kategori,
            'floor_aktif' => $floorAktif,
        ];
    }

    /**
     * Get full categorization without floor.
     *
     * @param  array<string, float>  $ambang
     */
    private function kategoriFull(float $qpi, array $ambang): string
    {
        $sangatBaik = $ambang['ambang_sangat_baik'] ?? 85;
        $baik = $ambang['ambang_baik'] ?? 75;
        $cukup = $ambang['ambang_cukup'] ?? 65;
        $perluPerbaikan = $ambang['ambang_perlu_perbaikan'] ?? 50;

        if ($qpi >= $sangatBaik) {
            return 'Sangat Baik';
        }
        if ($qpi >= $baik) {
            return 'Baik';
        }
        if ($qpi >= $cukup) {
            return 'Cukup';
        }
        if ($qpi >= $perluPerbaikan) {
            return 'Perlu Perbaikan';
        }

        return 'Kritis';
    }

    /**
     * Calculate rankings for all 21 kecamatan.
     * Descending by QPI Inti, with stable tie-break by kecamatan urutan.
     *
     * @return Collection<int, array{kecamatan: Kecamatan, qpi_inti: float|null, rank: int, kategori: string|null, floor_aktif: bool|null, dimensi: array<string, float|null>}>
     */
    public function peringkat(): Collection
    {
        $kecamatans = Kecamatan::orderBy('urutan')->get();
        $results = [];

        foreach ($kecamatans as $kec) {
            $skorDimensi = $this->allSkorDimensi($kec->id);
            $qpi = $this->qpiInti($kec->id, $skorDimensi);
            $kat = $this->kategori($kec->id, $skorDimensi);

            $results[] = [
                'kecamatan' => $kec,
                'qpi_inti' => $qpi,
                'kategori' => $kat ? $kat['kategori'] : null,
                'floor_aktif' => $kat ? $kat['floor_aktif'] : null,
                'dimensi' => $skorDimensi,
            ];
        }

        // Sort descending by QPI, null goes last; tie-break by kecamatan urutan (stable)
        usort($results, function ($a, $b) {
            if ($a['qpi_inti'] === null && $b['qpi_inti'] === null) {
                return $a['kecamatan']->urutan <=> $b['kecamatan']->urutan;
            }
            if ($a['qpi_inti'] === null) {
                return 1;
            }
            if ($b['qpi_inti'] === null) {
                return -1;
            }
            $cmp = $b['qpi_inti'] <=> $a['qpi_inti']; // descending

            return $cmp !== 0 ? $cmp : $a['kecamatan']->urutan <=> $b['kecamatan']->urutan;
        });

        // Assign ranks
        $rank = 1;
        foreach ($results as $i => &$row) {
            $row['rank'] = $row['qpi_inti'] !== null ? $rank++ : null;
        }

        return collect($results);
    }

    /**
     * Get top N weakest indicators (by city average).
     * Stable sort: ties broken by indicator original order.
     *
     * @return Collection<int, array{indikator: Indikator, rata_rata_kota: float|null, skor_per_kecamatan: array<int, float|null>}>
     */
    public function topNIndikatorTermurah(int $n = 10): Collection
    {
        $kecamatans = Kecamatan::orderBy('urutan')->get();
        $indikators = Indikator::with('subVariabel.kuesioner')->orderBy('id')->get();

        // Batch load all jawabans
        $allScores = [];
        foreach ($kecamatans as $kec) {
            $allScores[$kec->id] = $this->allSkorIndikator($kec->id);
        }

        $results = [];
        foreach ($indikators as $ind) {
            $scores = [];
            foreach ($kecamatans as $kec) {
                $scores[$kec->id] = $allScores[$kec->id][$ind->id] ?? null;
            }

            $validScores = array_filter($scores, fn ($s) => $s !== null);
            $rataRata = ! empty($validScores) ? array_sum($validScores) / count($validScores) : null;

            $results[] = [
                'indikator' => $ind,
                'rata_rata_kota' => $rataRata,
                'skor_per_kecamatan' => $scores,
            ];
        }

        // Sort ascending by rata_rata, null last, tie-break by original order (index)
        usort($results, function ($a, $b) {
            if ($a['rata_rata_kota'] === null && $b['rata_rata_kota'] === null) {
                return 0;
            }
            if ($a['rata_rata_kota'] === null) {
                return 1;
            }
            if ($b['rata_rata_kota'] === null) {
                return -1;
            }

            return $a['rata_rata_kota'] <=> $b['rata_rata_kota'];
        });

        return collect(array_slice($results, 0, $n));
    }

    /**
     * Get top N weakest sub-variables (by city average).
     *
     * @return Collection<int, array{sub_variabel: SubVariabel, rata_rata_kota: float|null, skor_per_kecamatan: array<int, float|null>}>
     */
    public function topNSubVariabelTermurah(int $n = 5): Collection
    {
        $kecamatans = Kecamatan::orderBy('urutan')->get();
        $subVariabels = SubVariabel::with('kuesioner')->orderBy('id')->get();

        // Pre-compute all indicator scores per kecamatan
        $allScores = [];
        foreach ($kecamatans as $kec) {
            $allScores[$kec->id] = $this->allSkorIndikator($kec->id);
        }

        $results = [];
        foreach ($subVariabels as $sv) {
            $scores = [];
            foreach ($kecamatans as $kec) {
                $scores[$kec->id] = $this->skorSubVariabel($sv->id, $kec->id, $allScores[$kec->id]);
            }

            $validScores = array_filter($scores, fn ($s) => $s !== null);
            $rataRata = ! empty($validScores) ? array_sum($validScores) / count($validScores) : null;

            $results[] = [
                'sub_variabel' => $sv,
                'rata_rata_kota' => $rataRata,
                'skor_per_kecamatan' => $scores,
            ];
        }

        usort($results, function ($a, $b) {
            if ($a['rata_rata_kota'] === null && $b['rata_rata_kota'] === null) {
                return 0;
            }
            if ($a['rata_rata_kota'] === null) {
                return 1;
            }
            if ($b['rata_rata_kota'] === null) {
                return -1;
            }

            return $a['rata_rata_kota'] <=> $b['rata_rata_kota'];
        });

        return collect(array_slice($results, 0, $n));
    }

    /**
     * Get city-wide KPI statistics for the dashboard.
     *
     * @return array{rata_rata_qpi: float|null, jumlah_kritis: int, jumlah_floor: int, jumlah_sangat_baik: int, kecamatan_terendah: string|null, qpi_terendah: float|null}
     */
    public function statistikKota(): array
    {
        $peringkat = $this->peringkat();

        $qpiValues = $peringkat->pluck('qpi_inti')->filter(fn ($v) => $v !== null);
        $rataRata = $qpiValues->isNotEmpty() ? round($qpiValues->avg(), 1) : null;

        // Count Kritis (including "Kritis (Floor)")
        $jumlahKritis = $peringkat->filter(fn ($r) => $r['kategori'] !== null && str_contains($r['kategori'], 'Kritis'))->count();

        // Count Floor Aktif
        $jumlahFloor = $peringkat->filter(fn ($r) => $r['floor_aktif'] === true)->count();

        // Count Sangat Baik (exact match, no floor variant exists for this)
        $jumlahSangatBaik = $peringkat->filter(fn ($r) => $r['kategori'] === 'Sangat Baik')->count();

        // Kecamatan with lowest score
        $lowest = $peringkat->filter(fn ($r) => $r['qpi_inti'] !== null)->sortBy('qpi_inti')->first();

        return [
            'rata_rata_qpi' => $rataRata,
            'jumlah_kritis' => $jumlahKritis,
            'jumlah_floor' => $jumlahFloor,
            'jumlah_sangat_baik' => $jumlahSangatBaik,
            'kecamatan_terendah' => $lowest ? $lowest['kecamatan']->nama : null,
            'qpi_terendah' => $lowest ? $lowest['qpi_inti'] : null,
        ];
    }

    /**
     * Get input progress overview: percentage filled per kuesioner × kecamatan.
     *
     * @return Collection<int, array{kecamatan: Kecamatan, progress: array<string, array{filled: int, total: int, percent: float}>}>
     */
    public function inputProgress(): Collection
    {
        $kecamatans = Kecamatan::orderBy('urutan')->get();

        // Get sub-item counts per kuesioner
        $subItemCounts = DB::table('sub_items')
            ->join('indikators', 'sub_items.indikator_id', '=', 'indikators.id')
            ->join('sub_variabels', 'indikators.sub_variabel_id', '=', 'sub_variabels.id')
            ->join('kuesioners', 'sub_variabels.kuesioner_id', '=', 'kuesioners.id')
            ->select('kuesioners.kode', DB::raw('count(*) as total'))
            ->groupBy('kuesioners.kode')
            ->pluck('total', 'kode')
            ->toArray();

        // Get filled jawaban counts per kuesioner per kecamatan
        $filledCounts = DB::table('jawabans')
            ->join('sub_items', 'jawabans.sub_item_id', '=', 'sub_items.id')
            ->join('indikators', 'sub_items.indikator_id', '=', 'indikators.id')
            ->join('sub_variabels', 'indikators.sub_variabel_id', '=', 'sub_variabels.id')
            ->join('kuesioners', 'sub_variabels.kuesioner_id', '=', 'kuesioners.id')
            ->whereNotNull('jawabans.nilai')
            ->select('kuesioners.kode', 'jawabans.kecamatan_id', DB::raw('count(*) as filled'))
            ->groupBy('kuesioners.kode', 'jawabans.kecamatan_id')
            ->get()
            ->groupBy('kecamatan_id');

        $results = [];
        foreach ($kecamatans as $kec) {
            $progress = [];
            $kecFilled = $filledCounts[$kec->id] ?? collect();

            foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $kode) {
                $total = $subItemCounts[$kode] ?? 0;
                $filled = $kecFilled->firstWhere('kode', $kode)?->filled ?? 0;
                $progress[$kode] = [
                    'filled' => (int) $filled,
                    'total' => $total,
                    'percent' => $total > 0 ? round(($filled / $total) * 100, 1) : 0,
                ];
            }

            $results[] = [
                'kecamatan' => $kec,
                'progress' => $progress,
            ];
        }

        return collect($results);
    }
}
