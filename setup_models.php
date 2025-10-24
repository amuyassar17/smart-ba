<?php

// Script to populate all Model files for SMART-BA system

$models = [
    'ProgramStudi' => <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramStudi extends Model
{
    protected $table = 'program_studi';
    protected $primaryKey = 'id_prodi';
    
    protected $fillable = [
        'nama_prodi',
    ];

    public function mahasiswa(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'id_prodi', 'id_prodi');
    }

    public function mataKuliah(): HasMany
    {
        return $this->hasMany(MataKuliah::class, 'id_prodi', 'id_prodi');
    }
}
PHP,

    'Dosen' => <<<'PHP'
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
PHP,

    'MataKuliah' => <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';
    protected $primaryKey = 'id_mk';
    
    protected $fillable = [
        'nama_mk',
        'sks',
        'id_prodi',
    ];

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class, 'id_mk', 'id_mk');
    }
}
PHP,

    'Mahasiswa' => <<<'PHP'
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
PHP,

    'Logbook' => <<<'PHP'
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
PHP,

    'Dokumen' => <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokumen extends Model
{
    protected $table = 'dokumen';
    protected $primaryKey = 'id_dokumen';
    public $timestamps = false;
    
    protected $fillable = [
        'nim_mahasiswa',
        'id_dosen',
        'judul_dokumen',
        'nama_file',
        'path_file',
        'tipe_file',
        'ukuran_file',
        'status_baca_dosen',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_unggah' => 'datetime',
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
PHP,

    'EvaluasiDosen' => <<<'PHP'
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
PHP,

    'EvaluasiSoftskill' => <<<'PHP'
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
PHP,

    'Pencapaian' => <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pencapaian extends Model
{
    protected $table = 'pencapaian';
    protected $primaryKey = 'id_pencapaian';
    
    protected $fillable = [
        'nim_mahasiswa',
        'nama_pencapaian',
        'status',
        'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_selesai' => 'date',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'nim_mahasiswa', 'nim');
    }
}
PHP,

    'RiwayatAkademik' => <<<'PHP'
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
PHP,

    'NilaiBermasalah' => <<<'PHP'
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
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lapor' => 'datetime',
        ];
    }
}
PHP,

    'NilaiMahasiswa' => <<<'PHP'
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
PHP,

    'Krs' => <<<'PHP'
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
PHP
];

// Get models directory
$modelsDir = __DIR__ . '/app/Models';

foreach ($models as $modelName => $content) {
    $file = $modelsDir . '/' . $modelName . '.php';
    if (file_exists($file)) {
        echo "Writing model: $modelName\n";
        file_put_contents($file, $content);
    }
}

echo "\n✅ All models have been populated with relationships!\n";
echo "\nNext steps:\n";
echo "1. Test models in tinker: php artisan tinker\n";
echo "2. Create seeders for test data\n";
echo "3. Start building controllers and views\n";
