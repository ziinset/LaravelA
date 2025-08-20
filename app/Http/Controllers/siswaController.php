<?php

namespace App\Http\Controllers;

use App\Models\siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function home()
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login');
        }

        $siswa = siswa::all();
        return view('home', compact('siswa'));
    }

    public function create()
    {
        if (session('role') !== 'admin') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses untuk menambah siswa');
        }
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        if (session('role') !== 'admin') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses untuk menambah siswa');
        }
        
        siswa::create($request->only('nama', 'tb', 'bb'));
        return redirect()->route('home')->with('success', 'Siswa berhasil ditambahkan');
    }

    public function edit($id)
    {
        if (session('role') !== 'admin') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses untuk mengedit siswa');
        }
        
        $siswa = siswa::findOrFail($id);
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        if (session('role') !== 'admin') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses untuk mengupdate siswa');
        }
        
        $siswa = siswa::findOrFail($id);
        $siswa->update($request->only('nama', 'tb', 'bb'));
        return redirect()->route('home')->with('success', 'Data siswa berhasil diupdate');
    }

    public function destroy($id)
    {
        if (session('role') !== 'admin') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses untuk menghapus siswa');
        }
        
        $siswa = siswa::findOrFail($id);
        $siswa->delete();
        return redirect()->route('home')->with('success', 'Siswa berhasil dihapus');
    }
}