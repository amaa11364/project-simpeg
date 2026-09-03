<?php
namespace App\Http\Controllers;
use App\Models\RekapProdi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RekapProdiController extends Controller {
    public function index() {
        // Hitung live dari tabel dosen
        $liveStats = \App\Models\Dosen::select('homebase_prodi')
            ->selectRaw('COUNT(*) as jumlah_dosen')
            ->selectRaw('SUM(pendidikan = "S3") as jumlah_doktor')
            ->selectRaw('SUM(pendidikan = "S2") as jumlah_magister')
            ->selectRaw('SUM(nama LIKE "Prof.%" OR nama LIKE "% Prof. %" OR nama LIKE "%Prof. %") as jumlah_guru_besar')
            ->whereNotNull('homebase_prodi')
            ->where('homebase_prodi', '!=', '')
            ->groupBy('homebase_prodi')
            ->orderBy('homebase_prodi')
            ->get()
            ->keyBy('homebase_prodi');

        // Gabungkan dengan rekap_prodi (untuk edit manual jika perlu)
        $rekap = RekapProdi::orderBy('program_studi')->get()->map(function($r) use ($liveStats) {
            $live = $liveStats->get($r->program_studi);
            return [
                'id' => $r->id,
                'program_studi' => $r->program_studi,
                'jumlah_dosen' => $live ? (int)$live->jumlah_dosen : $r->jumlah_dosen,
                'jumlah_guru_besar' => $live ? (int)$live->jumlah_guru_besar : $r->jumlah_guru_besar,
                'jumlah_doktor' => $live ? (int)$live->jumlah_doktor : $r->jumlah_doktor,
                'jumlah_magister' => $live ? (int)$live->jumlah_magister : $r->jumlah_magister,
                'is_live' => (bool)$live,
            ];
        });

        return Inertia::render('RekapProdi/Index', [
            'rekap' => $rekap,
        ]);
    }

    public function create() {
        return Inertia::render('RekapProdi/Form', ['rekap' => null]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'program_studi' => 'required|string|max:255',
            'jumlah_dosen' => 'required|integer|min:0',
            'jumlah_guru_besar' => 'required|integer|min:0',
            'jumlah_doktor' => 'required|integer|min:0',
            'jumlah_magister' => 'required|integer|min:0',
        ]);
        $rekapProdi = RekapProdi::create($validated);
        \App\Models\ActivityLog::record('create', "Tambah rekap prodi: {$rekapProdi->program_studi}", 'RekapProdi', $rekapProdi->id);
        return redirect()->route('rekap-prodi.index')->with('success', 'Data rekap prodi berhasil ditambahkan.');
    }

    public function edit(RekapProdi $rekapProdi) {
        return Inertia::render('RekapProdi/Form', ['rekap' => $rekapProdi]);
    }

    public function update(Request $request, RekapProdi $rekapProdi) {
        $validated = $request->validate([
            'program_studi' => 'required|string|max:255',
            'jumlah_dosen' => 'required|integer|min:0',
            'jumlah_guru_besar' => 'required|integer|min:0',
            'jumlah_doktor' => 'required|integer|min:0',
            'jumlah_magister' => 'required|integer|min:0',
        ]);
        $rekapProdi->update($validated);
        \App\Models\ActivityLog::record('update', "Update rekap prodi: {$rekapProdi->program_studi}", 'RekapProdi', $rekapProdi->id);
        return redirect()->route('rekap-prodi.index')->with('success', 'Data rekap prodi berhasil diperbarui.');
    }

    public function destroy(RekapProdi $rekapProdi) {
        \App\Models\ActivityLog::record('delete', "Hapus rekap prodi: {$rekapProdi->program_studi}", 'RekapProdi', $rekapProdi->id);
        $rekapProdi->delete();
        return redirect()->route('rekap-prodi.index')->with('success', 'Data rekap prodi berhasil dihapus.');
    }
}
