<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan Migrasi untuk membuat tabel waste.
     */
    public function up(): void
    {
        Schema::create('waste', function (Blueprint $table) {
            $table->id();
            
            // 1. Catat Outlet mana yang lapor barang rusak
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('cascade');
            
            // 2. Catat Bahan Baku mana yang rusak (diambil dari stok outlet)
            $table->foreignId('stok_outlet_id')->constrained('stok_outlets')->onDelete('cascade');
            
            // 3. Catat berapa banyak yang rusak
            $table->integer('jumlah');
            
            // 4. Catat alasannya (contoh: "Susu kadaluwarsa" atau "Bungkus bocor")
            $table->text('keterangan')->nullable();
            
            $table->timestamps(); // Mencatat tanggal & waktu lapor secara otomatis
        });
    }

    /**
     * Batalkan migrasi (Hapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('waste');
    }
};