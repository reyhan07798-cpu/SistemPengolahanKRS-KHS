@extends('layouts.admin')

@section('content')

<h3 class="mb-4">Beranda Admin</h3>

<div class="row g-3">

    <div class="col-md-3">
        <div class="card-box">
            <h6>Total Mahasiswa</h6>
            <h3>5</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box">
            <h6>Total Dosen</h6>
            <h3>5</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box">
            <h6>Total Mata Kuliah</h6>
            <h3>10</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box">
            <h6>Rata-rata IPK</h6>
            <h3>3.5</h3>
        </div>
    </div>

</div>

<div class="card-box mt-4">
    <h5>Peringkat IPK Mahasiswa</h5>

    <table class="table mt-3">
        <tr>
            <th>Nama</th>
            <th>IPK</th>
        </tr>

        <tr>
            <td>Reyhan</td>
            <td>3.85</td>
        </tr>

    </table>
</div>

@endsection