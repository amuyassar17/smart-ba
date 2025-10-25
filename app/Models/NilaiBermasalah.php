<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiBermasalah extends Model
{
    protected $table = 'nilai_bermasalah';
    protected $primaryKey = 'id_nilai';
    public $timestamps = false;
    
    protected $fillable = [
        'nim_mahasiswa',
        'nama_mk',
        'nilai_huruf',
        'semester_diambil',
        'status_perbaikan',
        'dikirim_ke_logbook',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lapor' => 'datetime',
        ];
    }
}