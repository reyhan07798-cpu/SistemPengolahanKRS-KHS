<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosens'; // Sesuaikan dengan nama tabel di database

    protected $fillable = [
        'nip',
        'nama',
        'email',
        // tambahkan field lain jika ada
    ];
}