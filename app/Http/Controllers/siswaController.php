<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiswaRequest;
use App\Models\siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function home()
    {
        // Logic for preparing home is now handled in middleware `cekrole`.
        // This fallback will redirect to /home so middleware can render the view.
        return redirect()->route('home');
    }

    protected $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function getData()
    {
        $siswa = Siswa::all();

        return response()->json($siswa);
    }

    /**
     * Mendapatkan daftar siswa berdasarkan idwalas
     *
     * @param  int|null  $idwalas
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getSiswaByKelas($idwalas = null)
    {
        if (!$idwalas) {
            return collect();
        }

        return siswa::whereHas('kelas', function ($query) use ($idwalas) {
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

    public function store(StoreSiswaRequest $request)
    {
        $this->service->createSiswa($request->validated());
        return redirect()->route('home')->with('success', 'Data siswa berhasil
        ditambahkan!');
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

    public function search(Request $request)
    {
        $keyword = strtolower($request->input('q'));
        $siswa = Siswa::whereRaw('LOWER(nama) LIKE ?', ["%{$keyword}%"])
            ->get();

        return response()->json($siswa);
    }
}
