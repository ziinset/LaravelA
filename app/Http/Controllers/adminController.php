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
            return redirect()->route('home');
        } else {
            return redirect()->back()->with('error', 'Username atau password salah!');
        }
    }

    public function logout()
    {
        //hapus session
        session()->forget(['user_id', 'username', 'role']);
        return redirect()->route('landing');
    }

    public function formRegister()
    {
        return view('register');
    }

    public function prosesRegister(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|max:50|unique:dataadmin,username',
                'password' => 'required|string|min:8',
                'role' => 'required|string|in:admin,guru,siswa',
            ]);
            admin::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
            return redirect()->back()->with('success', 'Registrasi berhasil!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Registrasi gagal: ' . $e->getMessage());
        }
    }
}