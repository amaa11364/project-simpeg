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
        Schema::table('pegawai_struktural', function (Blueprint $table) {
            $table->string('status')->default('Aktif')->after('kepemilikan_rek_bri');
        });
        Schema::table('dosen', function (Blueprint $table) {
            $table->string('status')->default('Aktif')->after('kepemilikan_rek_bri');
        });
    }

    public function down(): void
    {
        Schema::table('pegawai_struktural', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('dosen', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
