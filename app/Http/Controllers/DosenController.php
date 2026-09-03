<?php
namespace App\Http\Controllers;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DosenController extends Controller {
    public function index(Request $request) {
        $allowed = ['nama', 'nidn_nik', 'homebase_prodi', 'pendidikan', 'kepemilikan_rek_bri'];
        $sort = in_array($request->sort, $allowed) ? $request->sort : 'nama';
        $dir  = $request->dir === 'desc' ? 'desc' : 'asc';

        $query = Dosen::query();
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('nidn_nik', 'like', "%$search%")
                  ->orWhere('homebase_prodi', 'like', "%$search%");
            });
        }
        if ($request->prodi) {
            $query->where('homebase_prodi', $request->prodi);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $dosenData = $query->orderBy($sort, $dir)->paginate(15)->withQueryString();

        // Hitung kelengkapan dokumen untuk dosen di halaman ini
        $ids = $dosenData->pluck('id')->toArray();
        $dokumen = \App\Models\DokumenCivitas::where('civitas_type', 'dosen')
            ->whereIn('civitas_id', $ids)
            ->whereNotNull('link')
            ->where('link', '!=', '')
            ->selectRaw('civitas_id, COUNT(*) as terisi')
            ->groupBy('civitas_id')
            ->pluck('terisi', 'civitas_id');

        $totalJenis = count(\App\Models\DokumenCivitas::$jenisDosen);
        $dosenData->getCollection()->transform(function ($d) use ($dokumen, $totalJenis) {
            $terisi = $dokumen->get($d->id, 0);
            $d->persen_dokumen = $totalJenis > 0 ? round($terisi / $totalJenis * 100) : 0;
            return $d;
        });

        return Inertia::render('Dosen/Index', [
            'dosen' => $dosenData,
            'filters' => $request->only(['search', 'prodi', 'sort', 'dir', 'status']),
            'prodi_list' => Dosen::distinct()->orderBy('homebase_prodi')->pluck('homebase_prodi')->filter()->values(),
        ]);
    }

    public function create() {
        return Inertia::render('Dosen/Form', [
            'dosen' => null,
            'prodi_list' => Dosen::distinct()->orderBy('homebase_prodi')->pluck('homebase_prodi')->filter()->values(),
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nidn_nik' => 'nullable|string|max:50',
            'nama' => 'required|string|max:255',
            'foto_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'foto' => 'nullable|string|max:500',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|string|max:100',
            'pendidikan' => 'nullable|string|max:50',
            'homebase_prodi' => 'nullable|string|max:255',
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
        $dosen = Dosen::create($validated);
        \App\Models\ActivityLog::record('create', "Tambah dosen: {$dosen->nama}", 'Dosen', $dosen->id);
        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil ditambahkan.');
    }

    public function edit(Dosen $dosen) {
        $jenis = \App\Models\DokumenCivitas::getJenis('dosen');
        $existing = \App\Models\DokumenCivitas::where('civitas_type', 'dosen')
            ->where('civitas_id', $dosen->id)
            ->get()->keyBy('jenis_dokumen');

        $dokumen = collect($jenis)->map(fn($label, $key) => [
            'key'   => $key,
            'label' => $label,
            'link'  => $existing->get($key)?->link ?? '',
        ])->values();

        $terisi = $dokumen->filter(fn($d) => !empty($d['link']))->count();

        return Inertia::render('Dosen/Form', [
            'dosen'     => $dosen,
            'prodi_list'=> Dosen::distinct()->orderBy('homebase_prodi')->pluck('homebase_prodi')->filter()->values(),
            'dokumen'   => $dokumen,
            'persen'    => count($jenis) > 0 ? round($terisi / count($jenis) * 100) : 0,
        ]);
    }

    public function update(Request $request, Dosen $dosen) {
        $validated = $request->validate([
            'nidn_nik' => 'nullable|string|max:50',
            'nama' => 'required|string|max:255',
            'foto_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'foto' => 'nullable|string|max:500',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|string|max:100',
            'pendidikan' => 'nullable|string|max:50',
            'homebase_prodi' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'nik_ktp' => 'nullable|string|max:30',
            'kepemilikan_rek_bri' => 'nullable|string|max:20',
            'status' => 'nullable|in:Aktif,Tidak Aktif',
        ]);
        $validated['status'] = $validated['status'] ?? 'Aktif';
        if ($request->hasFile('foto_file')) {
            // Delete old file if it was a local upload
            if ($dosen->foto && str_starts_with($dosen->foto, '/storage/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $dosen->foto));
            }
            $validated['foto'] = '/storage/' . $request->file('foto_file')->store('foto', 'public');
        }
        unset($validated['foto_file']);
        $dosen->update($validated);
        \App\Models\ActivityLog::record('update', "Update dosen: {$dosen->nama}", 'Dosen', $dosen->id);
        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen) {
        \App\Models\ActivityLog::record('delete', "Hapus dosen: {$dosen->nama}", 'Dosen', $dosen->id);
        $dosen->delete();
        return redirect()->route('dosen.index')->with('success', 'Data dosen berhasil dihapus.');
    }
}
