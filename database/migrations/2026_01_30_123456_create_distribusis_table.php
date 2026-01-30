<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void {
    Schema::create('distribusis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('bahan_id')->constrained('bahans');
        $table->foreignId('outlet_id')->constrained('outlets');
        $table->integer('jumlah');
        $table->enum('status', ['dikirim', 'diterima'])->default('dikirim');
        $table->date('tanggal');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusis');
    }
};