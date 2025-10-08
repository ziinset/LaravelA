<?php
namespace Database\Seeders;

use App\Models\admin;
use App\Models\guru;
use App\Models\kbm;
use App\Models\kelas;
use App\Models\konten;
use App\Models\siswa;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\walas;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        admin::factory()->dataadmin1()->create();
        admin::factory()->dataadmin2()->create();
        siswa::factory()->count(15)->create();
        guru::factory()->count(5)->create();


        // Seed konten data
        konten::factory()->count(5)->create();
        //Penambahan dari scenario kali ini mulai dari baris ini
        //membuat 5 data untuk tabel guru, dan disimpan di variabel objek guruList
        $guruList = guru::factory(5)->create();
        //membuat 25 data untuk tabel siswa, dan disimpan di variabel objek siswaList
        $siswaList = siswa::factory(25)->create();
        //mengambil 3 data secara random dari variabel objek guruList
        $guruRandom = $guruList->random(3);
        //3 guru random dijadikan walas
        foreach ($guruRandom as $guru) {
            walas::factory()->create([
                'idguru' => $guru->idguru
            ]);
        }
        //mengambil data semua walas
        $waliKelasIds = walas::pluck('idwalas')->toArray();
        //mengacak urutan siswa
        $shuffledSiswa = $siswaList->shuffle();
        //mendistribusikan siswa menjadi 3 kelompok sesuai jumlah wali kelas
        $kelompokSiswa = $shuffledSiswa->chunk(ceil($shuffledSiswa->count() /
            count($waliKelasIds)));
        //perulangan tiap wali kelas dan siswanya
        foreach ($waliKelasIds as $index => $idwalas) {
            if (isset($kelompokSiswa[$index])) {
                foreach ($kelompokSiswa[$index] as $siswa) {
                    kelas::create([
                        'idwalas' => $idwalas,
                        'idsiswa' => $siswa->idsiswa
                    ]);
                }
            }
        }
        // Buat data KBM yang lebih lengkap seperti di gambar
        $guruList = guru::all();
        $walasList = walas::all();

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $waktuMulaiList = ['07:00', '08:30', '10:00', '11:30', '13:00', '14:30'];
        $waktuSelesaiList = ['08:30', '10:00', '11:30', '13:00', '14:30', '16:00'];

        // Buat 25 data KBM seperti di gambar
        for ($i = 1; $i <= 25; $i++) {
            $guruRandom = $guruList->random();
            $walasRandom = $walasList->random();

            kbm::create([
                'idguru' => $guruRandom->idguru,
                'idwalas' => $walasRandom->idwalas,
                'hari' => $hariList[array_rand($hariList)],
                'mulai' => $waktuMulaiList[array_rand($waktuMulaiList)],
                'selesai' => $waktuSelesaiList[array_rand($waktuSelesaiList)],
            ]);
        }

        // Buat beberapa data KBM khusus untuk guru dengan ID tertentu (untuk testing)
        $guruTest = guru::first();
        if ($guruTest) {
            kbm::create([
                'idguru' => $guruTest->idguru,
                'idwalas' => $walasList->first()->idwalas,
                'hari' => 'Kamis',
                'mulai' => '13:00',
                'selesai' => '13:00',
            ]);
        }

    }
}
