<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Saya - Karhutla</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Laporan Kebakaran Milik Saya</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('reports.create') }}" class="btn btn-danger">+ Buat Laporan Baru</a>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Logout</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Wilayah (Area)</th>
                                <th>Detail Lokasi</th>
                                <th>Kondisi</th>
                                <th>Waktu Kejadian</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $index => $report)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $report->area->name ?? '-' }}</td>
                                    <td>{{ $report->location_detail }}</td>
                                    <td><span class="badge bg-warning text-dark">{{ ucfirst($report->condition_level) }}</span></td>
                                    <td>{{ $report->incident_time }}</td>
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
                                    <td>
                                        @if($report->status == 'menunggu')
                                            <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        @else
                                            <span class="text-muted small">Terkunci</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Kamu belum pernah membuat laporan.</td>
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