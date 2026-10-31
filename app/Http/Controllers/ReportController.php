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
        $reports = Report::with('area', 'user')->get();//mengambil data laporan beserta relasi area dan user

        //return json sementara untuk memastikan datanya masuk
        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
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

        //simpan data ke database
        Report::create([
            'user_id'=> Auth::id(),
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
