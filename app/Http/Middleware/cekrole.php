<?php

namespace App\Http\Middleware;

use App\Models\guru;
use App\Models\kelas;
use App\Models\siswa;
use App\Models\walas;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class cekrole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika route adalah /home atau route bernama 'home', siapkan data home di middleware
        $routeName = optional($request->route())->getName();
        $isHome = $routeName === 'home' || $request->is('home');

        if ($isHome) {
            if (! session()->has('user_id')) {
                return redirect()->route('login');
            }

            // Siapkan data tambahan sesuai role untuk ditampilkan di view
            $extra = [];
            $role = session('role');
            $siswaColl = collect(); // Inisialisasi koleksi siswa kosong

            // Set tahun ajaran (bisa diambil dari database atau settingan)
            $tahunAjaran = '2024/2025'; // Default value, bisa diganti dengan nilai dari database
            $extra['tahun_ajaran'] = $tahunAjaran;

            if ($role === 'siswa') {
                $loggedInStudent = siswa::where('id', session('user_id'))->first();
                if ($loggedInStudent) {
                    $kelasRecord = kelas::with(['walas.guru'])
                        ->where('idsiswa', $loggedInStudent->idsiswa)
                        ->first();
                    if ($kelasRecord && $kelasRecord->walas && $kelasRecord->walas->guru) {
                        $extra['walas_nama'] = $kelasRecord->walas->guru->nama;
                        $extra['kelas_nama'] = $kelasRecord->walas->namakelas;
                        $extra['tahun_ajaran'] = $tahunAjaran;
                    }
                }
                $siswaColl = $this->getSiswaByKelas($kelasRecord->idwalas ?? null);
            } elseif ($role === 'guru') {
                $guruModel = guru::where('id', session('user_id'))->first();
                if ($guruModel) {
                    $walasModel = walas::where('idguru', $guruModel->idguru)->first();
                    if ($walasModel) {
                        $kelasSiswa = kelas::where('idwalas', $walasModel->idwalas)
                            ->with('walas')
                            ->get();

                        $extra['kelas_nama'] = $walasModel->namakelas;
                        $extra['jumlah_siswa'] = $kelasSiswa->count();
                        $extra['tahun_ajaran'] = $tahunAjaran;

                        $siswaColl = $this->getSiswaByKelas($walasModel->idwalas);
                    } else {
                        $siswaColl = siswa::all();
                    }
                } else {
                    $siswaColl = siswa::all();
                }
            } else {
                $siswaColl = siswa::all();
            }

            return response()->view('home', ['siswa' => $siswaColl, 'extra' => $extra]);
        }

        return $next($request);
    }

    /**
     * Helper untuk mendapatkan siswa berdasarkan idwalas
     */
    private function getSiswaByKelas($idwalas = null)
    {
        if (! $idwalas) {
            return collect();
        }

        return siswa::whereHas('kelas', function ($query) use ($idwalas) {
            $query->where('idwalas', $idwalas);
        })->get();
    }
}
