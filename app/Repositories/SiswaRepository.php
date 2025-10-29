<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\Siswa;

class SiswaRepository
{
    public function create(array $data)
    {
        $admin = Admin::create([
            'username' => $data['nama'],
            'password' => bcrypt($data['nama']),
            'role'     => 'siswa',
        ]);

        $siswa = Siswa::create([
            'id'   => $admin->id,
            'nama' => $data['nama'],
            'tb'   => $data['tb'],
            'bb'   => $data['bb'],
        ]);

        return $siswa;
    }
}