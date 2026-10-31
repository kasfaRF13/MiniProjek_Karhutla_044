<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pelaporan Kebakaran Hutan - Karhutla</title>
    <!-- CDN Bootstrap 5 untuk styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-danger text-white">
                        <h4 class="mb-0">Form Pelaporan Titik Api / Karhutla</h4>
                    </div>
                    <div class="card-body">
                        
                        <!-- Notifikasi jika sukses -->
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Form Input Laporan -->
                        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Pilihan Wilayah / Area -->
                            <div class="mb-3">
                                <label for="area_id" class="form-label">Wilayah / Area</label>
                                <select name="area_id" id="area_id" class="form-select" required>
                                    <option value="">-- Pilih Wilayah --</option>
                                    @foreach($areas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }} ({{ $area->region_code }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Detail Lokasi -->
                            <div class="mb-3">
                                <label for="location_detail" class="form-label">Detail Lokasi / Patokan</label>
                                <input type="text" name="location_detail" id="location_detail" class="form-control" placeholder="Contoh: Dekat bukit utara, blok C" required>
                            </div>

                            <!-- Koordinat Latitude & Longitude -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="latitude" class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" placeholder="-6.9175" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="longitude" class="form-label">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" placeholder="107.6191" required>
                                </div>
                            </div>

                            <!-- Waktu Kejadian -->
                            <div class="mb-3">
                                <label for="incident_time" class="form-label">Waktu Kejadian</label>
                                <input type="datetime-local" name="incident_time" id="incident_time" class="form-control" required>
                            </div>

                            <!-- Tingkat Kondisi / Keparahan -->
                            <div class="mb-3">
                                <label for="condition_level" class="form-label">Tingkat Keparahan / Kondisi</label>
                                <select name="condition_level" id="condition_level" class="form-select" required>
                                    <option value="sedang">Sedang</option>
                                    <option value="parah">Parah</option>
                                    <option value="kritis">Kritis</option>
                                </select>
                            </div>

                            <!-- Deskripsi Laporan -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi / Keterangan Kejadian</label>
                                <textarea name="description" id="description" rows="4" class="form-control" placeholder="Jelaskan kondisi kebakaran secara singkat..." required></textarea>
                            </div>

                            <!-- Upload Bukti Foto -->
                            <div class="mb-3">
                                <label for="evidence_file" class="form-label">Bukti Foto (Opsional)</label>
                                <input type="file" name="evidence_file" id="evidence_file" class="form-control" accept="image/png,image/jpeg,image/jpg">
                            </div>

                            <!-- Tombol Kirim -->
                            <button type="submit" class="btn btn-danger w-100">Kirim Laporan Kebakaran</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>