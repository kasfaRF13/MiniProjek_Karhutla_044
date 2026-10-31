<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome'); 
});

// route untuk melihat daftar laporan 
Route::get('/reports', [ReportController::class, 'index']);

//rout untuk menampilkan form laporan
Route::get('/reports/create', [ReportController::class, 'create']);

//route unruk memproses penyimpanan data laporan baru 
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');