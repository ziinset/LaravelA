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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        th,
        td {
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

        .search-container {
            background-color: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }

        .search-form {
            display: flex;
            gap: 15px;
            align-items: end;
            flex-wrap: wrap;
        }

        .search-group {
            flex: 1;
            min-width: 200px;
        }

        .search-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .search-group input,
        .search-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
        }

        .search-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-search, .btn-reset {
            padding: 8px 16px;
            border: 1px solid #ddd;
            cursor: pointer;
        }

        .btn-search {
            background-color: #28a745;
            color: white;
        }

        .btn-reset {
            background-color: #6c757d;
            color: white;
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

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #f5c6cb;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        {{-- Search Form --}}
        <div class="search-container">
            <h3 style="margin-top: 0; margin-bottom: 15px; color: #495057;">Pencarian Jadwal KBM</h3>
            <form id="searchForm" class="search-form">
                <div class="search-group">
                    <label for="nama_guru">Nama Guru:</label>
                    <input type="text" id="nama_guru" name="nama_guru" placeholder="Masukkan nama guru...">
                </div>
                <div class="search-group">
                    <label for="hari">Hari:</label>
                    <select id="hari" name="hari">
                        <option value="">Semua Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>
                <div class="search-buttons">
                    <button type="submit" class="btn-search">Cari</button>
                    <button type="button" id="resetBtn" class="btn-reset">Reset</button>
                </div>
            </form>
        </div>

        {{-- Schedule table --}}
        <div id="jadwal">
            {{-- Loading and Error Messages --}}
            <div id="loadingIndicator" class="loading" style="display: none;">
                Memuat...
            </div>
            <div id="errorMessage" class="error-message" style="display: none;"></div>
            @if (session('role') === 'guru' && session('guru_nama'))
                <div style="margin-bottom: 20px;">
                    <a href="{{ route('home') }}" class="btn">← Kembali ke Home</a>
                </div>
                <h2>Jadwal KBM Anda</h2>

                @if ($jadwals->count() > 0)
                    <p style="color: #666; margin-bottom: 20px;">
                        Berikut adalah jadwal mengajar Anda untuk semester ini.
                    </p>
                @endif
            @elseif (session('role') === 'siswa' && session('siswa_nama'))
                <div style="margin-bottom: 20px;">
                    <a href="{{ route('home') }}" class="btn">← Kembali ke Home</a>
                </div>
                <h2>Jadwal KBM Kelas Anda</h2>

                @if ($jadwals->count() > 0)
                    <p style="color: #666; margin-bottom: 20px;">
                        Berikut adalah jadwal pelajaran untuk kelas Anda.
                    </p>
                @endif
            @else
                <h2>Jadwal KBM</h2>
            @endif

            <table id="kbmTable">
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
                <tbody id="kbmTableBody">
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

    <script>
        $(document).ready(function() {
            const searchForm = document.getElementById('searchForm');
            const resetBtn = document.getElementById('resetBtn');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const errorMessage = document.getElementById('errorMessage');
            const kbmTableBody = document.getElementById('kbmTableBody');
            const kbmTable = document.getElementById('kbmTable');

            // Store original data for reset functionality
            const originalData = kbmTableBody.innerHTML;

            // Load data on page refresh/load
            loadInitialData();

            // Search form submission
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                performSearch();
            });

            // Reset button
            resetBtn.addEventListener('click', function() {
                resetSearch();
            });

            // Real-time search on input change (optional)
            const namaGuruInput = document.getElementById('nama_guru');
            const hariSelect = document.getElementById('hari');

            let searchTimeout;
            namaGuruInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 500); // Debounce search
            });

            hariSelect.addEventListener('change', function() {
                performSearch();
            });

            function loadInitialData() {
                // Show loading indicator on page load
                showLoading();

                // Simulate loading delay to show the loading indicator
                setTimeout(function() {
                    hideLoading();
                }, 1000);
            }

            function performSearch() {
                const namaGuru = namaGuruInput.value.trim();
                const hari = hariSelect.value;

                // Show loading indicator
                showLoading();

                // Prepare search parameters
                const searchParams = new URLSearchParams();
                if (namaGuru) searchParams.append('nama_guru', namaGuru);
                if (hari) searchParams.append('hari', hari);

                // Make AJAX request
                fetch(`{{ route('kbm.search') }}?${searchParams.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        updateTable(data.jadwals);
                        hideError();
                    } else {
                        showError(data.message || 'Terjadi kesalahan saat mencari data');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showError('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                });
            }

            function updateTable(jadwals) {
                if (jadwals.length === 0) {
                    kbmTableBody.innerHTML = `
                        <tr>
                            <td colspan="{{ session('role') === 'guru' ? 7 : 8 }}" class="no-results">
                                Tidak ada data yang ditemukan
                            </td>
                        </tr>
                    `;
                    return;
                }

                let html = '';
                jadwals.forEach((jadwal, index) => {
                    html += `
                        <tr>
                            <td class="text-center">${index + 1}</td>
                            <td>${jadwal.guru ? jadwal.guru.nama : 'N/A'}</td>
                            @if (session('role') !== 'guru')
                                <td>${jadwal.guru ? jadwal.guru.mapel : 'N/A'}</td>
                            @endif
                            <td>${jadwal.walas ? jadwal.walas.jenjang : 'N/A'}</td>
                            <td>${jadwal.walas ? jadwal.walas.namakelas : 'N/A'}</td>
                            <td>${jadwal.hari || 'N/A'}</td>
                            <td>${jadwal.mulai || 'N/A'}</td>
                            <td>${jadwal.selesai || 'N/A'}</td>
                        </tr>
                    `;
                });

                kbmTableBody.innerHTML = html;
            }

            function resetSearch() {
                namaGuruInput.value = '';
                hariSelect.value = '';
                kbmTableBody.innerHTML = originalData;
                hideError();
            }

            function showLoading() {
                loadingIndicator.style.display = 'block';
                kbmTable.style.display = 'none';
            }

            function hideLoading() {
                loadingIndicator.style.display = 'none';
                kbmTable.style.display = 'table';
            }

            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
            }

            function hideError() {
                errorMessage.style.display = 'none';
            }
        });
    </script>
</body>

</html>
