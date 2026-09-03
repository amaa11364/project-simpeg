<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PegawaiStruktural extends Model {
    protected $table = 'pegawai_struktural';
    protected $fillable = [
        'nomor_induk_kepegawaian', 'nama', 'foto', 'tempat_lahir', 'tanggal_lahir',
        'pendidikan', 'jabatan', 'alamat', 'nik_ktp', 'kepemilikan_rek_bri', 'status'
    ];
}
