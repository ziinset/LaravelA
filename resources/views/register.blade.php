<!DOCTYPE html>
<html>
<head>
<title>Register</title>
</head>
<body>
<h2>Register</h2>
@if(session('success'))
<div class="message success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="message error">{{ session('error') }}</div>
@endif
@if($errors->any())
<div class="message error">
    @foreach($errors->all() as $error)
        {{ $error }}<br>
    @endforeach
</div>
@endif

<form method="POST" action="{{ route('register.post') }}">
    @csrf
    <div class="form-group">
        <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required>
    </div>

    <div class="form-group">
        <input type="password" name="password" placeholder="Password" required>
    </div>

    <div class="radio-group">
        <label><input type="radio" name="role" value="admin" onchange="showRoleFields(this.value)" {{ old('role') == 'admin' ? 'checked' : '' }} required> Admin</label>
        <label><input type="radio" name="role" value="guru" onchange="showRoleFields(this.value)" {{ old('role') == 'guru' ? 'checked' : '' }}> Guru</label>
        <label><input type="radio" name="role" value="siswa" onchange="showRoleFields(this.value)" {{ old('role') == 'siswa' ? 'checked' : '' }}> Siswa</label>
    </div>

    <!-- Dynamic fields for Guru -->
    <div id="guru-fields" class="dynamic-fields" style="display: none;">
        <h4>Data Guru</h4>
        <div class="form-group">
            <input type="text" name="nama_guru" placeholder="Nama Lengkap Guru" value="{{ old('nama_guru') }}">
        </div>
        <div class="form-group">
            <input type="text" name="mata_pelajaran" placeholder="Mata Pelajaran" value="{{ old('mata_pelajaran') }}">
        </div>
    </div>

    <!-- Dynamic fields for Siswa -->
    <div id="siswa-fields" class="dynamic-fields" style="display: none;">
        <h4>Data Siswa</h4>
        <div class="form-group">
            <input type="text" name="nama_siswa" placeholder="Nama Lengkap Siswa" value="{{ old('nama_siswa') }}">
        </div>
        <div class="form-group">
            <input type="number" name="tinggi_badan" placeholder="Tinggi Badan (cm)" value="{{ old('tinggi_badan') }}" min="1">
        </div>
        <div class="form-group">
            <input type="number" name="berat_badan" placeholder="Berat Badan (kg)" value="{{ old('berat_badan') }}" min="1">
        </div>
    </div>

    <button type="submit">Register</button>
</form>

<script>
function showRoleFields(role) {
    document.getElementById('guru-fields').style.display = 'none';
    document.getElementById('siswa-fields').style.display = 'none';

    if (role === 'guru') {
        document.getElementById('guru-fields').style.display = 'block';
        document.querySelector('input[name="nama_guru"]').required = true;
        document.querySelector('input[name="mata_pelajaran"]').required = true;
        document.querySelector('input[name="nama_siswa"]').required = false;
        document.querySelector('input[name="tinggi_badan"]').required = false;
        document.querySelector('input[name="berat_badan"]').required = false;
    } else if (role === 'siswa') {
        document.getElementById('siswa-fields').style.display = 'block';
        document.querySelector('input[name="nama_siswa"]').required = true;
        document.querySelector('input[name="tinggi_badan"]').required = true;
        document.querySelector('input[name="berat_badan"]').required = true;
        document.querySelector('input[name="nama_guru"]').required = false;
        document.querySelector('input[name="mata_pelajaran"]').required = false;
    } else {
        document.querySelector('input[name="nama_guru"]').required = false;
        document.querySelector('input[name="mata_pelajaran"]').required = false;
        document.querySelector('input[name="nama_siswa"]').required = false;
        document.querySelector('input[name="tinggi_badan"]').required = false;
        document.querySelector('input[name="berat_badan"]').required = false;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const selectedRole = document.querySelector('input[name="role"]:checked');
    if (selectedRole) {
        showRoleFields(selectedRole.value);
    }
});
</script>
</body>
</html>