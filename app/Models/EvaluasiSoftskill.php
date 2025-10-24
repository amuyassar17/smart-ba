<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluasiSoftskill extends Model
{
    protected $table = 'evaluasi_softskill';
    protected $primaryKey = 'id_evaluasi';
    public $timestamps = false;
    
    protected $fillable = [
        'nim_mahasiswa',
        'id_dosen',
        'periode_evaluasi',
        'kategori',
        'skor',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_evaluasi' => 'datetime',
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