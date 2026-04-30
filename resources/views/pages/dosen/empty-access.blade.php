@extends('layouts.dosen')

@section('content')
<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:60vh;text-align:center;padding:2rem;">
    <div style="margin-bottom:1.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#9CA3AF" viewBox="0 0 16 16">
            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
        </svg>
    </div>
    <h3 style="font-size:1.25rem;font-weight:700;color:#374151;margin-bottom:0.5rem;">Akses Dibatasi</h3>
    <p style="color:#6B7280;margin-bottom:1.5rem;">Maaf, fitur <strong>{{ $roleName ?? 'ini' }}</strong> tidak tersedia untuk peran Anda saat ini.</p>
    <a href="{{ route('dosen.wali.beranda') }}" style="padding:10px 24px;background:#2F5D8A;color:white;border-radius:8px;text-decoration:none;font-weight:600;font-size:0.9rem;">
        Kembali ke Beranda
    </a>
</div>
@endsection
