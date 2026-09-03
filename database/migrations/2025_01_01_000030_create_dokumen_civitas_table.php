<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dokumen_civitas', function (Blueprint $table) {
            $table->id();
            $table->string('civitas_type'); // 'dosen' atau 'pegawai_struktural'
            $table->unsignedBigInteger('civitas_id');
            $table->string('jenis_dokumen'); // ktp, kk, npwp, dst.
            $table->text('link')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->unique(['civitas_type', 'civitas_id', 'jenis_dokumen'], 'unique_dokumen');
            $table->index(['civitas_type', 'civitas_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('dokumen_civitas');
    }
};
