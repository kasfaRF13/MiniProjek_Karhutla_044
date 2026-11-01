<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Area;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Menampilkan data laporan untuk Relawan/Koordinator
     */
    public function index()
    {
        $reports = Report::with('area', 'user')->latest()->get();
        return view('reports.index', compact('reports'));
    }

    /**
     * Menampilkan form buat laporan 
     */
    public function create()
    {
        $areas = Area::all();
        return view('reports.create', compact('areas'));
    }

    /**
     * Menyimpan laporan baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'location_detail' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'incident_time' => 'required|date',
            'condition_level' => 'required|in:sedang,parah,kritis',
            'description' => 'required|string', 
            'evidence_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('evidence_file')) {
            $filePath = $request->file('evidence_file')->store('evidences', 'public');
        }

        Report::create([
            'user_id' => Auth::id(),
            'area_id' => $request->area_id,
            'location_detail' => $request->location_detail,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'incident_time' => $request->incident_time,
            'condition_level' => $request->condition_level,
            'description' => $request->description,
            'evidence_file' => $filePath,
            'status' => 'menunggu',
        ]);

        return redirect()->route('reports.create')->with('success', 'Laporan berhasil dikirim dan menunggu peninjauan relawan.');
    }

    /**
     * Menampilkan daftar laporan milik Pelapor yang sedang login
     */
    public function myReports()
    {
        $reports = Report::where('user_id', Auth::id())
                    ->with('area')
                    ->latest()
                    ->get();

        return view('reports.my-reports', compact('reports'));
    }

    /**
     * Menampilkan detail lengkap suatu laporan
     */
    public function show(string $id)
    {
        $report = Report::with('area', 'user')->findOrFail($id);
        return view('reports.show', compact('report'));
    }

    /**
     * Form edit laporan khusus pelapor (hanya jika status 'menunggu')
     */
    public function edit(string $id)
    {
        $report = Report::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        if ($report->status !== 'menunggu') {
            return redirect()->route('reports.my-reports')->with('error', 'Laporan sudah ditinjau relawan dan tidak dapat diubah lagi.');
        }

        $areas = Area::all();
        return view('reports.edit', compact('report', 'areas'));
    }

    /**
     * Memproses update data laporan oleh Pelapor
     */
    public function updatePelapor(Request $request, string $id)
    {
        $report = Report::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        if ($report->status !== 'menunggu') {
            return redirect()->route('reports.my-reports')->with('error', 'Laporan tidak dapat diubah.');
        }

        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'location_detail' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'incident_time' => 'required|date',
            'condition_level' => 'required|in:sedang,parah,kritis',
            'description' => 'required|string',
            'evidence_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $filePath = $report->evidence_file;
        if ($request->hasFile('evidence_file')) {
            $filePath = $request->file('evidence_file')->store('evidences', 'public');
        }

        $report->update([
            'area_id' => $request->area_id,
            'location_detail' => $request->location_detail,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'incident_time' => $request->incident_time,
            'condition_level' => $request->condition_level,
            'description' => $request->description,
            'evidence_file' => $filePath,
        ]);

        return redirect()->route('reports.my-reports')->with('success', 'Laporan berhasil diperbarui.');
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

    // Business Rule: Status 'diteruskan' hanya boleh diatur oleh Koordinator Relawan
    if ($request->status === 'diteruskan' && !auth()->user()->hasRole('koordinator')) { // Sesuaikan pengecekan role sesuai sistem rolenya
        return back()->with('error', 'Akses ditolak! Hanya Koordinator Relawan yang dapat meneruskan laporan ke pihak terkait.');
    }

    $report->update([
        'status' => $request->status,
        'volunteer_notes' => $request->volunteer_notes,
    ]);

    return redirect()->route('reports.show', $id)->with('success', 'Status laporan berhasil diperbarui!');
}

    public function destroy(string $id)
    {
        //
    }

    /**
     * Endpoint API Asynchronous Fetch Area Reports
     */
    public function getAreaReports($areaId)
    {
        $totalActive = Report::where('area_id', $areaId)
            ->whereNotIn('status', ['selesai', 'ditolak'])
            ->count();

        return response()->json([
            'success' => true,
            'total_active' => $totalActive
        ]);
    }

    /**
     * Halaman Rekapitulasi Data
     */
    public function recap(Request $request)
    {
        $query = Report::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->with(['user', 'area'])->latest()->get();

        $totalReports = Report::count();
        $pendingReports = Report::where('status', 'menunggu')->count();
        $finishedReports = Report::where('status', 'selesai')->count();

        return view('reports.recap', compact('reports', 'totalReports', 'pendingReports', 'finishedReports'));
    }
}