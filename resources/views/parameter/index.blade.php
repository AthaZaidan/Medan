@extends('layouts.app')

@section('title', 'Parameter')
@section('heading', 'Parameter & Bobot')

@section('content')
<div class="space-y-6 max-w-3xl">

    <div class="admin-card p-4">
        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Pengaturan Bobot & Ambang Batas</h2>
        <p class="text-xs text-slate-500 mt-0.5">Perubahan nilai parameter di bawah ini akan langsung mengubah hasil kalkulasi QPI Inti.</p>
    </div>

    <form action="{{ route('parameter.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Bobot Dimensi --}}
        <div class="admin-card overflow-hidden">
            <div class="admin-card-header flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Bobot Dimensi QPI Inti (D1–D5)</h3>
                <span class="text-[11px] font-bold text-slate-500">Total Wajib 100%</span>
            </div>
            <div class="p-4 space-y-3 bg-white" x-data="{
                totals() {
                    let sum = 0;
                    document.querySelectorAll('[data-group=bobot]').forEach(el => sum += parseFloat(el.value || 0));
                    return sum.toFixed(2);
                }
            }">
                @foreach($bobotDimensi as $param)
                    <div class="flex items-center gap-4 py-1 border-b border-slate-100 last:border-0 text-xs">
                        <label class="text-slate-800 font-medium flex-1">{{ $param->label }}</label>
                        <div class="flex items-center gap-1.5">
                            <input type="hidden" name="params[{{ $loop->index }}][id]" value="{{ $param->id }}">
                            <input type="number" name="params[{{ $loop->index }}][value]" value="{{ $param->value }}"
                                   step="0.01" min="0" max="100" data-group="bobot" @input="$el.closest('[x-data]').__x.$data.totals()"
                                   class="w-24 px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded text-slate-900 font-mono font-bold text-right focus:border-blue-900 focus:outline-none">
                            <span class="text-slate-500 font-semibold">%</span>
                        </div>
                    </div>
                @endforeach
                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-200 text-xs">
                    <span class="font-bold text-slate-500">Total:</span>
                    <span class="font-bold text-slate-900 font-mono" x-text="totals() + '%'"></span>
                </div>
            </div>
        </div>

        {{-- Ambang Kategori --}}
        <div class="admin-card overflow-hidden">
            <div class="admin-card-header">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Ambang Batas Kategori</h3>
            </div>
            <div class="p-4 space-y-3 bg-white">
                @php $offset = $bobotDimensi->count(); @endphp
                @foreach($ambangKategori as $param)
                    <div class="flex items-center gap-4 py-1 border-b border-slate-100 last:border-0 text-xs">
                        <label class="text-slate-800 font-medium flex-1">{{ $param->label }}</label>
                        <div class="flex items-center gap-1.5">
                            <input type="hidden" name="params[{{ $offset + $loop->index }}][id]" value="{{ $param->id }}">
                            <input type="number" name="params[{{ $offset + $loop->index }}][value]" value="{{ $param->value }}"
                                   step="0.01" min="0" max="100"
                                   class="w-24 px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded text-slate-900 font-mono font-bold text-right focus:border-blue-900 focus:outline-none">
                            <span class="text-slate-500 font-semibold">%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Floor --}}
        @if($floor)
        <div class="admin-card overflow-hidden">
            <div class="admin-card-header">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Ambang Floor Non-Kompensatori</h3>
            </div>
            <div class="p-4 bg-white">
                <div class="flex items-center gap-4 text-xs">
                    <label class="text-slate-800 font-medium flex-1">{{ $floor->label }}</label>
                    <div class="flex items-center gap-1.5">
                        @php $floorIdx = $bobotDimensi->count() + $ambangKategori->count(); @endphp
                        <input type="hidden" name="params[{{ $floorIdx }}][id]" value="{{ $floor->id }}">
                        <input type="number" name="params[{{ $floorIdx }}][value]" value="{{ $floor->value }}"
                               step="0.01" min="0" max="100"
                               class="w-24 px-2.5 py-1.5 bg-slate-50 border border-slate-300 rounded text-slate-900 font-mono font-bold text-right focus:border-blue-900 focus:outline-none">
                        <span class="text-slate-500 font-semibold">%</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="flex justify-end">
            <button type="submit"
                    class="px-6 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded shadow-xs transition-colors text-xs">
                Simpan Parameter
            </button>
        </div>
    </form>

</div>
@endsection
