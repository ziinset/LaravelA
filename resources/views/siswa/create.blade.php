<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Tambah Data Siswa</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('siswa.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Siswa</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="tb" class="form-label">Tinggi Badan (cm)</label>
                                <input type="number" class="form-control" id="tb" name="tb" required>
                            </div>
                            <div class="mb-3">
                                <label for="bb" class="form-label">Berat Badan (kg)</label>
                                <input type="number" class="form-control" id="bb" name="bb" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('home') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>