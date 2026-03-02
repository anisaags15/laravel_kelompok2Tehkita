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
        Schema::table('waste', function (Blueprint $table) {
            // Kita tambahkan kolom foto setelah kolom keterangan
            // Pakai nullable() supaya data lama yang belum punya foto nggak error
            $table->string('foto')->nullable()->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waste', function (Blueprint $table) {
            // Ini untuk menghapus kolom foto kalau kamu melakukan rollback
            $table->dropColumn('foto');
        });
    }
};