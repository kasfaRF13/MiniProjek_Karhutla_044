<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome'); 
});

// route untuk melihat daftar laporan 
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// route untuk menampilkan form laporan (tambahkan .name di sini)
Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');

// route untuk memproses penyimpanan data laporan baru 
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');