@props([
    'matkul' => '',
    'sks' => '',
    'nilai' => '',
    'bobot' => ''
])

@php
    $gradeColor = match($nilai) {
        'A' => 'bg-green-100 text-green-700',
        'B' => 'bg-blue-100 text-blue-700',
        'C' => 'bg-yellow-100 text-yellow-700',
        'D' => 'bg-orange-100 text-orange-700',
        default => 'bg-red-100 text-red-700',
    };
@endphp

<tr class="hover:bg-gray-50 transition">
    <td class="px-6 py-4 text-sm text-dark">{{ $matkul }}</td>
    <td class="px-6 py-4 text-sm text-center">{{ $sks }}</td>
    <td class="px-6 py-4 text-center">
        <span class="px-2 py-1 text-xs font-semibold rounded {{ $gradeColor }}">
            {{ $nilai }}
        </span>
    </td>
    <td class="px-6 py-4 text-sm text-center font-medium">{{ $bobot }}</td>
</tr>