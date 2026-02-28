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
        Schema::create('pemakaians', function (Blueprint $table) {
            $table->id();

            // Relasi ke bahan baku
            $table->foreignId('bahan_id')
                  ->constrained('bahans')
                  ->cascadeOnDelete();

            // Relasi ke outlet
            $table->foreignId('outlet_id')
                  ->constrained('outlets')
                  ->cascadeOnDelete();

            $table->integer('jumlah');
            $table->date('tanggal');

            // --- KOLOM TAMBAHAN UNTUK WASTE ---
            // tipe: untuk membedakan 'rutin' atau 'waste'
            $table->string('tipe')->default('rutin'); 
            
            // keterangan: alasan kenapa barang jadi waste (rusak/kadaluwarsa)
            $table->text('keterangan')->nullable();
            
            // status: untuk verifikasi dari admin pusat
            $table->string('status')->default('pending'); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemakaians');
    }
};