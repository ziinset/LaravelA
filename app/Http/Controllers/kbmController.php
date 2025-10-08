<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kbm;
use App\Models\guru;
use App\Models\kelas;
use App\Models\walas;
use App\Models\siswa;

class kbmController extends Controller

{
    public function index()
    {
        // Check user role and filter data accordingly
        if (session('role') === 'guru' && session('user_id')) {
            // Get the teacher's idguru from the database
            $guru = guru::where('id', session('user_id'))->first();

            if ($guru) {
                // Get teacher's KBM data only using idguru
                $jadwals = kbm::with(['guru', 'walas'])
                    ->where('idguru', $guru->idguru)
                    ->get();
            } else {
                $jadwals = collect();
            }
        } elseif (session('role') === 'siswa' && session('user_id')) {
            // Get student's class information
            $loggedInStudent = siswa::where('id', session('user_id'))->first();

            if ($loggedInStudent) {
                // Get student's class record
                $kelasRecord = kelas::with(['walas'])
                    ->where('idsiswa', $loggedInStudent->idsiswa)
                    ->first();

                if ($kelasRecord && $kelasRecord->walas) {
                    // Get KBM data for the student's class (jenjang and namakelas)
                    $jadwals = kbm::with(['guru', 'walas'])
                        ->whereHas('walas', function($query) use ($kelasRecord) {
                            $query->where('jenjang', $kelasRecord->walas->jenjang)
                                  ->where('namakelas', $kelasRecord->walas->namakelas);
                        })
                        ->get();
                } else {
                    $jadwals = collect();
                }
            } else {
                $jadwals = collect();
            }
        } else {
            // Show all KBM data for admin and other users
            $jadwals = kbm::with(['guru', 'walas'])->get();
        }

        return view('kbm.index', compact('jadwals'));
    }

    public function showGuru($idguru)
    {
        $guru = guru::with(['kbm.walas'])->findOrFail($idguru);
        return view('kbm.guru', compact('guru'));
    }

    public function showKelas($idwalas)
    {
        $walas = walas::with(['kbm.guru'])->findOrFail($idwalas);
        return view('kbm.kelas', compact('walas'));
    }
}
