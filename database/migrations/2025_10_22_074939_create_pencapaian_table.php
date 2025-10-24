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