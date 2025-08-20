<!DOCTYPE html>
<html>
<head>
<title>Home</title>
</head>
<body>
<h2>Halo, {{ session('username') }}</h2>
<a href="{{ route('logout') }}">Logout</a>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

<h2>Daftar Siswa</h2>
@if (session('role') === 'admin')
    <a href="{{ route('siswa.create') }}">
    <button>+ Tambah Siswa</button>
    </a>
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