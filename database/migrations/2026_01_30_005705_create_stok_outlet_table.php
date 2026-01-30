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
    Schema::create('stok_outlets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('outlet_id')->constrained('outlets');
        $table->foreignId('bahan_id')->constrained('bahans');
        $table->integer('stok')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_outlets');
    }
};