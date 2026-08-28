<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\Kecamatan;
use App\Models\Kuesioner;
use App\Models\SubItem;
use App\Services\QpiCalculationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InputController extends Controller
{
    public function __construct(
        private QpiCalculationService $qpiService,
    ) {}

    /**
     * Show the checklist input form for a kuesioner × kecamatan.
     * Requires periode_bulan and periode_tahun query parameters.
     */
    public function show(Kuesioner $kuesioner, Kecamatan $kecamatan, Request $request): View|RedirectResponse
    {
        $user = $request->user();
        if ($user && $user->isUserArea() && (int) $user->kecamatan_id !== (int) $kecamatan->id) {
            return redirect()
                ->route('input.progress', ['bulan' => $request->integer('bulan') ?: null, 'tahun' => $request->integer('tahun') ?: null])
                ->with('error', 'Akses ditolak. Anda hanya berhak menginput data untuk wilayah '.($user->kecamatan->nama ?? 'kecamatan Anda').'.');
        }

        $bulan = $request->integer('bulan') ?: null;
        $tahun = $request->integer('tahun') ?: null;

        // Periode wajib dipilih sebelum input
        if ($bulan === null || $tahun === null) {
            return redirect()
                ->route('input.progress')
                ->with('error', 'Pilih periode (bulan & tahun) terlebih dahulu sebelum menginput data.');
        }

        $subVariabels = $kuesioner->subVariabels()
            ->with(['indikators' => fn ($q) => $q->orderBy('urutan'), 'indikators.subItems' => fn ($q) => $q->orderBy('urutan')])
            ->orderBy('urutan')
            ->get();

        // Get existing answers for this periode
        $subItemIds = $subVariabels->flatMap(fn ($sv) => $sv->indikators->flatMap(fn ($i) => $i->subItems->pluck('id')));
        $existingAnswers = Jawaban::where('kecamatan_id', $kecamatan->id)
            ->where('periode_bulan', $bulan)
            ->where('periode_tahun', $tahun)
            ->whereIn('sub_item_id', $subItemIds)
            ->pluck('nilai', 'sub_item_id')
            ->toArray();

        return view('input.show', compact('kuesioner', 'kecamatan', 'subVariabels', 'existingAnswers', 'bulan', 'tahun'));
    }

    /**
     * Store/update batch answers for a kuesioner × kecamatan × periode.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kecamatan_id' => ['required', 'exists:kecamatans,id'],
            'kuesioner_id' => ['required', 'exists:kuesioners,id'],
            'periode_bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'periode_tahun' => ['required', 'integer', 'min:2020', 'max:2100'],
            'jawaban' => ['required', 'array'],
            'jawaban.*' => ['nullable', 'in:1,0'],
        ]);

        $user = $request->user();
        if ($user && $user->isUserArea() && (int) $user->kecamatan_id !== (int) $validated['kecamatan_id']) {
            return redirect()
                ->route('input.progress', ['bulan' => $validated['periode_bulan'], 'tahun' => $validated['periode_tahun']])
                ->with('error', 'Akses ditolak. Anda tidak berhak menginput data untuk kecamatan ini.');
        }

        $kecamatanId = $validated['kecamatan_id'];
        $kuesionerId = $validated['kuesioner_id'];
        $bulan = (int) $validated['periode_bulan'];
        $tahun = (int) $validated['periode_tahun'];

        foreach ($validated['jawaban'] as $subItemId => $nilai) {
            // Verify sub_item belongs to the kuesioner
            $subItem = SubItem::find($subItemId);
            if (! $subItem) {
                continue;
            }

            Jawaban::updateOrCreate(
                [
                    'sub_item_id' => $subItemId,
                    'kecamatan_id' => $kecamatanId,
                    'periode_bulan' => $bulan,
                    'periode_tahun' => $tahun,
                ],
                [
                    'nilai' => $nilai !== null && $nilai !== '' ? (bool) $nilai : null,
                ]
            );
        }

        $kuesioner = Kuesioner::find($kuesionerId);

        return redirect()
            ->route('input.show', [$kuesioner, $kecamatanId, 'bulan' => $bulan, 'tahun' => $tahun])
            ->with('success', 'Data berhasil disimpan!');
    }

    /**
     * Input progress overview matrix with periode filter.
     */
    public function progress(Request $request): View
    {
        $bulan = $request->integer('bulan') ?: null;
        $tahun = $request->integer('tahun') ?: null;

        $progressData = $this->qpiService->inputProgress($bulan, $tahun);
        $kuesioners = Kuesioner::orderBy('id')->get();
        $periodeList = $this->qpiService->getPeriodeList();

        return view('input.progress', compact('progressData', 'kuesioners', 'periodeList', 'bulan', 'tahun'));
    }
}
