<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Area;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * menampilkan data laporan
     */
    public function index()
    {
        // Mengambil data laporan beserta relasi area dan user, diurutkan dari yang terbaru
        $reports = Report::with('area', 'user')->latest()->get();

        // Mengarahkan ke file view index.blade.php
        return view('reports.index', compact('reports'));
    }

    /**
     * menampilkan form buat laporan 
     */
    public function create()
    {
        $areas = Area::all();
        return view('reports.create', compact('areas'));
    }

    /**
     * menyimpan laporan ke database
     */
    /**
     * menyimpan laporan ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'location_detail' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'condition_level' => 'required|string',
            'incident_time' => 'required|date',
            // Validasi file sesuai ketentuan dokumen (maksimal 2MB, format gambar/pdf)[cite: 6]
            'evidence_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('evidence_file')) {
            // Menyimpan file ke folder storage/app/public/evidences
            $filePath = $request->file('evidence_file')->store('evidences', 'public');
        }

        Report::create([
            'user_id' => auth()->id(),
            'area_id' => $request->area_id,
            'location_detail' => $request->location_detail,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'condition_level' => $request->condition_level,
            'incident_time' => $request->incident_time,
            'evidence_file' => $filePath,
            'status' => 'menunggu', // Status awal sesuai dokumen[cite: 3]
        ]);

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dibuat!');
    }
    /**
     * menampilkan detail lengkap suatu laporan
     */
    public function show(string $id)
    {
        $report = Report::with('area', 'user')->findOrFail($id);
        return view('reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Mengupdate status laporan oleh relawan/petugas
     */
    public function update(Request $request, string $id)
    {
        $report = Report::findOrFail($id);

        $request->validate([
            'status' => 'required|in:menunggu,ditinjau,diteruskan,selesai,ditolak',
            'volunteer_notes' => 'nullable|string|max:500',
        ]);

        $report->update([
            'status' => $request->status,
            'volunteer_notes' => $request->volunteer_notes,
        ]);

        return redirect()->route('reports.show', $id)->with('success', 'Status laporan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getAreaReports($areaId)
{
    // Mengambil laporan aktif (yang belum selesai/ditolak) pada area tersebut
    $activeReports = \App\Models\Report::where('area_id', $areaId)
        ->whereNotIn('status', ['selesai', 'ditolak'])
        ->get();

    return response()->json([
        'success' => true,
        'total_active' => $activeReports->count(),
        'reports' => $activeReports
    ]);
}
}
