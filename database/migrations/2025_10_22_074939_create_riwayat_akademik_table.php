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