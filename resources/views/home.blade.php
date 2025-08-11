<!DOCTYPE html>
<html>
<head>
<title>Home</title>
</head>
<body>
<h2>Halo, Admin</h2>
<a href="{{ route('logout') }}">Logout</a>
<h2>Daftar Siswa</h2>
<a href="{{ route('siswa.create') }}">
<button>+ Tambah Siswa</button>
</a>
<table border="1" cellpadding="8">
<thead>
<tr>
<th>No</th>
<th>Nama</th>
<th>Tinggi Badan</th>
<th>Berat Badan</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
@foreach($siswa as $i => $s)
<tr>
<td>{{ $i + 1 }}</td>
<td>{{ $s->nama }}</td>
<td>{{ $s->tb }}</td>
<td>{{ $s->bb }}</td>
<td>

<a href="{{ route('siswa.edit', $s->id) }}">Edit</a> |
<a href="{{ route('siswa.delete', $s->id) }}" onclick="return confirm('Yakin
ingin menghapus?')">Hapus</a>
</td>
</tr>
@endforeach
</tbody>
</table>
</body>
</html>