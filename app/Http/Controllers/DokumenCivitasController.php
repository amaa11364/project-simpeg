<?php
namespace App\Http\Controllers;

use App\Models\DokumenCivitas;
use App\Models\Dosen;
use App\Models\PegawaiStruktural;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DokumenCivitasController extends Controller
{
    private const ALLOWED_TYPES = ['dosen', 'pegawai_struktural'];

    private function getCivitas(string $type, int $id)
    {
        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            abort(404);
        }
        return $type === 'dosen'
            ? Dosen::findOrFail($id)
            : PegawaiStruktural::findOrFail($id);
    }

    public function show(string $civitasType, int $civitasId)
    {
        $civitas = $this->getCivitas($civitasType, $civitasId);
        $jenis   = DokumenCivitas::getJenis($civitasType);

        $existing = DokumenCivitas::where('civitas_type', $civitasType)
            ->where('civitas_id', $civitasId)
            ->get()
            ->keyBy('jenis_dokumen');

        // Susun menjadi array lengkap per jenis dokumen
        $dokumen = collect($jenis)->map(function ($label, $key) use ($existing) {
            $row = $existing->get($key);
            return [
                'key'        => $key,
                'label'      => $label,
                'link'       => $row?->link ?? '',
                'keterangan' => $row?->keterangan ?? '',
                'id'         => $row?->id,
            ];
        })->values();

        $terisi = $dokumen->filter(fn($d) => !empty($d['link']))->count();
        $total  = $dokumen->count();

        return Inertia::render('DokumenCivitas/Show', [
            'civitas'      => $civitas,
            'civitas_type' => $civitasType,
            'dokumen'      => $dokumen,
            'persen'       => $total > 0 ? round($terisi / $total * 100) : 0,
            'terisi'       => $terisi,
            'total'        => $total,
        ]);
    }

    public function upsert(Request $request, string $civitasType, int $civitasId)
    {
        $this->getCivitas($civitasType, $civitasId); // validasi exists

        $validated = $request->validate([
            'jenis_dokumen' => 'required|string',
            'link'          => 'nullable|string|max:2000',
            'keterangan'    => 'nullable|string|max:500',
        ]);

        $jenis = DokumenCivitas::getJenis($civitasType);
        if (!array_key_exists($validated['jenis_dokumen'], $jenis)) {
            abort(422, 'Jenis dokumen tidak valid.');
        }

        DokumenCivitas::updateOrCreate(
            [
                'civitas_type'  => $civitasType,
                'civitas_id'    => $civitasId,
                'jenis_dokumen' => $validated['jenis_dokumen'],
            ],
            [
                'link'       => $validated['link'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
            ]
        );

        ActivityLog::record(
            'update',
            "Update dokumen {$jenis[$validated['jenis_dokumen']]} untuk " .
            ($civitasType === 'dosen' ? 'dosen' : 'pegawai') . " ID {$civitasId}",
            'DokumenCivitas',
            $civitasId
        );

        return back()->with('success', 'Dokumen berhasil disimpan.');
    }
}
