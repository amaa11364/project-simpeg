<?php
namespace App\Http\Controllers;
use App\Models\Dosen;
use App\Models\PegawaiStruktural;
use App\Models\RekapProdi;
use Inertia\Inertia;

class DashboardController extends Controller {
    public function index() {
        return Inertia::render('Dashboard', [
            'stats' => [
                'total_dosen' => Dosen::count(),
                'total_pegawai' => PegawaiStruktural::count(),
                'total_prodi' => RekapProdi::count(),
                'dosen_s3' => Dosen::where('pendidikan', 'S3')->count(),
                'dosen_rek_bri' => Dosen::where('kepemilikan_rek_bri', 'Sudah Punya')->count(),
                'pegawai_rek_bri' => PegawaiStruktural::where('kepemilikan_rek_bri', 'Sudah Punya')->count(),
            ],
            'rekap_prodi' => RekapProdi::all(),
            'recent_dosen' => Dosen::latest()->take(5)->get(),
            'recent_pegawai' => PegawaiStruktural::latest()->take(5)->get(),
        ]);
    }
}
