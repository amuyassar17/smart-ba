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