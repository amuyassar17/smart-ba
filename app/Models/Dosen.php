<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Dosen extends Authenticatable
{
    protected $table = 'dosen';
    protected $primaryKey = 'id_dosen';
    
    protected $fillable = [
        'nidn_dosen',
        'nama_dosen',
        'password',
        'foto_dosen',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function mahasiswa(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'id_dosen_pa', 'id_dosen');
    }

    public function logbook(): HasMany
    {
        return $this->hasMany(Logbook::class, 'id_dosen', 'id_dosen');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'id_dosen', 'id_dosen');
    }

    public function evaluasiDosen(): HasMany
    {
        return $this->hasMany(EvaluasiDosen::class, 'id_dosen', 'id_dosen');
    }

    public function evaluasiSoftskill(): HasMany
    {
        return $this->hasMany(EvaluasiSoftskill::class, 'id_dosen', 'id_dosen');
    }
}