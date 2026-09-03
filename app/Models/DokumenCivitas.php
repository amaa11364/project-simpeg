<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DokumenCivitas extends Model {
    protected $table = 'dokumen_civitas';
    protected $fillable = ['civitas_type', 'civitas_id', 'jenis_dokumen', 'link', 'keterangan'];

    // Daftar dokumen wajib per tipe civitas
    public static array $jenisDosen = [
        'pas_foto'           => 'Pas Foto',
        'ktp'                => 'KTP',
        'kk'                 => 'Kartu Keluarga (KK)',
        'npwp'               => 'NPWP',
        'ijazah_s1'          => 'Ijazah S1',
        'ijazah_s2'          => 'Ijazah S2',
        'ijazah_s3'          => 'Ijazah S3',
        'sk_pengangkatan'    => 'SK Pengangkatan',
        'serdos'             => 'Sertifikat Pendidik (Serdos)',
        'sk_jabatan_fungsional' => 'SK Jabatan Fungsional',
        'rek_bri'            => 'Rekening BRI',
    ];

    public static array $jenisPegawai = [
        'pas_foto'        => 'Pas Foto',
        'ktp'             => 'KTP',
        'kk'              => 'Kartu Keluarga (KK)',
        'npwp'            => 'NPWP',
        'ijazah_terakhir' => 'Ijazah Terakhir',
        'sk_pengangkatan' => 'SK Pengangkatan',
        'sk_jabatan'      => 'SK Jabatan',
        'rek_bri'         => 'Rekening BRI',
    ];

    public static function getJenis(string $civitasType): array {
        return $civitasType === 'dosen' ? static::$jenisDosen : static::$jenisPegawai;
    }

    public static function getKelengkapan(string $civitasType, int $civitasId): array {
        $jenis = static::getJenis($civitasType);
        $total = count($jenis);
        $dokumen = static::where('civitas_type', $civitasType)
            ->where('civitas_id', $civitasId)
            ->whereNotNull('link')
            ->where('link', '!=', '')
            ->pluck('link', 'jenis_dokumen');
        $terisi = $dokumen->count();
        return [
            'total'      => $total,
            'terisi'     => $terisi,
            'persen'     => $total > 0 ? round($terisi / $total * 100) : 0,
            'dokumen'    => $dokumen,
        ];
    }
}
