<?php

namespace App\Http\Controllers;

use App\Models\siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function home()
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login');
        }

        // Ambil semua siswa untuk tabel daftar
        $siswa = siswa::all();

        // Siapkan data tambahan sesuai role untuk ditampilkan di view
        $extra = [];
        $role = session('role');

        if ($role === 'siswa') {
            // tampilkan nama guru walas dan kelasnya jika siswa terdaftar pada suatu kelas
            $loggedInStudent = siswa::where('id', session('user_id'))->first();
            if ($loggedInStudent) {
                // cari record datakelas berdasarkan idsiswa
                $kelasRecord = \App\Models\kelas::with(['walas.guru'])
                    ->where('idsiswa', $loggedInStudent->idsiswa)
                    ->first();
                if ($kelasRecord && $kelasRecord->walas && $kelasRecord->walas->guru) {
                    $extra['walas_nama'] = $kelasRecord->walas->guru->nama;
                    $extra['kelas_nama'] = $kelasRecord->walas->namakelas;
                }
            }
        } elseif ($role === 'guru') {
            // Jika guru juga menjadi walas, tampilkan nama kelas dan jumlah siswa
            $guru = \App\Models\guru::where('id', session('user_id'))->first();
            if ($guru) {
                $walas = \App\Models\walas::where('idguru', $guru->idguru)->first();
                if ($walas) {
                    $kelasSiswa = \App\Models\kelas::where('idwalas', $walas->idwalas)
                        ->with('walas')
                        ->get();
                    $extra['kelas_nama'] = $walas->namakelas;
                    $extra['jumlah_siswa'] = $kelasSiswa->count();
                }
            }
        }

        return view('home', compact('siswa', 'extra'));
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

        // Buat record admin terlebih dahulu dengan role 'siswa'
        $admin = \App\Models\admin::create([
            'username' => $request->nama . '_siswa', // username otomatis dari nama
            'password' => Hash::make('password123'), // password default
            'role' => 'siswa'
        ]);

        // Buat record siswa dengan id dari admin yang baru dibuat
        siswa::create([
            'id' => $admin->id,
            'nama' => $request->nama,
            'tb' => $request->tb,
            'bb' => $request->bb
        ]);

        return redirect()->route('home')->with('success', 'Siswa berhasil ditambahkan');
    }

    public function edit($id)
    {
        if (session('role') !== 'admin') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses untuk mengedit siswa');
        }

        $siswa = siswa::where('id', $id)->firstOrFail();
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        if (session('role') !== 'admin') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses untuk mengupdate siswa');
        }

        $siswa = siswa::where('id', $id)->firstOrFail();
        $siswa->update($request->only('nama', 'tb', 'bb'));
        return redirect()->route('home')->with('success', 'Data siswa berhasil diupdate');
    }

    public function destroy($id)
    {
        if (session('role') !== 'admin') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses untuk menghapus siswa');
        }

        $siswa = siswa::where('id', $id)->firstOrFail();
        $siswa->delete();
        return redirect()->route('home')->with('success', 'Siswa berhasil dihapus');
    }
}