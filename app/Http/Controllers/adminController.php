<?php

namespace App\Http\Controllers;

use App\Models\admin;
use Exception;
use Hash;
use Illuminate\Http\Request;

class adminController extends Controller
{
    public function formLogin()
    {
        return view('login');
    }

    public function prosesLogin(Request $request)
    {
        $admin = admin::where('username', $request->username)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            //simpan ke session dengan format yang konsisten
            session([
                'user_id' => $admin->id,
                'username' => $admin->username,
                'role' => $admin->role
            ]);

            if ($admin && Hash::check($request->password, $admin->password)) {
            session([
                'admin_id' => $admin->id,
                'admin_username' => $admin->username,
                'role' => $admin->role,
            ]);

            // kalau guru → simpan info guru
            if ($admin->role === 'guru' && $admin->guru) {
                session([
                    'guru_nama' => $admin->guru->nama,
                    'mapel' => $admin->guru->mapel,
                ]);
            }

            // kalau siswa → simpan info siswa
            if ($admin->role === 'siswa' && $admin->siswa) {
                session([
                    'siswa_nama' => $admin->siswa->nama,
                    'tb' => $admin->siswa->tb,
                    'bb' => $admin->siswa->bb,
                ]);
            }

            return redirect()->route('home');
        }
            return redirect()->route('home');
        } else {
            return redirect()->back()->with('error', 'Username atau password salah!');
        }
    }

    public function logout()
    {
        // Hapus semua kunci session yang mungkin diset saat login
        session()->forget([
            'user_id', 'admin_id',
            'username', 'admin_username',
            'role',
            // role-specific
            'guru_nama', 'mapel',
            'siswa_nama', 'tb', 'bb'
        ]);

        return redirect()->route('landing');
    }

    public function formRegister()
    {
        return view('register');
    }

    public function prosesRegister(Request $request)
    {
        try {
            // Base validation rules
            $rules = [
                'username' => 'required|string|max:50|unique:dataadmin,username',
                'password' => 'required|string|min:8',
                'role' => 'required|string|in:admin,guru,siswa',
            ];

            // Add role-specific validation rules
            if ($request->role === 'guru') {
                $rules['nama_guru'] = 'required|string|max:100';
                $rules['mata_pelajaran'] = 'required|string|max:100';
            } elseif ($request->role === 'siswa') {
                $rules['nama_siswa'] = 'required|string|max:100';
                $rules['tinggi_badan'] = 'required|numeric|min:1|max:300';
                $rules['berat_badan'] = 'required|numeric|min:1|max:500';
            }

            $request->validate($rules);

            // Create admin record
            $admin = admin::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // Create role-specific records
            if ($request->role === 'guru') {
                \App\Models\guru::create([
                    'id' => $admin->id,
                    'nama' => $request->nama_guru,
                    'mapel' => $request->mata_pelajaran,
                ]);
            } elseif ($request->role === 'siswa') {
                \App\Models\siswa::create([
                    'id' => $admin->id,
                    'nama' => $request->nama_siswa,
                    'tb' => $request->tinggi_badan,
                    'bb' => $request->berat_badan,
                ]);
            }

            return redirect()->back()->with('success', 'Registrasi berhasil!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Registrasi gagal: ' . $e->getMessage());
        }
    }
}