<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParameterController extends Controller
{
    /**
     * Show editable parameters.
     */
    public function index(): View
    {
        $bobotDimensi = Parameter::where('group', 'bobot_dimensi')->orderBy('id')->get();
        $ambangKategori = Parameter::where('group', 'ambang_kategori')->orderBy('id')->get();
        $floor = Parameter::where('group', 'floor')->first();

        return view('parameter.index', compact('bobotDimensi', 'ambangKategori', 'floor'));
    }

    /**
     * Update parameters.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'params' => ['required', 'array'],
            'params.*.id' => ['required', 'exists:parameters,id'],
            'params.*.value' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        foreach ($validated['params'] as $param) {
            Parameter::where('id', $param['id'])->update(['value' => $param['value']]);
        }

        return redirect()->route('parameter.index')->with('success', 'Parameter berhasil diperbarui!');
    }
}
