@extends('layouts.dosen')

@section('title', 'Profil Dosen Matkul')
@section('page_title', 'Profil')

@section('content')
    <x-profile-dosen 
        :dosen="$dosen"
        route-update="{{ route('pages.dosen_matkul.profil.update') }}"
        route-password="{{ route('pages.dosen_matkul.profil.password') }}"
        role="Dosen Mata Kuliah"
        icon="badge"
        id-field="{{ $dosen['nidn'] }}" />
@endsection
