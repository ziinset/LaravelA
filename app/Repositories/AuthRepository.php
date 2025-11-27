<?php

namespace App\Repositories;

use App\Models\admin;
use App\Models\guru;
use App\Models\siswa;

class AuthRepository
{
    public function findAdminByUsername(string $username): ?admin
    {
        return admin::where('username', $username)->first();
    }

    public function createAdmin(string $username, string $hashedPassword, string $role): admin
    {
        return admin::create([
            'username' => $username,
            'password' => $hashedPassword,
            'role' => $role,
        ]);
    }

    public function createGuru(int $adminId, string $nama, string $mapel): guru
    {
        return guru::create([
            'id' => $adminId,
            'nama' => $nama,
            'mapel' => $mapel,
        ]);
    }

    public function createSiswa(int $adminId, string $nama, $tb, $bb): siswa
    {
        return siswa::create([
            'id' => $adminId,
            'nama' => $nama,
            'tb' => $tb,
            'bb' => $bb,
        ]);
    }
}



