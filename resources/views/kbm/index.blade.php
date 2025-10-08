<!DOCTYPE html>
<html>
<head>
    <title>Jadwal KBM (Kegiatan Belajar Mengajar)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e9ecef;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
        .header-actions {
            margin-bottom: 20px;
            text-align: right;
        }
        .home-section {
            text-align: center;
            padding: 40px 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .welcome-message {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        .kbm-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .kbm-button:hover {
            background-color: #218838;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Check if logged in user is a teacher or student --}}
        @if (session('role') === 'guru' && session('guru_nama'))
            {{-- Home page for teachers --}}
            <div class="home-section">
                <h1>Selamat Datang, {{ session('guru_nama') }}!</h1>
                <div class="welcome-message">
                    <p>Selamat datang di sistem KBM (Kegiatan Belajar Mengajar)</p>
                    <p>Anda dapat melihat jadwal mengajar Anda di bawah ini.</p>
                </div>
                <a href="#jadwal" class="kbm-button">Lihat KBM</a>
            </div>
        @elseif (session('role') === 'siswa' && session('siswa_nama'))
            {{-- Home page for students --}}
            <div class="home-section">
                <h1>Selamat Datang, {{ session('siswa_nama') }}!</h1>
                <div class="welcome-message">
                    <p>Selamat datang di sistem KBM (Kegiatan Belajar Mengajar)</p>
                    <p>Anda dapat melihat jadwal pelajaran kelas Anda di bawah ini.</p>
                </div>
                <a href="#jadwal" class="kbm-button">Lihat KBM</a>
            </div>
        @else
            {{-- Regular KBM page for admin and other users --}}
            <h1>Jadwal KBM (Kegiatan Belajar Mengajar)</h1>

            <div class="header-actions">
                <a href="{{ route('home') }}" class="btn">← Kembali ke Home</a>
                @if (session('role') === 'admin')
                    <a href="#" class="btn">+ Tambah Jadwal</a>
                @endif
            </div>
        @endif

        {{-- Schedule table --}}
        <div id="jadwal">
            @if (session('role') === 'guru' && session('guru_nama'))
                <div style="margin-bottom: 20px;">
                    <a href="{{ route('home') }}" class="btn">← Kembali ke Home</a>
                </div>
                <h2>Jadwal KBM Anda</h2>

                @if($jadwals->count() > 0)
                    <p style="color: #666; margin-bottom: 20px;">
                        Berikut adalah jadwal mengajar Anda untuk semester ini.
                    </p>
                @endif
            @elseif (session('role') === 'siswa' && session('siswa_nama'))
                <div style="margin-bottom: 20px;">
                    <a href="{{ route('home') }}" class="btn">← Kembali ke Home</a>
                </div>
                <h2>Jadwal KBM Kelas Anda</h2>

                @if($jadwals->count() > 0)
                    <p style="color: #666; margin-bottom: 20px;">
                        Berikut adalah jadwal pelajaran untuk kelas Anda.
                    </p>
                @endif
            @else
                <h2>Jadwal KBM</h2>
            @endif

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Guru</th>
                        @if (session('role') !== 'guru')
                            <th>Mata Pelajaran</th>
                        @endif
                        <th>Jenjang</th>
                        <th>Kelas</th>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwals as $i => $jadwal)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $jadwal->guru->nama ?? 'N/A' }}</td>
                        @if (session('role') !== 'guru')
                            <td>{{ $jadwal->guru->mapel ?? 'N/A' }}</td>
                        @endif
                        <td>{{ $jadwal->walas->jenjang ?? 'N/A' }}</td>
                        <td>{{ $jadwal->walas->namakelas ?? 'N/A' }}</td>
                        <td>{{ $jadwal->hari ?? 'N/A' }}</td>
                        <td>{{ $jadwal->mulai ?? 'N/A' }}</td>
                        <td>{{ $jadwal->selesai ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ session('role') === 'guru' ? 7 : 8 }}" class="text-center text-muted">
                            @if (session('role') === 'guru')
                                Belum ada jadwal mengajar yang ditugaskan untuk Anda
                            @elseif (session('role') === 'siswa')
                                Belum ada jadwal pelajaran untuk kelas Anda
                            @else
                                Belum ada jadwal pelajaran
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
