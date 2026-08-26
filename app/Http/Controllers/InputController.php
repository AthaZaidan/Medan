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
     */
    public function show(Kuesioner $kuesioner, Kecamatan $kecamatan): View
    {
        $subVariabels = $kuesioner->subVariabels()
            ->with(['indikators' => fn ($q) => $q->orderBy('urutan'), 'indikators.subItems' => fn ($q) => $q->orderBy('urutan')])
            ->orderBy('urutan')
            ->get();

        // Get existing answers
        $subItemIds = $subVariabels->flatMap(fn ($sv) => $sv->indikators->flatMap(fn ($i) => $i->subItems->pluck('id')));
        $existingAnswers = Jawaban::where('kecamatan_id', $kecamatan->id)
            ->whereIn('sub_item_id', $subItemIds)
            ->pluck('nilai', 'sub_item_id')
            ->toArray();

        return view('input.show', compact('kuesioner', 'kecamatan', 'subVariabels', 'existingAnswers'));
    }

    /**
     * Store/update batch answers for a kuesioner × kecamatan.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kecamatan_id' => ['required', 'exists:kecamatans,id'],
            'kuesioner_id' => ['required', 'exists:kuesioners,id'],
            'jawaban' => ['required', 'array'],
            'jawaban.*' => ['nullable', 'in:1,0'],
        ]);

        $kecamatanId = $validated['kecamatan_id'];
        $kuesionerId = $validated['kuesioner_id'];

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
                ],
                [
                    'nilai' => $nilai !== null && $nilai !== '' ? (bool) $nilai : null,
                ]
            );
        }

        $kuesioner = Kuesioner::find($kuesionerId);

        return redirect()
            ->route('input.show', [$kuesioner, $kecamatanId])
            ->with('success', 'Data berhasil disimpan!');
    }

    /**
     * Input progress overview matrix.
     */
    public function progress(): View
    {
        $progressData = $this->qpiService->inputProgress();
        $kuesioners = Kuesioner::orderBy('id')->get();

        return view('input.progress', compact('progressData', 'kuesioners'));
    }
}
