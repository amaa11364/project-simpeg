<?php
namespace App\Http\Controllers;
use App\Models\PegawaiStruktural;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PegawaiStrukturalController extends Controller {
    public function index(Request $request) {
        $allowed = ['nama', 'nomor_induk_kepegawaian', 'jabatan', 'pendidikan', 'kepemilikan_rek_bri'];
        $sort = in_array($request->sort, $allowed) ? $request->sort : 'nama';
        $dir  = $request->dir === 'desc' ? 'desc' : 'asc';

        $query = PegawaiStruktural::query();
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nomor_induk_kepegawaian', 'like', "%$search%")
                  ->orWhere('jabatan', 'like', "%$search%");
            });
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $pegawaiData = $query->orderBy($sort, $dir)->paginate(15)->withQueryString();

        $ids = $pegawaiData->pluck('id')->toArray();
        $dokumen = \App\Models\DokumenCivitas::where('civitas_type', 'pegawai_struktural')
            ->whereIn('civitas_id', $ids)
            ->whereNotNull('link')
            ->where('link', '!=', '')
            ->selectRaw('civitas_id, COUNT(*) as terisi')
            ->groupBy('civitas_id')
            ->pluck('terisi', 'civitas_id');

        $totalJenis = count(\App\Models\DokumenCivitas::$jenisPegawai);
        $pegawaiData->getCollection()->transform(function ($p) use ($dokumen, $totalJenis) {
            $terisi = $dokumen->get($p->id, 0);
            $p->persen_dokumen = $totalJenis > 0 ? round($terisi / $totalJenis * 100) : 0;
            return $p;
        });

        return Inertia::render('PegawaiStruktural/Index', [
            'pegawai' => $pegawaiData,
            'filters' => $request->only(['search', 'sort', 'dir', 'status']),
        ]);
    }

    public function create() {
        return Inertia::render('PegawaiStruktural/Form', ['pegawai' => null]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nomor_induk_kepegawaian' => 'nullable|string|max:50',
            'nama' => 'required|string|max:255',
            'foto_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'foto' => 'nullable|string|max:500',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|string|max:100',
            'pendidikan' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'nik_ktp' => 'nullable|string|max:30',
            'kepemilikan_rek_bri' => 'nullable|string|max:20',
            'status' => 'nullable|in:Aktif,Tidak Aktif',
        ]);
        $validated['status'] = $validated['status'] ?? 'Aktif';
        if ($request->hasFile('foto_file')) {
            $validated['foto'] = '/storage/' . $request->file('foto_file')->store('foto', 'public');
        }
        unset($validated['foto_file']);
        $pegawaiStruktural = PegawaiStruktural::create($validated);
        \App\Models\ActivityLog::record('create', "Tambah pegawai: {$pegawaiStruktural->nama}", 'PegawaiStruktural', $pegawaiStruktural->id);
        return redirect()->route('pegawai-struktural.index')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function edit(PegawaiStruktural $pegawaiStruktural) {
        $jenis = \App\Models\DokumenCivitas::getJenis('pegawai_struktural');
        $existing = \App\Models\DokumenCivitas::where('civitas_type', 'pegawai_struktural')
            ->where('civitas_id', $pegawaiStruktural->id)
            ->get()->keyBy('jenis_dokumen');

        $dokumen = collect($jenis)->map(fn($label, $key) => [
            'key'   => $key,
            'label' => $label,
            'link'  => $existing->get($key)?->link ?? '',
        ])->values();

        $terisi = $dokumen->filter(fn($d) => !empty($d['link']))->count();

        return Inertia::render('PegawaiStruktural/Form', [
            'pegawai' => $pegawaiStruktural,
            'dokumen' => $dokumen,
            'persen'  => count($jenis) > 0 ? round($terisi / count($jenis) * 100) : 0,
        ]);
    }

    public function update(Request $request, PegawaiStruktural $pegawaiStruktural) {
        $validated = $request->validate([
            'nomor_induk_kepegawaian' => 'nullable|string|max:50',
            'nama' => 'required|string|max:255',
            'foto_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'foto' => 'nullable|string|max:500',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|string|max:100',
            'pendidikan' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'nik_ktp' => 'nullable|string|max:30',
            'kepemilikan_rek_bri' => 'nullable|string|max:20',
            'status' => 'nullable|in:Aktif,Tidak Aktif',
        ]);
        $validated['status'] = $validated['status'] ?? 'Aktif';
        if ($request->hasFile('foto_file')) {
            if ($pegawaiStruktural->foto && str_starts_with($pegawaiStruktural->foto, '/storage/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $pegawaiStruktural->foto));
            }
            $validated['foto'] = '/storage/' . $request->file('foto_file')->store('foto', 'public');
        }
        unset($validated['foto_file']);
        $pegawaiStruktural->update($validated);
        \App\Models\ActivityLog::record('update', "Update pegawai: {$pegawaiStruktural->nama}", 'PegawaiStruktural', $pegawaiStruktural->id);
        return redirect()->route('pegawai-struktural.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(PegawaiStruktural $pegawaiStruktural) {
        \App\Models\ActivityLog::record('delete', "Hapus pegawai: {$pegawaiStruktural->nama}", 'PegawaiStruktural', $pegawaiStruktural->id);
        $pegawaiStruktural->delete();
        return redirect()->route('pegawai-struktural.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}
