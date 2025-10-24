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