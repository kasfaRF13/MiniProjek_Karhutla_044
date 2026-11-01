<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome'); 
});

// Rute yang membutuhkan autentikasi login
Route::middleware(['auth'])->group(function () {

    // 1. Hak Akses Pelapor: Membuat dan mengirim laporan baru
    Route::middleware(['role:pelapor'])->group(function () {
        Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    });

    // 2. Hak Akses Relawan Pemantau & Koordinator Relawan: Melihat daftar, detail, dan update status
    Route::middleware(['role:relawan,koordinator'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
        Route::put('/reports/{id}', [ReportController::class, 'update'])->name('reports.update');
    });

    Route::get('/api/area-reports/{areaId}', [ReportController::class, 'getAreaReports'])->middleware('auth');

});