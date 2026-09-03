<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiStrukturalController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\RekapProdiController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DokumenCivitasController;
use App\Http\Controllers\ExportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/pengelola', function () {
    return redirect()->route('login');
});

Route::prefix('pengelola')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/ganti-password', fn() => Inertia::render('GantiPassword'))->name('ganti-password');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('pegawai-struktural', PegawaiStrukturalController::class)->except(['show']);
    Route::resource('dosen', DosenController::class)->except(['show']);
    Route::resource('rekap-prodi', RekapProdiController::class)->except(['show']);
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');

    Route::get('/dokumen/{civitasType}/{civitasId}', [DokumenCivitasController::class, 'show'])->name('dokumen.show');
    Route::post('/dokumen/{civitasType}/{civitasId}', [DokumenCivitasController::class, 'upsert'])->name('dokumen.upsert');

    Route::middleware('throttle:20,1')->group(function () {
        Route::get('/export/dosen/csv',   [ExportController::class, 'dosenCsv'])->name('export.dosen.csv');
        Route::get('/export/dosen/pdf',   [ExportController::class, 'dosenPdf'])->name('export.dosen.pdf');
        Route::get('/export/pegawai/csv', [ExportController::class, 'pegawaiCsv'])->name('export.pegawai.csv');
        Route::get('/export/pegawai/pdf', [ExportController::class, 'pegawaiPdf'])->name('export.pegawai.pdf');
        Route::get('/export/rekap/csv',   [ExportController::class, 'rekapCsv'])->name('export.rekap.csv');
        Route::get('/export/rekap/pdf',   [ExportController::class, 'rekapPdf'])->name('export.rekap.pdf');
    });
});

require __DIR__.'/auth.php';
