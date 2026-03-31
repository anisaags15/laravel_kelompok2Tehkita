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
    Schema::table('distribusis', function (Blueprint $table) {
        // Taruh setelah kolom status
        $table->timestamp('tanggal_diterima')->nullable()->after('status');
    });
}

public function down()
{
    Schema::table('distribusis', function (Blueprint $table) {
        $table->dropColumn('tanggal_diterima');
    });
}
};
