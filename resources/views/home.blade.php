<!DOCTYPE html>

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

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .loading {
            text-align: center;
            padding: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #000000;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    {{-- Tampilan sesuai role --}}
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
            {{-- Tampilkan tombol KBM: semua guru dapat tombol, khusus walas teks diperpanjang --}}
            @if (!empty($extra['kelas_nama']))
                <p>
                    <a href="{{ route('kbm.index') }}" class="btn">Lihat Jadwal KBM (Kegiatan Belajar Mengajar)</a>
                </p>
            @else
                <p>
                    <a href="{{ route('kbm.index') }}" class="btn">Lihat Jadwal KBM</a>
                </p>
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

            <p>
                <a href="{{ route('kbm.index') }}" class="btn">Lihat Jadwal KBM</a>
            </p>

            <p><a href="{{ route('logout') }}" class="btn">Logout</a></p>
        </div>
    @else
        {{-- Default (admin atau role lain) --}}
        <div class="profile">
            <h2>Halo, {{ session('username') }}</h2>
            <p><a href="{{ route('logout') }}" class="btn">Logout</a></p>
        </div>
    @endif


    {{-- Tabel hanya untuk Admin --}}
    @if (session('role') === 'admin')

        <h2>Daftar Siswa</h2>
        <p>
            <a href="{{ route('kbm.index') }}" class="btn">Lihat Jadwal KBM</a>
            <a href="{{ route('siswa.create') }}" class="btn">+ Tambah Siswa</a>
        </p>
        @if (count($siswa) > 0)
            <p><label>Cari Siswa: </label><input type="text" id="search" placeholder="Ketik nama..."></p>
            <div id="loading" class="loading" style="display: none;">Memuat...</div>
            <table id="tabel-siswa">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tinggi Badan (cm)</th>
                        <th>Berat Badan (kg)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $i => $s)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $s->nama }}</td>
                            <td>{{ $s->tb }}</td>
                            <td>{{ $s->bb }}</td>
                            <td>
                                <a href="{{ route('siswa.edit', $s->id) }}" class="btn">Edit</a>
                                <a href="{{ route('siswa.delete', $s->id) }}"
                                    onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-danger">Hapus</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <script>
                $(function() {
                    function renderTable(data) {
                        let rows = '';
                        if (!data || data.length === 0) {
                            rows = '<tr><td colspan="5">Tidak ada data ditemukan</td></tr>';
                        } else {
                            data.forEach((s, index) => {
                                rows += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${s.nama ?? ''}</td>
                                        <td>${s.tb ?? ''}</td>
                                        <td>${s.bb ?? ''}</td>
                                        <td>
                                            <a href="/siswa/${s.id}/edit" class="btn">Edit</a>
                                            <a href="/siswa/${s.id}/delete" onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-danger">Hapus</a>
                                        </td>
                                    </tr>
                                `;
                            });
                        }
                        $('#tabel-siswa tbody').html(rows);
                    }

                    function loadAll() {
                        $('#loading').show();
                        $('#tabel-siswa').hide();
                        $.ajax({
                            url: "{{ route('siswa.data') }}",
                            method: "GET",
                            success: function(response) {
                                $('#loading').hide();
                                $('#tabel-siswa').show();
                                renderTable(response);
                            },
                            error: function() {
                                $('#loading').hide();
                                $('#tabel-siswa').show();
                                console.error('Gagal memuat data siswa.');
                            }
                        });
                    }

                    function searchSiswa(keyword) {
                        $.ajax({
                            url: "{{ route('siswa.search') }}",
                            method: "GET",
                            data: { q: keyword },
                            success: function(response) {
                                renderTable(response);
                            },
                            error: function() {
                                console.error('Gagal mencari data siswa.');
                            }
                        });
                    }

                    // Load data on page refresh/load
                    loadAll();

                    $('#search').on('keyup', function() {
                        const keyword = $(this).val().trim();
                        if (keyword.length > 0) {
                            searchSiswa(keyword);
                        } else {
                            loadAll();
                        }
                    });
                });
            </script>
        @else
            <p>Tidak ada data siswa yang ditemukan.</p>
        @endif

        {{-- Tabel hanya untuk Guru Walas --}}
    @elseif (session('role') === 'guru' && !empty($extra['kelas_nama']))
        <h2>Daftar Siswa di Kelas {{ $extra['kelas_nama'] }}</h2>

        @if (count($siswa) > 0)

            <table id="tabel-siswa">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tinggi Badan (cm)</th>
                        <th>Berat Badan (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $i => $s)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $s->nama }}</td>
                            <td>{{ $s->tb }}</td>
                            <td>{{ $s->bb }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        @else
            <p>Belum ada data siswa untuk kelas ini.</p>
        @endif

    @endif

</body>

</html>
