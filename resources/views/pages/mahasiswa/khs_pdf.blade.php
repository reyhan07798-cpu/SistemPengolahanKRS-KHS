<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KHS</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
        }
        .center {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
        }
        .no-border td {
            border: none;
        }
    </style>
</head>
<body>

    <h3 class="center">KARTU HASIL STUDI</h3>
    <h4 class="center">TAHUN AKADEMIK {{ $tahun }} SEMESTER {{ $semester }}</h4>

    <br>

    <table class="no-border">
        <tr>
            <td>Nama Mahasiswa</td><td>: {{ $mahasiswa->nama }}</td>
            <td>Semester</td><td>: {{ $semester_ke }}</td>
        </tr>
        <tr>
            <td>NIM</td><td>: {{ $mahasiswa->nim }}</td>
            <td>Kelas</td><td>: {{ $kelas }}</td>
        </tr>
        <tr>
            <td>Program Studi</td><td>: {{ $prodi }}</td>
            <td>Pembimbing Akademik</td><td>: {{ $pa }}</td>
        </tr>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Mata Kuliah</th>
                <th>SKS</th>
                <th>Nilai</th>
                <th>Angka</th>
                <th>K x N</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $total_sks = 0;
                $total_kn = 0;
            @endphp

            @foreach($khs as $i => $item)
            @php
                $kn = $item->sks * $item->angka;
                $total_sks += $item->sks;
                $total_kn += $kn;
            @endphp
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->mata_kuliah }}</td>
                <td>{{ $item->sks }}</td>
                <td>{{ $item->nilai }}</td>
                <td>{{ $item->angka }}</td>
                <td>{{ $kn }}</td>
            </tr>
            @endforeach

            <tr>
                <td colspan="3" class="center"><b>Jumlah</b></td>
                <td>{{ $total_sks }}</td>
                <td></td>
                <td></td>
                <td>{{ $total_kn }}</td>
            </tr>
        </tbody>
    </table>

    <br><br>

    @php
        $ips = $total_kn / ($total_sks ?: 1);
    @endphp

    <table class="no-border">
        <tr>
            <td>Indeks Prestasi Semester</td><td>: {{ number_format($ips, 2) }}</td>
        </tr>
        <tr>
            <td>Indeks Prestasi Kumulatif</td><td>: {{ number_format($ips, 2) }}</td>
        </tr>
        <tr>
            <td>SKS yang telah diambil</td><td>: {{ $total_sks }}</td>
        </tr>
        <tr>
            <td>SKS maksimum</td><td>: 24</td>
        </tr>
    </table>

    <br><br>

    <div style="text-align: right;">
        <p>Batam, {{ date('d F Y') }}</p>
        <p>Mengetahui,</p>
        <p>Ketua Program Studi</p>
        <br><br><br>
        <p><b>{{ $kaprodi }}</b></p>
        <p>NIP. {{ $nip }}</p>
    </div>

</body>
</html>