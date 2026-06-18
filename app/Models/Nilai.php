<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai';

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'semester_id',
        'tahun_ajaran',
        'semester',
        'nilai',
        'bobot',
        'sks',
        'status',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function semesterData()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}