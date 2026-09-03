<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model {
    protected $table = 'dosen';
    protected $fillable = [
        'nidn_nik', 'nama', 'foto', 'tempat_lahir', 'tanggal_lahir',
        'pendidikan', 'homebase_prodi', 'alamat', 'nik_ktp', 'kepemilikan_rek_bri', 'status'
    ];
}
