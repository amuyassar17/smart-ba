<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nilai_bermasalah', function (Blueprint $table) {
            $table->boolean('dikirim_ke_logbook')->default(false)->after('status_perbaikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_bermasalah', function (Blueprint $table) {
            $table->dropColumn('dikirim_ke_logbook');
        });
    }
};
