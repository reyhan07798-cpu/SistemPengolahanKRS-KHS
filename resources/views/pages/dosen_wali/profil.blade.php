@extends('layouts.dosen')

@section('title', 'Profil Dosen Wali')
@section('page_title', 'Profil')

@section('content')
    <x-profile-dosen 
        :dosen="$dosen"
        route-update="{{ route('pages.dosen_wali.profil.update') }}"
        route-password="{{ route('pages.dosen_wali.profil.password') }}"
        role="Dosen Wali"
        icon="badge"
        id-field="{{ $dosen['nip'] }}" />
@endsection
