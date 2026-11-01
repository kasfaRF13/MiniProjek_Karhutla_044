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
        // Validasi input dari form pelapor
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'location_detail' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'incident_time' => 'required|date',
            'condition_level' => 'required|string',
            'description' => 'required|string',
            'evidence_file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // opsional upload foto bukti
        ]);

        $filePath = null;
        // Jika ada file bukti yang di-upload
        if ($request->hasFile('evidence_file')) {
            $filePath = $request->file('evidence_file')->store('evidence_reports', 'public');
        }

        // Simpan data ke database
        Report::create([
            // Gunakan Auth::id() jika sudah login, atau fallback ke ID 1 untuk testing sementara
            'user_id' => Auth::id() ?? 1, 
            'area_id' => $request->area_id,
            'location_detail' => $request->location_detail,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'incident_time' => $request->incident_time,
            'condition_level' => $request->condition_level,
            'description' => $request->description,
            'evidence_file' => $filePath,
            'status' => 'menunggu', // Status awal laporan
        ]);

       
        return redirect()->back()->with('success', 'Laporan kebakaran berhasil dikirim!');
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
            'status' => 'required|in:menunggu,diproses,selesai,ditolak',
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
}
