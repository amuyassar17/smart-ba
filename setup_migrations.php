<?php

// Script to populate all migration files for SMART-BA system

$migrations = [
    'dosen' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen', function (Blueprint $table) {
            $table->id('id_dosen');
            $table->string('nidn_dosen', 20)->unique()->nullable();
            $table->string('nama_dosen', 255);
            $table->string('password', 255);
            $table->string('foto_dosen', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
PHP,

    'mata_kuliah' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id('id_mk');
            $table->string('nama_mk', 100)->unique();
            $table->integer('sks')->nullable();
            $table->unsignedBigInteger('id_prodi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
PHP,

    'mahasiswa' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim', 20)->primary();
            $table->string('nama_mahasiswa', 255);
            $table->integer('angkatan');
            $table->char('status_semester', 1)->nullable()->comment('A = Aktif, N = Non-Aktif');
            $table->integer('semester_berjalan')->nullable();
            $table->integer('sks_semester')->nullable();
            $table->integer('batas_sks')->nullable();
            $table->integer('total_sks')->nullable();
            $table->decimal('ips', 3, 2)->nullable();
            $table->decimal('ipk', 3, 2)->nullable();
            $table->boolean('krs_disetujui')->default(false);
            $table->boolean('krs_notif_dilihat')->default(false);
            $table->unsignedBigInteger('id_prodi')->nullable();
            $table->unsignedBigInteger('id_dosen_pa')->nullable();
            $table->string('password', 255);
            $table->string('foto_mahasiswa', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_prodi')->references('id_prodi')->on('program_studi')->onDelete('set null');
            $table->foreign('id_dosen_pa')->references('id_dosen')->on('dosen')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
PHP,

    'logbook' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbook', function (Blueprint $table) {
            $table->id('id_log');
            $table->string('nim_mahasiswa', 20);
            $table->unsignedBigInteger('id_dosen');
            $table->enum('pengisi', ['Dosen', 'Mahasiswa'])->default('Dosen');
            $table->enum('status_baca', ['Dibaca', 'Belum Dibaca'])->default('Belum Dibaca');
            $table->date('tanggal_bimbingan');
            $table->string('topik_bimbingan', 255);
            $table->text('isi_bimbingan')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('nim_mahasiswa')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('id_dosen')->references('id_dosen')->on('dosen')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook');
    }
};
PHP,

    'dokumen' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id('id_dokumen');
            $table->string('nim_mahasiswa', 20);
            $table->unsignedBigInteger('id_dosen');
            $table->string('judul_dokumen', 255);
            $table->string('nama_file', 255)->comment('Nama file yang disimpan di server');
            $table->string('path_file', 255);
            $table->string('tipe_file', 100);
            $table->integer('ukuran_file')->comment('Dalam bytes');
            $table->timestamp('tanggal_unggah')->useCurrent();
            $table->enum('status_baca_dosen', ['Belum Dilihat', 'Sudah Dilihat'])->default('Belum Dilihat');

            $table->foreign('nim_mahasiswa')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('id_dosen')->references('id_dosen')->on('dosen')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
PHP,

    'evaluasi_dosen' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluasi_dosen', function (Blueprint $table) {
            $table->id('id_evaluasi_dosen');
            $table->string('nim_mahasiswa', 20);
            $table->unsignedBigInteger('id_dosen');
            $table->string('periode_evaluasi', 50);
            $table->integer('skor_komunikasi');
            $table->integer('skor_membantu');
            $table->integer('skor_solusi');
            $table->text('saran_kritik')->nullable();
            $table->timestamp('tanggal_submit')->useCurrent();

            $table->unique(['nim_mahasiswa', 'id_dosen', 'periode_evaluasi']);
            $table->foreign('nim_mahasiswa')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('id_dosen')->references('id_dosen')->on('dosen')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasi_dosen');
    }
};
PHP,

    'evaluasi_softskill' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluasi_softskill', function (Blueprint $table) {
            $table->id('id_evaluasi');
            $table->string('nim_mahasiswa', 20);
            $table->unsignedBigInteger('id_dosen');
            $table->string('periode_evaluasi', 50);
            $table->string('kategori', 100);
            $table->integer('skor');
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_evaluasi')->useCurrent();

            $table->foreign('nim_mahasiswa')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('id_dosen')->references('id_dosen')->on('dosen')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasi_softskill');
    }
};
PHP,

    'pencapaian' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pencapaian', function (Blueprint $table) {
            $table->id('id_pencapaian');
            $table->string('nim_mahasiswa', 20);
            $table->string('nama_pencapaian', 100);
            $table->enum('status', ['Selesai', 'Belum Selesai'])->default('Belum Selesai');
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->unique(['nim_mahasiswa', 'nama_pencapaian']);
            $table->foreign('nim_mahasiswa')->references('nim')->on('mahasiswa')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pencapaian');
    }
};
PHP,

    'riwayat_akademik' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_akademik', function (Blueprint $table) {
            $table->id('id_riwayat');
            $table->string('nim_mahasiswa', 20);
            $table->integer('semester');
            $table->decimal('ip_semester', 3, 2);
            $table->integer('sks_semester');
            $table->timestamps();

            $table->unique(['nim_mahasiswa', 'semester']);
            $table->foreign('nim_mahasiswa')->references('nim')->on('mahasiswa')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_akademik');
    }
};
PHP,

    'nilai_bermasalah' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_bermasalah', function (Blueprint $table) {
            $table->id('id_nilai');
            $table->string('nim_mahasiswa', 20);
            $table->string('nama_mk', 255);
            $table->char('nilai_huruf', 2);
            $table->integer('semester_diambil');
            $table->enum('status_perbaikan', ['Belum', 'Sudah'])->default('Belum');
            $table->timestamp('tanggal_lapor')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_bermasalah');
    }
};
PHP,

    'nilai_mahasiswa' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_mahasiswa', function (Blueprint $table) {
            $table->id('id_nilai');
            $table->string('nim_mahasiswa', 20);
            $table->string('kode_mk', 20);
            $table->string('nama_mk', 100);
            $table->char('nilai_huruf', 2);
            $table->integer('semester_diambil');
            $table->timestamps();

            $table->foreign('nim_mahasiswa')->references('nim')->on('mahasiswa')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_mahasiswa');
    }
};
PHP,

    'krs' => <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('krs', function (Blueprint $table) {
            $table->id('id_krs');
            $table->string('nim_mahasiswa', 20);
            $table->unsignedBigInteger('id_mk');
            $table->integer('semester_diambil');
            $table->char('nilai_huruf', 2)->nullable();
            $table->boolean('sudah_dinilai')->default(false);
            $table->timestamps();

            $table->foreign('nim_mahasiswa')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('id_mk')->references('id_mk')->on('mata_kuliah')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};
PHP
];

// Get migration directory
$migrationDir = __DIR__ . '/database/migrations';

// Get all migration files
$files = glob($migrationDir . '/*_create_*_table.php');

foreach ($files as $file) {
    $filename = basename($file);
    
    // Extract table name from filename
    foreach ($migrations as $table => $content) {
        if (strpos($filename, '_create_' . $table . '_table.php') !== false) {
            echo "Writing migration for table: $table\n";
            file_put_contents($file, $content);
            break;
        }
    }
}

echo "\n✅ All migrations have been populated!\n";
echo "\nNext steps:\n";
echo "1. Review migrations in database/migrations/\n";
echo "2. Run: php artisan migrate\n";
