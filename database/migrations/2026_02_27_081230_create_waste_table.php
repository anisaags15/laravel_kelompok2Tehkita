<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('cascade');
            $table->foreignId('stok_outlet_id')->constrained('stok_outlets')->onDelete('cascade');
            $table->integer('jumlah');
            $table->date('tanggal'); // <-- TAMBAHKAN INI AGAR BISA FILTER PER BULAN
            $table->text('keterangan')->nullable();
            $table->string('status')->default('pending'); // <-- TAMBAHKAN UNTUK VERIFIKASI ADMIN
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste');
    }
};