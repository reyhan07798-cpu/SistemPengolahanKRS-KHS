@props([
    'tahunLabel' => 'Tahun Ajaran',
    'semesterLabel' => 'Semester',
    'idPrefix' => ''
])

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="{{ $idPrefix }}tahun_ajaran" class="nb-label">{{ $tahunLabel }}</label>
        <select id="{{ $idPrefix }}tahun_ajaran" name="tahun_ajaran">
            <option value="">-- Pilih --</option>
            <option value="2025/2026">2025/2026</option>
            <option value="2024/2025">2024/2025</option>
            <option value="2023/2024">2023/2024</option>
        </select>
    </div>
    <div>
        <label for="{{ $idPrefix }}semester" class="nb-label">{{ $semesterLabel }}</label>
        <select id="{{ $idPrefix }}semester" name="semester">
            <option value="">-- Pilih --</option>
            <option value="1">Semester 1</option>
            <option value="2">Semester 2</option>
            <option value="3">Semester 3</option>
            <option value="4">Semester 4</option>
        </select>
    </div>
</div>

