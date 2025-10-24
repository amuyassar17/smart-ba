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