<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('pemakaians', function (Blueprint $table) {
        // 'rutin' untuk jualan, 'waste' untuk rusak/basi
        $table->string('tipe')->default('rutin')->after('jumlah'); 
        $table->string('keterangan')->nullable()->after('tipe');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemakaians', function (Blueprint $table) {
            //
        });
    }
};
