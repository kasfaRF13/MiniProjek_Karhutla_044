<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - {{ $report->location_detail }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container my-5">
        <div class="mb-3">
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">&larr; Kembali ke Daftar Laporan</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <!-- Informasi Detail Laporan -->
            <div class="col-md-7">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Detail Informasi Kebakaran Hutan</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 35%;">Pelapor</th>
                                <td>: {{ $report->user->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Wilayah (Area)</th>
                                <td>: {{ $report->area->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Detail Lokasi</th>
                                <td>: {{ $report->location_detail }}</td>
                            </tr>
                            <tr>
                                <th>Titik Koordinat</th>
                                <td>: Lat {{ $report->latitude }}, Long {{ $report->longitude }}</td>
                            </tr>
                            <tr>
                                <th>Waktu Kejadian</th>
                                <td>: {{ $report->incident_time }}</td>
                            </tr>
                            <tr>
                                <th>Tingkat Kondisi</th>
                                <td>: <span class="badge bg-warning text-dark">{{ ucfirst($report->condition_level) }}</span></td>
                            </tr>
                            <tr>
                                <th>Status Saat Ini</th>
                                <td>: 
                                    <span class="badge 
                                        @if($report->status == 'menunggu') bg-secondary 
                                        @elseif($report->status == 'diproses') bg-warning text-dark 
                                        @elseif($report->status == 'selesai') bg-success 
                                        @else bg-danger @endif">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>: {{ $report->description }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Panel Aksi / Ubah Status untuk Relawan -->
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Tindakan Relawan / Petugas</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('reports.update', $report->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="status" class="form-label">Ubah Status Laporan</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="menunggu" {{ $report->status == 'menunggu' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                    <option value="diproses" {{ $report->status == 'diproses' ? 'selected' : '' }}>Diproses / Ditangani Tim</option>
                                    <option value="selesai" {{ $report->status == 'selesai' ? 'selected' : '' }}>Selesai / Padam</option>
                                    <option value="ditolak" {{ $report->status == 'ditolak' ? 'selected' : '' }}>Ditolak (Laporan Palsu)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="volunteer_notes" class="form-label">Catatan Lapangan (Opsional)</label>
                                <textarea name="volunteer_notes" id="volunteer_notes" rows="3" class="form-control" placeholder="Tulis catatan penanganan...">{{ $report->volunteer_notes }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Perbarui Status Laporan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>