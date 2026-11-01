<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome'); 
});

// Halaman Login (Bisa diakses langsung)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// RUTE YANG MEMBUTUHKAN AUTENTIKASI (Sudah Login)
Route::middleware(['auth'])->group(function () {

    // Proses Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 1. Hak Akses Pelapor (Masyarakat)
    Route::middleware(['role:pelapor'])->group(function () {
        Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
        
        // Memantau & mengedit laporan miliknya sendiri
        Route::get('/my-reports', [ReportController::class, 'myReports'])->name('reports.my-reports');
        Route::get('/reports/{id}/edit', [ReportController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{id}/update-pelapor', [ReportController::class, 'updatePelapor'])->name('reports.update-pelapor');
    });

    // 2. Hak Akses Relawan Pemantau & Koordinator Relawan
    Route::middleware(['role:relawan,koordinator'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        
        // Rute statis rekapitulasi ditaruh di atas rute dinamis {id}
        Route::get('/reports/recap', [ReportController::class, 'recap'])->name('reports.recap');
        
        Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
        Route::put('/reports/{id}', [ReportController::class, 'update'])->name('reports.update');
    });

    // Rute API Fetch Area (Bisa diakses semua role yang sudah login)
    Route::get('/api/area-reports/{areaId}', [ReportController::class, 'getAreaReports']);

});