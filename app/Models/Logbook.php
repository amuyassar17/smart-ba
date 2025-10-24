<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Logbook extends Model
{
    protected $table = 'logbook';
    protected $primaryKey = 'id_log';
    public $timestamps = false;
    
    protected $fillable = [
        'nim_mahasiswa',
        'id_dosen',
        'pengisi',
        'status_baca',
        'tanggal_bimbingan',
        'topik_bimbingan',
        'isi_bimbingan',
        'tindak_lanjut',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bimbingan' => 'date',
            'created_at' => 'datetime',
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