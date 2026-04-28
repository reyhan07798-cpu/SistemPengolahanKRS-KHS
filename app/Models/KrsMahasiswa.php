<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KrsMahasiswa extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'status',
        'semester',
        'tahun_ajaran',
        'is_retake',
        'status_perkuliahan',
    ];

    protected $casts = [
        'is_retake' => 'boolean',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function nilai()
    {
        return $this->hasOne(Nilai::class, 'krs_id');
    }

    public function nilaiHistory()
    {
        return $this->hasMany(NilaiHistory::class, 'krs_id');
    }

    public function scopeRetake($query)
    {
        return $query->where('is_retake', true);
    }

    public function scopeAktif($query)
    {
        return $query->where('status_perkuliahan', 'aktif');
    }
}

