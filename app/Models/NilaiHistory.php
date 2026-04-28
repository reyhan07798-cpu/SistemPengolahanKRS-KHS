<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiHistory extends Model
{
    protected $table = 'nilai_history';

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'krs_id',
        'nilai',
        'bobot',
        'sks',
        'semester',
        'tahun_ajaran',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function krs()
    {
        return $this->belongsTo(KrsMahasiswa::class, 'krs_id');
    }
}

