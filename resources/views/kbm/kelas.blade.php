<!DOCTYPE html>
<html>
<head>
    <title>Jadwal KBM - {{ $walas->kelas }}</title>
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
            border-left: 4px solid #28a745;
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
        <h1>Jadwal KBM Kelas</h1>

        <div style="text-align: right; margin-bottom: 20px;">
            <a href="{{ route('kbm.index') }}" class="btn">← Kembali ke Daftar KBM</a>
            <a href="{{ route('home') }}" class="btn">← Kembali ke Home</a>
        </div>

        <div class="profile">
            <h2>Informasi Kelas</h2>
            <p><strong>Kelas:</strong> {{ $walas->kelas }}</p>
            <p><strong>Tahun Ajaran:</strong> {{ $walas->tahun_ajaran ?? '2024/2025' }}</p>
            <p><strong>Wali Kelas:</strong> {{ $walas->nama ?? 'N/A' }}</p>
            <p><strong>Jumlah Siswa:</strong> {{ $walas->jumlah_siswa ?? 'N/A' }}</p>
        </div>

        @if($walas->kbm && count($walas->kbm) > 0)
            <h2>Jadwal Pelajaran</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Guru</th>
                        <th>Mata Pelajaran</th>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($walas->kbm as $index => $kbm)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $kbm->guru->nama ?? 'N/A' }}</td>
                            <td>{{ $kbm->guru->mapel ?? 'N/A' }}</td>
                            <td>{{ $kbm->hari ?? 'N/A' }}</td>
                            <td>{{ $kbm->mulai ?? 'N/A' }}</td>
                            <td>{{ $kbm->selesai ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                <h3>Belum ada jadwal pelajaran untuk kelas ini.</h3>
                <p>Kelas {{ $walas->kelas }} belum memiliki jadwal pelajaran yang tersedia.</p>
            </div>
        @endif
    </div>
</body>
</html>
