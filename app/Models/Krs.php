<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Krs extends Model
{
    protected $table = 'krs';
    protected $primaryKey = 'id_krs';
    
    protected $fillable = [
        'nim_mahasiswa',
        'id_mk',
        'semester_diambil',
        'nilai_huruf',
        'sudah_dinilai',
    ];

    protected function casts(): array
    {
        return [
            'sudah_dinilai' => 'boolean',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'nim_mahasiswa', 'nim');
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'id_mk', 'id_mk');
    }
}