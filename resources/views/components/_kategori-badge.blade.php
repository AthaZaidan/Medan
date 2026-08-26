@php
    $k = $kategori ?? '';
    $classes = match(true) {
        str_contains($k, 'Sangat Baik') => 'badge-sangat-baik',
        str_contains($k, 'Baik') && !str_contains($k, 'Sangat') => 'badge-baik',
        str_contains($k, 'Cukup') => 'badge-cukup',
        str_contains($k, 'Perlu Perbaikan') => 'badge-perlu-perbaikan',
        str_contains($k, 'Kritis') => 'badge-kritis',
        default => 'bg-slate-100 text-slate-600 border border-slate-200',
    };
@endphp
<span class="badge {{ $classes }}">
    {{ $k }}
</span>
