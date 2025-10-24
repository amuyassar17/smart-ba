<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mahasiswa extends Authenticatable
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'nim',
        'nama_mahasiswa',
        'angkatan',
        'status_semester',
        'semester_berjalan',
        'sks_semester',
        'batas_sks',
        'total_sks',
        'ips',
        'ipk',
        'krs_disetujui',
        'krs_notif_dilihat',
        'id_prodi',
        'id_dosen_pa',
        'password',
        'foto_mahasiswa',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'krs_disetujui' => 'boolean',
            'krs_notif_dilihat' => 'boolean',
            'ips' => 'decimal:2',
            'ipk' => 'decimal:2',
            'password' => 'hashed',
        ];
    }

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function dosenPA(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'id_dosen_pa', 'id_dosen');
    }

    public function logbook(): HasMany
    {
        return $this->hasMany(Logbook::class, 'nim_mahasiswa', 'nim');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'nim_mahasiswa', 'nim');
    }

    public function evaluasiDosen(): HasMany
    {
        return $this->hasMany(EvaluasiDosen::class, 'nim_mahasiswa', 'nim');
    }

    public function evaluasiSoftskill(): HasMany
    {
        return $this->hasMany(EvaluasiSoftskill::class, 'nim_mahasiswa', 'nim');
    }

    public function pencapaian(): HasMany
    {
        return $this->hasMany(Pencapaian::class, 'nim_mahasiswa', 'nim');
    }

    public function riwayatAkademik(): HasMany
    {
        return $this->hasMany(RiwayatAkademik::class, 'nim_mahasiswa', 'nim');
    }

    public function nilaiBermasalah(): HasMany
    {
        return $this->hasMany(NilaiBermasalah::class, 'nim_mahasiswa', 'nim');
    }

    public function nilaiMahasiswa(): HasMany
    {
        return $this->hasMany(NilaiMahasiswa::class, 'nim_mahasiswa', 'nim');
    }

    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class, 'nim_mahasiswa', 'nim');
    }
}