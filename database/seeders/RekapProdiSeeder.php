<?php
namespace Database\Seeders;
use App\Models\RekapProdi;
use Illuminate\Database\Seeder;

class RekapProdiSeeder extends Seeder {
    public function run(): void {
        $csvFile = base_path('ref/db_format_rekap.csv');
        $rows = array_map('str_getcsv', file($csvFile));
        array_shift($rows); // skip header

        foreach ($rows as $row) {
            if (count($row) < 2 || empty(trim($row[1])) || strtolower(trim($row[1])) === 'jumlah') continue;

            RekapProdi::create([
                'program_studi' => trim($row[1]),
                'jumlah_dosen' => isset($row[2]) ? (int) trim($row[2]) : 0,
                'jumlah_guru_besar' => isset($row[3]) ? (int) trim($row[3]) : 0,
                'jumlah_doktor' => isset($row[4]) ? (int) trim($row[4]) : 0,
                'jumlah_magister' => isset($row[5]) ? (int) trim($row[5]) : 0,
            ]);
        }
    }
}
