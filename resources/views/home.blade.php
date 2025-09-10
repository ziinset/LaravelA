<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .profile {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

{{-- Tampilkan sesuai role --}}
@if (session('role') === 'guru')
    <div class="profile">
        <h2>Halo, {{ session('guru_nama') }}</h2>
        <p><strong>Username:</strong> {{ session('username') }}</p>
        <p><strong>Mata Pelajaran:</strong> {{ session('mapel') }}</p>

        @if (!empty($extra['kelas_nama']))
            <h3>Informasi Wali Kelas</h3>
            <p><strong>Kelas:</strong> {{ $extra['kelas_nama'] }}</p>
            <p><strong>Tahun Ajaran:</strong> {{ $extra['tahun_ajaran'] ?? '2024/2025' }}</p>
            <p><strong>Jumlah Siswa:</strong> {{ $extra['jumlah_siswa'] }}</p>
        @endif
        <p><a href="{{ route('logout') }}" class="btn">Logout</a></p>
    </div>

@elseif (session('role') === 'siswa')
    <div class="profile">
        <h2>Halo, {{ session('siswa_nama') }}</h2>
        <p><strong>Username:</strong> {{ session('username') }}</p>
        <p><strong>Tinggi Badan:</strong> {{ session('tb') }} cm</p>
        <p><strong>Berat Badan:</strong> {{ session('bb') }} kg</p>

        @if (!empty($extra['walas_nama']))
            <h3>Informasi Kelas</h3>
            <p><strong>Wali Kelas:</strong> {{ $extra['walas_nama'] }}</p>
            <p><strong>Kelas:</strong> {{ $extra['kelas_nama'] }}</p>
            <p><strong>Tahun Ajaran:</strong> {{ $extra['tahun_ajaran'] ?? '2024/2025' }}</p>
        @endif
        <p><a href="{{ route('logout') }}" class="btn">Logout</a></p>
    </div>

@else
    <div class="profile">
        <h2>Halo, {{ session('username') }}</h2>
        <p><a href="{{ route('logout') }}" class="btn">Logout</a></p>
    </div>
@endif

@if (session('role') !== 'siswa')
    <hr>

    <h2>Daftar Siswa</h2>

    @if (session('role') === 'admin')
        <p><a href="{{ route('siswa.create') }}" class="btn">+ Tambah Siswa</a></p>
    @endif

    @if(count($siswa) > 0)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tinggi Badan (cm)</th>
                    <th>Berat Badan (kg)</th>
                    @if (session('role') === 'admin')
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($siswa as $i => $s)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $s->nama }}</td>
                        <td>{{ $s->tb }}</td>
                        <td>{{ $s->bb }}</td>
                        @if (session('role') === 'admin')
                            <td>
                                <a href="{{ route('siswa.edit', $s->id) }}" class="btn">Edit</a>
                                <a href="{{ route('siswa.delete', $s->id) }}"
                                   onclick="return confirm('Yakin ingin menghapus?')"
                                   class="btn btn-danger">Hapus</a>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada data siswa yang ditemukan.</p>
    @endif
@endif

</body>
</html>