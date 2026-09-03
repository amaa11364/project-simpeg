<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RekapProdi extends Model {
    protected $table = 'rekap_prodi';
    protected $fillable = [
        'program_studi', 'jumlah_dosen', 'jumlah_guru_besar',
        'jumlah_doktor', 'jumlah_magister'
    ];
}
