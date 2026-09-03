<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rekap_prodi', function (Blueprint $table) {
            $table->id();
            $table->string('program_studi');
            $table->integer('jumlah_dosen')->default(0);
            $table->integer('jumlah_guru_besar')->default(0);
            $table->integer('jumlah_doktor')->default(0);
            $table->integer('jumlah_magister')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('rekap_prodi');
    }
};
