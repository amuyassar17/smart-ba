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