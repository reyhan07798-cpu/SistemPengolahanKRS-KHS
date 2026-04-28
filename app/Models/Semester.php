<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semesters';

    protected $fillable = [
        'nama',
        'tahun_ajaran',
        'semester_ke',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Get all mata kuliah for this semester
     */
    public function mataKuliahs()
    {
        return $this->hasMany(MataKuliah::class, 'semester_id');
    }

    /**
     * Get all KRS for this semester
     */
    public function krsMahasiswas()
    {
        return $this->hasMany(KrsMahasiswa::class, 'semester_id');
    }

    /**
     * Scope for active semester
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}