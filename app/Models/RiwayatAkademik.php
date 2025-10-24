<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatAkademik extends Model
{
    protected $table = 'riwayat_akademik';
    protected $primaryKey = 'id_riwayat';
    
    protected $fillable = [
        'nim_mahasiswa',
        'semester',
        'ip_semester',
        'sks_semester',
    ];

    protected function casts(): array
    {
        return [
            'ip_semester' => 'decimal:2',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'nim_mahasiswa', 'nim');
    }
}