<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiMahasiswa extends Model
{
    protected $table = 'nilai_mahasiswa';
    protected $primaryKey = 'id_nilai';
    
    protected $fillable = [
        'nim_mahasiswa',
        'kode_mk',
        'nama_mk',
        'nilai_huruf',
        'semester_diambil',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'nim_mahasiswa', 'nim');
    }
}