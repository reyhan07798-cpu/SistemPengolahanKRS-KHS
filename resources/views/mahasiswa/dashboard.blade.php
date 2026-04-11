@extends('layouts.mahasiswa')

@section('content')

<h3 class="mb-4">Beranda Mahasiswa</h3>

<div class="row g-3">

    <div class="col-md-3">
        <div class="card-box">
            <h6>Semester Aktif</h6>
            <h3>2</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box">
            <h6>SKS Diambil</h6>
            <h3>15</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box">
            <h6>IPK</h6>
            <h3>3.64</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box">
            <h6>Lulus</h6>
            <h3>7</h3>
        </div>
    </div>

</div>

<div class="card-box mt-4">
    <h5>KRS Aktif</h5>

    <table class="table">
        <tr>
            <th>Mata Kuliah</th>
            <th>SKS</th>
        </tr>

        <tr>
            <td>Basis Data</td>
            <td>3</td>
        </tr>
    </table>
</div>

@endsection