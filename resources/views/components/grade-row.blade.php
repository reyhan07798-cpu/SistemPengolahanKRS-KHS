@props([
    'matkul' => '',
    'sks' => '',
    'nilai' => '',
    'bobot' => ''
])

@php
    $gradeBadge = match($nilai) {
        'A' => 'nb-badge-success',
        'B' => 'nb-badge-primary',
        'C' => 'nb-badge-warning',
        'D' => 'nb-badge-warning',
        default => 'nb-badge-danger',
    };
@endphp

<tr>
    <td class="font-medium text-ink">{{ $matkul }}</td>
    <td class="text-center">{{ $sks }}</td>
    <td class="text-center">
        <span class="nb-badge {{ $gradeBadge }}">{{ $nilai }}</span>
    </td>
    <td class="text-center font-bold text-primary">{{ $bobot }}</td>
</tr>
