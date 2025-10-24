<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluasiDosen extends Model
{
    protected $table = 'evaluasi_dosen';
    protected $primaryKey = 'id_evaluasi_dosen';
    public $timestamps = false;
    
    protected $fillable = [
        'nim_mahasiswa',
        'id_dosen',
        'periode_evaluasi',
        'skor_komunikasi',
        'skor_membantu',
        'skor_solusi',
        'saran_kritik',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_submit' => 'datetime',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'nim_mahasiswa', 'nim');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }
}