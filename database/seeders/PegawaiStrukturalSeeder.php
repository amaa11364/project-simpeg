<?php
namespace Database\Seeders;
use App\Models\PegawaiStruktural;
use Illuminate\Database\Seeder;

class PegawaiStrukturalSeeder extends Seeder {
    public function run(): void {
        $csvFile = base_path('ref/db_kepegawaian_struktural.csv');
        $rows = array_map('str_getcsv', file($csvFile));
        $header = array_shift($rows); // skip header

        foreach ($rows as $row) {
            if (count($row) < 3 || empty(trim($row[2]))) continue;

            $ttl = isset($row[3]) ? trim($row[3]) : '';
            $parts = explode(', ', $ttl, 2);
            $tempat = count($parts) >= 1 ? trim($parts[0]) : '';
            $tanggal = count($parts) >= 2 ? trim($parts[1]) : '';

            PegawaiStruktural::create([
                'nomor_induk_kepegawaian' => isset($row[1]) ? trim($row[1]) : null,
                'nama' => trim($row[2]),
                'tempat_lahir' => $tempat ?: null,
                'tanggal_lahir' => $tanggal ?: null,
                'pendidikan' => isset($row[4]) ? trim($row[4]) : null,
                'jabatan' => isset($row[5]) ? trim($row[5]) : null,
                'alamat' => isset($row[6]) ? trim($row[6]) : null,
                'nik_ktp' => isset($row[7]) ? trim($row[7]) : null,
                'kepemilikan_rek_bri' => isset($row[8]) ? trim($row[8]) : null,
            ]);
        }
    }
}
