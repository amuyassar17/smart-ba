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