<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pegawai_struktural', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_induk_kepegawaian')->nullable();
            $table->string('nama');
            $table->string('tempat_lahir')->nullable();
            $table->string('tanggal_lahir')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('jabatan')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nik_ktp')->nullable();
            $table->string('kepemilikan_rek_bri')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pegawai_struktural');
    }
};
