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