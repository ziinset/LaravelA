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

        // Siapkan data tambahan sesuai role untuk ditampilkan di view
        $extra = [];
        $role = session('role');
        $siswa = collect(); // Inisialisasi koleksi siswa kosong

        // Set tahun ajaran (bisa diambil dari database atau settingan)
        $tahunAjaran = '2024/2025'; // Default value, bisa diganti dengan nilai dari database
        $extra['tahun_ajaran'] = $tahunAjaran;

        if ($role === 'siswa') {
            // Tampilkan nama guru walas dan kelasnya jika siswa terdaftar pada suatu kelas
            $loggedInStudent = siswa::where('id', session('user_id'))->first();
            if ($loggedInStudent) {
                // Cari record datakelas berdasarkan idsiswa
                $kelasRecord = \App\Models\kelas::with(['walas.guru'])
                    ->where('idsiswa', $loggedInStudent->idsiswa)
                    ->first();
                if ($kelasRecord && $kelasRecord->walas && $kelasRecord->walas->guru) {
                    $extra['walas_nama'] = $kelasRecord->walas->guru->nama;
                    $extra['kelas_nama'] = $kelasRecord->walas->namakelas;
                    $extra['tahun_ajaran'] = $tahunAjaran;
                }
            }
            // Ambil data siswa yang satu kelas
            $siswa = $this->getSiswaByKelas($kelasRecord->idwalas ?? null);
        } 
        elseif ($role === 'guru') {
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
                    $extra['tahun_ajaran'] = $tahunAjaran;
                    
                    // Ambil data siswa yang satu kelas dengan walas ini
                    $siswa = $this->getSiswaByKelas($walas->idwalas);
                } else {
                    // Jika guru bukan walas, tampilkan semua siswa
                    $siswa = siswa::all();
                }
            } else {
                $siswa = siswa::all();
            }
        } 
        else {
            // Untuk admin atau role lain, tampilkan semua siswa
            $siswa = siswa::all();
        }

        return view('home', compact('siswa', 'extra'));
    }

    /**
     * Mendapatkan daftar siswa berdasarkan idwalas
     *
     * @param int|null $idwalas
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getSiswaByKelas($idwalas = null)
    {
        if (!$idwalas) {
            return collect();
        }

        return \App\Models\siswa::whereHas('kelas', function($query) use ($idwalas) {
            $query->where('idwalas', $idwalas);
        })->get();
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

        // Validasi input
        $request->validate([
            'username' => 'required|string|max:50|unique:dataadmin,username',
            'password' => 'required|string|min:6',
            'nama' => 'required|string|max:100',
            'tb' => 'required|numeric|min:1|max:300',
            'bb' => 'required|numeric|min:1|max:500'
        ]);

        // Buat record admin terlebih dahulu dengan role 'siswa'
        $admin = \App\Models\admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
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