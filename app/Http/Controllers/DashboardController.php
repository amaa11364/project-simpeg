<?php
namespace App\Http\Controllers;
use App\Models\Dosen;
use App\Models\PegawaiStruktural;
use App\Models\RekapProdi;
use Inertia\Inertia;

class DashboardController extends Controller {
    public function index() {
        $dosen_rek_bri = Dosen::where('kepemilikan_rek_bri', 'Sudah Punya')->count();
        $pegawai_rek_bri = PegawaiStruktural::where('kepemilikan_rek_bri', 'Sudah Punya')->count();
        $dosen_belum = Dosen::where('kepemilikan_rek_bri', '!=', 'Sudah Punya')->orWhereNull('kepemilikan_rek_bri')->count();
        $pegawai_belum = PegawaiStruktural::where('kepemilikan_rek_bri', '!=', 'Sudah Punya')->orWhereNull('kepemilikan_rek_bri')->count();

        // Data for Pendidikan Donut
        $pendidikanDosen = Dosen::selectRaw('pendidikan, count(*) as total')->groupBy('pendidikan')->pluck('total', 'pendidikan');
        $pendidikanPegawai = PegawaiStruktural::selectRaw('pendidikan, count(*) as total')->groupBy('pendidikan')->pluck('total', 'pendidikan');
        
        $pendidikanData = [];
        foreach (['S3', 'S2', 'S1', 'D3', 'SMA/SMK', 'Lainnya'] as $p) {
            $pendidikanData[$p] = 0;
        }
        foreach ($pendidikanDosen as $key => $val) {
            $k = in_array($key, ['S3', 'S2', 'S1', 'D3']) ? $key : (in_array($key, ['SMA', 'SMK']) ? 'SMA/SMK' : 'Lainnya');
            $pendidikanData[$k] += $val;
        }
        foreach ($pendidikanPegawai as $key => $val) {
            $k = in_array($key, ['S3', 'S2', 'S1', 'D3']) ? $key : (in_array($key, ['SMA', 'SMK']) ? 'SMA/SMK' : 'Lainnya');
            $pendidikanData[$k] += $val;
        }

        // Mock trend S3 data for 6 months
        $trendS3 = [2, 5, 8, 12, 18, Dosen::where('pendidikan', 'S3')->count()];

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_dosen' => Dosen::count(),
                'total_pegawai' => PegawaiStruktural::count(),
                'total_prodi' => RekapProdi::count(),
                'dosen_s3' => Dosen::where('pendidikan', 'S3')->count(),
                'guru_besar' => RekapProdi::sum('jumlah_guru_besar'),
            ],
            'chart_data' => [
                'bri_ownership' => [
                    'sudah' => $dosen_rek_bri + $pegawai_rek_bri,
                    'belum' => $dosen_belum + $pegawai_belum,
                ],
                'pendidikan' => $pendidikanData,
                'trend_s3' => $trendS3
            ],
            'rekap_prodi' => RekapProdi::all(),
            'recent_dosen' => Dosen::latest('updated_at')->take(5)->get(),
            'recent_pegawai' => PegawaiStruktural::latest('updated_at')->take(5)->get(),
        ]);
    }
}
