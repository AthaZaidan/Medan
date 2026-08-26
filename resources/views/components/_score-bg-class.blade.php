{{-- Score background class based on value --}}
@php
    $s = $score ?? 0;
    if ($s >= 85) { echo 'bg-emerald-500'; }
    elseif ($s >= 75) { echo 'bg-green-500'; }
    elseif ($s >= 65) { echo 'bg-yellow-500'; }
    elseif ($s >= 50) { echo 'bg-orange-500'; }
    else { echo 'bg-red-500'; }
@endphp
