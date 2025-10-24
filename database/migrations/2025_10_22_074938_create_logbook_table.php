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