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

	public function updateByAdminId(int $adminId, array $data)
	{
		// Update siswa record tied to the given admin id
		$siswa = Siswa::where('id', $adminId)->firstOrFail();
		$siswa->update([
			'nama' => $data['nama'] ?? $siswa->nama,
			'tb'   => $data['tb']   ?? $siswa->tb,
			'bb'   => $data['bb']   ?? $siswa->bb,
		]);

		// Optionally keep Admin username in sync with siswa name
		if (isset($data['nama'])) {
			$admin = Admin::findOrFail($adminId);
			$admin->username = $data['nama'];
			$admin->save();
		}

		return $siswa->fresh();
	}
}