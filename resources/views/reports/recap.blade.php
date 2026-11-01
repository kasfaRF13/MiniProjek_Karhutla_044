<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Laporan Karhutla</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Rekapitulasi & Statistik Laporan Karhutla</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">← Kembali ke Daftar Laporan</a>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Logout</button>
                </form>
            </div>
        </div>

        <!-- 3 Indikator Ringkasan Utama sesuai Dokumen Proyek -->
        <div class="row text-white mb-4">
            <div class="col-md-4">
                <div class="card bg-primary shadow-sm mb-3">
                    <div class="card-body text-center">
                        <h5>Total Seluruh Laporan</h5>
                        <h3>{{ $totalReports ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark shadow-sm mb-3">
                    <div class="card-body text-center">
                        <h5>Laporan Menunggu</h5>
                        <h3>{{ $pendingReports ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success shadow-sm mb-3">
                    <div class="card-body text-center">
                        <h5>Laporan Selesai</h5>
                        <h3>{{ $finishedReports ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Filter Berdasarkan Status -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.recap') }}" class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="status" class="col-form-label fw-bold">Filter Status:</label>
                    </div>
                    <div class="col-md-4">
                        <select name="status" id="status" class="form-select">
                            <option value="">-- Semua Status --</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="ditinjau" {{ request('status') == 'ditinjau' ? 'selected' : '' }}>Ditinjau</option>
                            <option value="diteruskan" {{ request('status') == 'diteruskan' ? 'selected' : '' }}>Diteruskan</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('reports.recap') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Rekapitulasi Data -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Wilayah (Area)</th>
                                <th>Pelapor</th>
                                <th>Tingkat Kondisi</th>
                                <th>Status</th>
                                <th>Catatan / Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $index => $report)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $report->created_at->format('d-m-Y H:i') }}</td>
                                    <td>{{ $report->area->name ?? '-' }}</td>
                                    <td>{{ $report->user->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">{{ ucfirst($report->condition_level) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($report->status == 'menunggu') bg-secondary 
                                            @elseif($report->status == 'ditinjau') bg-info text-dark 
                                            @elseif($report->status == 'diteruskan') bg-warning text-dark 
                                            @elseif($report->status == 'selesai') bg-success 
                                            @else bg-danger @endif">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $report->volunteer_notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Tidak ada data laporan yang sesuai dengan filter.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>