{{-- Score color class based on value --}}
@php
    $s = $score ?? 0;
    if ($s >= 85) { echo 'score-sangat-baik'; }
    elseif ($s >= 75) { echo 'score-baik'; }
    elseif ($s >= 65) { echo 'score-cukup'; }
    elseif ($s >= 50) { echo 'score-perlu-perbaikan'; }
    else { echo 'score-kritis'; }
@endphp
