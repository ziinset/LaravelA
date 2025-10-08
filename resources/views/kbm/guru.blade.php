<!DOCTYPE html>
<html>
<head>
    <title>Jadwal KBM - {{ $guru->nama }}</title>
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
        .profile {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #007bff;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Jadwal KBM Guru</h1>

        <div style="text-align: right; margin-bottom: 20px;">
            <a href="{{ route('kbm.index') }}" class="btn">← Kembali ke Daftar KBM</a>
            <a href="{{ route('home') }}" class="btn">← Kembali ke Home</a>
        </div>

        <div class="profile">
            <h2>Informasi Guru</h2>
            <p><strong>Nama:</strong> {{ $guru->nama }}</p>
            <p><strong>Mata Pelajaran:</strong> {{ $guru->mapel }}</p>
            <p><strong>Username:</strong> {{ $guru->username }}</p>
        </div>

        @if($guru->kbm && count($guru->kbm) > 0)
            <h2>Jadwal Mengajar</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Wali Kelas</th>
                        <th>Kelas</th>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guru->kbm as $index => $kbm)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $kbm->walas->nama ?? 'N/A' }}</td>
                            <td>{{ $kbm->walas->kelas ?? 'N/A' }}</td>
                            <td>{{ $kbm->hari ?? 'N/A' }}</td>
                            <td>{{ $kbm->mulai ?? 'N/A' }}</td>
                            <td>{{ $kbm->selesai ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                <h3>Belum ada jadwal mengajar untuk guru ini.</h3>
                <p>Guru {{ $guru->nama }} belum memiliki jadwal mengajar yang tersedia.</p>
            </div>
        @endif
    </div>
</body>
</html>
