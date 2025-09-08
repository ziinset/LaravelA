<!DOCTYPE html>
<html>
<head>
<title>Home</title>
</head>
<body>

{{-- tampilkan sesuai role --}}
@if (session('role') === 'guru')
    <h2>Halo, guru {{ session('username') }}</h2>
    <a href="{{ route('logout') }}">Logout</a>

    <p>Nama : {{ session('guru_nama') }}</p>
    <p>Mapel : {{ session('mapel') }}</p>

    @if (!empty($extra['kelas_nama']))
    <p>Wali Kelas: {{ $extra['kelas_nama'] }}</p>
    <p>Jumlah Siswa: {{ $extra['jumlah_siswa'] }}</p>
    @endif

@elseif (session('role') === 'siswa')
    <h2>Halo, siswa {{ session('username') }}</h2>
    <a href="{{ route('logout') }}">Logout</a>

    <p>Nama : {{ session('siswa_nama') }}</p>
    <p>BB : {{ session('bb') }}</p>
    <p>TB : {{ session('tb') }}</p>

    @if (!empty($extra['walas_nama']))
    <p>Guru Walas : {{ $extra['walas_nama'] }}</p>
    <p>Kelas : {{ $extra['kelas_nama'] }}</p>
    @endif

@else
    <h2>Halo, {{ session('username') }}</h2>
    <a href="{{ route('logout') }}">Logout</a>
@endif

<hr>

<h2>Daftar Siswa</h2>

@if (session('role') === 'admin')
<p><a href="{{ route('siswa.create') }}">+ Tambah Siswa</a></p>
@endif

<table border="1" cellpadding="8">
<thead>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Tinggi Badan</th>
    <th>Berat Badan</th>

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
        <a href="{{ route('siswa.edit', $s->id) }}">Edit</a> |
        <a href="{{ route('siswa.delete', $s->id) }}" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
    </td>
    @endif
</tr>
@endforeach
</tbody>
</table>

</body>
</html>