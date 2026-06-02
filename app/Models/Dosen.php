<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'user_id',
        'nik',
        'nip',
        'nama',
        'email',
        'no_hp',
        'alamat',
        'tipe_dosen',
    ];
}