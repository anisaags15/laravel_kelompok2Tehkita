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
    Schema::table('pemakaians', function (Blueprint $table) {
        // Cek kolom 'tipe'
        if (!Schema::hasColumn('pemakaians', 'tipe')) {
            $table->string('tipe')->default('rutin')->after('tanggal');
        }
        
        // Cek kolom 'status'
        if (!Schema::hasColumn('pemakaians', 'status')) {
            $table->string('status')->nullable()->after('tipe');
        }

        // Cek kolom 'keterangan'
        if (!Schema::hasColumn('pemakaians', 'keterangan')) {
            $table->text('keterangan')->nullable()->after('status');
        }
    });
}

public function down(): void
{
    Schema::table('pemakaians', function (Blueprint $table) {
        $table->dropColumn(['tipe', 'status', 'keterangan']);
    });
}
};
