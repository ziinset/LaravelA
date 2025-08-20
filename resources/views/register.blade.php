<!DOCTYPE html>
<html>
<head>
<title>Register</title>
</head>
<body>
<h2>Register</h2>
@if(session('success'))
<p style="color:green">{{ session('success') }}</p>
@endif
@if(session('error'))
<p style="color:red">{{ session('error') }}</p>
@endif
@if(session('errors'))
<p style="color: red">{{ session('errors') }} </p>
@endif
<form method="POST" action="{{ route('register.post') }}">
    @csrf
<input type="text" name="username" placeholder="Username" required><br>
<input type="password" name="password" placeholder="Password" required><br>
<label><input type="radio" name="role" value="admin"
oninput="showChoice(this.value)" required>Admin</label>
<label><input type="radio" name="role" value="guru"
oninput="showChoice(this.value)">Guru</label>
<label><input type="radio" name="role" value="siswa"
oninput="showChoice(this.value)">Siswa</label>
<button type="submit">Register</button>
</form>
</body>
</html>