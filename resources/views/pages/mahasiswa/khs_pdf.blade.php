<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KHS</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            color: #000;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 5px;
            vertical-align: top;
        }

        .no-border td {
            border: none;
            padding: 3px;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .mt-40 {
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <h3 class="center">KARTU HASIL STUDI</h3>
    <h4 class="center">TAHUN AKADEMIK {{ $tahun }} SEMESTER {{ $semester }}</h4>

    <br>

    <table class="no-border">
        <tr>
            <td style="width: 18%;">Nama Mahasiswa</td>
            <td style="width: 32%;">: {{ $mahasiswa->nama ?? '-' }}</td>
            <td style="width: 18%;">Semester</td>
            <td style="width: 32%;">: {{ $semester_ke ?? '-' }}</td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>: {{ $mahasiswa->nim ?? '-' }}</td>
            <td>Kelas</td>
            <td>: {{ $kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>: {{ $prodi ?? '-' }}</td>
            <td>Pembimbing Akademik</td>
            <td>: {{ $pa ?? '-' }}</td>
        </tr>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 13%;">Kode</th>
                <th>Mata Kuliah</th>
                <th style="width: 8%;">SKS</th>
                <th style="width: 10%;">Nilai</th>
                <th style="width: 10%;">Angka</th>
                <th style="width: 10%;">K x N</th>
            </tr>
        </thead>

        <tbody>
            @forelse($khs as $i => $item)
                @php
                    $kn = $item->sks * $item->angka;
                @endphp

                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->mata_kuliah }}</td>
                    <td class="center">{{ $item->sks }}</td>
                    <td class="center">{{ $item->nilai }}</td>
                    <td class="center">{{ number_format($item->angka, 2) }}</td>
                    <td class="center">{{ number_format($kn, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">Belum ada data nilai.</td>
                </tr>
            @endforelse

            <tr>
                <td colspan="3" class="center bold">Jumlah</td>
                <td class="center bold">{{ $total_sks }}</td>
                <td></td>
                <td></td>
                <td class="center bold">{{ number_format($total_kn, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <br>

    <table class="no-border">
        <tr>
            <td style="width: 30%;">Indeks Prestasi Semester</td>
            <td>: {{ number_format($ips, 2) }}</td>
        </tr>
        <tr>
            <td>Indeks Prestasi Kumulatif</td>
            <td>: {{ number_format($ipk, 2) }}</td>
        </tr>
        <tr>
            <td>SKS yang telah diambil</td>
            <td>: {{ $total_sks }}</td>
        </tr>
        <tr>
            <td>SKS maksimum</td>
            <td>: 24</td>
        </tr>
    </table>

    <br><br>

    <div class="right">
        <p>Batam, {{ date('d F Y') }}</p>
        <p>Mengetahui,</p>
        <p>Ketua Program Studi</p>

        <br><br><br>

        <p class="bold">{{ $kaprodi }}</p>
        <p>NIP. {{ $nip }}</p>
    </div>

</body>
</html>