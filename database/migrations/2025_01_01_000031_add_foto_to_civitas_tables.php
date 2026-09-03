<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('dosen', function (Blueprint $table) {
            $table->text('foto')->nullable()->after('nama');
        });
        Schema::table('pegawai_struktural', function (Blueprint $table) {
            $table->text('foto')->nullable()->after('nama');
        });
    }
    public function down(): void {
        Schema::table('dosen', fn($t) => $t->dropColumn('foto'));
        Schema::table('pegawai_struktural', fn($t) => $t->dropColumn('foto'));
    }
};
