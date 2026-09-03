<?php
namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\PegawaiStruktural;
use App\Models\RekapProdi;
use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExportController extends Controller
{
    // ───────────────────────── DOSEN ─────────────────────────

    public function dosenCsv(Request $request)
    {
        $query = Dosen::query();
        if ($request->search) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nama','like',"%$s%")->orWhere('nidn_nik','like',"%$s%")->orWhere('homebase_prodi','like',"%$s%"));
        }
        if ($request->prodi) $query->where('homebase_prodi', $request->prodi);

        $rows = $query->orderBy('nama')->get();

        ActivityLog::record('export', 'Ekspor data dosen ke CSV (' . $rows->count() . ' baris)');

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data-dosen-' . date('Ymd-His') . '.csv"',
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            // BOM untuk Excel agar UTF-8 terbaca
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No','NIDN/NIK','Nama','Tempat Lahir','Tanggal Lahir','Pendidikan','Homebase Prodi','Alamat','NIK KTP','Kepemilikan Rek. BRI']);
            foreach ($rows as $i => $d) {
                fputcsv($file, [
                    $i + 1,
                    $d->nidn_nik,
                    $d->nama,
                    $d->tempat_lahir,
                    $d->tanggal_lahir,
                    $d->pendidikan,
                    $d->homebase_prodi,
                    $d->alamat,
                    $d->nik_ktp,
                    $d->kepemilikan_rek_bri,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function dosenPdf(Request $request)
    {
        $query = Dosen::query();
        if ($request->search) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nama','like',"%$s%")->orWhere('nidn_nik','like',"%$s%")->orWhere('homebase_prodi','like',"%$s%"));
        }
        if ($request->prodi) $query->where('homebase_prodi', $request->prodi);

        $rows = $query->orderBy('nama')->get();

        ActivityLog::record('export', 'Ekspor data dosen ke PDF (' . $rows->count() . ' baris)');

        $pdf = Pdf::loadView('exports.dosen', compact('rows'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('data-dosen-' . date('Ymd-His') . '.pdf');
    }

    // ───────────────────────── PEGAWAI STRUKTURAL ─────────────────────────

    public function pegawaiCsv(Request $request)
    {
        $query = PegawaiStruktural::query();
        if ($request->search) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nama','like',"%$s%")->orWhere('nomor_induk_kepegawaian','like',"%$s%")->orWhere('jabatan','like',"%$s%"));
        }

        $rows = $query->orderBy('nama')->get();

        ActivityLog::record('export', 'Ekspor data pegawai struktural ke CSV (' . $rows->count() . ' baris)');

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="pegawai-struktural-' . date('Ymd-His') . '.csv"',
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No','NIK','Nama','Tempat Lahir','Tanggal Lahir','Pendidikan','Jabatan','Alamat','NIK KTP','Kepemilikan Rek. BRI']);
            foreach ($rows as $i => $p) {
                fputcsv($file, [
                    $i + 1,
                    $p->nomor_induk_kepegawaian,
                    $p->nama,
                    $p->tempat_lahir,
                    $p->tanggal_lahir,
                    $p->pendidikan,
                    $p->jabatan,
                    $p->alamat,
                    $p->nik_ktp,
                    $p->kepemilikan_rek_bri,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function pegawaiPdf(Request $request)
    {
        $query = PegawaiStruktural::query();
        if ($request->search) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nama','like',"%$s%")->orWhere('nomor_induk_kepegawaian','like',"%$s%")->orWhere('jabatan','like',"%$s%"));
        }

        $rows = $query->orderBy('nama')->get();

        ActivityLog::record('export', 'Ekspor data pegawai struktural ke PDF (' . $rows->count() . ' baris)');

        $pdf = Pdf::loadView('exports.pegawai', compact('rows'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('pegawai-struktural-' . date('Ymd-His') . '.pdf');
    }

    // ───────────────────────── REKAP PRODI ─────────────────────────

    public function rekapCsv()
    {
        $rows = RekapProdi::orderBy('program_studi')->get();

        ActivityLog::record('export', 'Ekspor rekap prodi ke CSV');

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rekap-prodi-' . date('Ymd-His') . '.csv"',
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No','Program Studi','Jumlah Dosen','Guru Besar','Doktor','Magister']);
            foreach ($rows as $i => $r) {
                fputcsv($file, [$i+1, $r->program_studi, $r->jumlah_dosen, $r->jumlah_guru_besar, $r->jumlah_doktor, $r->jumlah_magister]);
            }
            // Baris total
            fputcsv($file, ['', 'TOTAL', $rows->sum('jumlah_dosen'), $rows->sum('jumlah_guru_besar'), $rows->sum('jumlah_doktor'), $rows->sum('jumlah_magister')]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function rekapPdf()
    {
        $rows = RekapProdi::orderBy('program_studi')->get();

        ActivityLog::record('export', 'Ekspor rekap prodi ke PDF');

        $pdf = Pdf::loadView('exports.rekap', compact('rows'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('rekap-prodi-' . date('Ymd-His') . '.pdf');
    }
}
