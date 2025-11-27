<?php

namespace App\Http\Controllers;

use App\Models\admin;
use Exception;
use Hash;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;

class adminController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function formLogin()
    {
        return view('login');
    }

    public function prosesLogin(LoginRequest $request)
    {
        $ok = $this->authService->login($request->validated());
        if (!$ok) {
            return redirect()->back()->with('error', 'Username atau password salah!');
        }
        return redirect()->route('home');
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

    public function prosesRegister(RegisterRequest $request)
    {
        try {
            $this->authService->register($request->validated());
            return redirect()->back()->with('success', 'Registrasi berhasil!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Registrasi gagal: ' . $e->getMessage());
        }
    }
}