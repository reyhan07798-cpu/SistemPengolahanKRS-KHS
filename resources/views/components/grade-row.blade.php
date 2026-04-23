{{-- Grade Row Component for Mahasiswa Dashboard --}}
@props([
    'matkul' => '',
    'sks' => '',
    'nilai' => '',
    'bobot' => ''
])

<tr class="hover:bg-gray-50">
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $matkul }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">{{ $sks }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">
        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
            @if($nilai == 'A') bg-green-100 text-green-800
            @elseif($nilai == 'A-') bg-green-100 text-green-700
            @elseif($nilai == 'B+') bg-blue-100 text-blue-800
            @elseif($nilai == 'B') bg-blue-100 text-blue-700
            @elseif($nilai == 'B-') bg-yellow-100 text-yellow-800
            @elseif($nilai == 'C+') bg-yellow-100 text-yellow-700
            @elseif($nilai == 'C') bg-orange-100 text-orange-800
            @elseif($nilai == 'D') bg-red-100 text-red-800
            @else bg-gray-100 text-gray-800
            @endif">
            {{ $nilai }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900 text-center">{{ number_format($bobot, 2) }}</td>
</tr>