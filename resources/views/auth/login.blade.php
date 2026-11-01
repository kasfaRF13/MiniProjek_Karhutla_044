<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Karhutla</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center py-5" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Login Karhutla</h3>

                        <!-- Notifikasi Error jika login gagal -->
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Notifikasi Sukses (misal habis logout) -->
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">Masuk</button>
                        </form>

                        <hr class="my-4">
                        <div class="text-center text-muted small">
                            <p class="mb-1">Gunakan akun demo seeder:</p>
                            <span class="d-block">Pelapor: <code>pelapor@karhutla.test</code></span>
                            <span class="d-block">Relawan: <code>relawan@karhutla.test</code></span>
                            <span class="d-block">Koordinator: <code>koordinator@karhutla.test</code></span>
                            <span class="d-block mt-1">Password (semua): <code>password123</code></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>