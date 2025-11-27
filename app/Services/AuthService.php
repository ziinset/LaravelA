<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $repo;

    public function __construct(AuthRepository $repo)
    {
        $this->repo = $repo;
    }

    public function login(array $credentials): bool
    {
        $admin = $this->repo->findAdminByUsername($credentials['username']);
        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return false;
        }

        session([
            'user_id' => $admin->id,
            'username' => $admin->username,
            'role' => $admin->role,
            'admin_id' => $admin->id,
            'admin_username' => $admin->username,
        ]);

        if ($admin->role === 'guru' && $admin->guru) {
            session([
                'guru_nama' => $admin->guru->nama,
                'mapel' => $admin->guru->mapel,
            ]);
        }

        if ($admin->role === 'siswa' && $admin->siswa) {
            session([
                'siswa_nama' => $admin->siswa->nama,
                'tb' => $admin->siswa->tb,
                'bb' => $admin->siswa->bb,
            ]);
        }

        return true;
    }

    public function register(array $data): void
    {
        $admin = $this->repo->createAdmin(
            $data['username'],
            Hash::make($data['password']),
            $data['role']
        );

        if ($data['role'] === 'guru') {
            $this->repo->createGuru($admin->id, $data['nama_guru'], $data['mata_pelajaran']);
        } elseif ($data['role'] === 'siswa') {
            $this->repo->createSiswa($admin->id, $data['nama_siswa'], $data['tinggi_badan'], $data['berat_badan']);
        }
    }
}


