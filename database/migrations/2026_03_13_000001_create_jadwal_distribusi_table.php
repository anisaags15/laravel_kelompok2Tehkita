<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_distribusi', function (Blueprint $table) {
            $table->id();
            $table->string('keterangan'); // contoh: "Distribusi Rutin Maret 2026"
            $table->date('tanggal_rencana');
            $table->enum('status', ['upcoming', 'selesai'])->default('upcoming');
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_distribusi');
    }
};