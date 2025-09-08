<?php
namespace Database\Seeders;

use App\Models\admin;
use App\Models\guru;
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
        //membuat 5 data untuk tabel guru, dan disimpan di variabel objek gurus
        $gurus = guru::factory(5)->create();
        //membuat 25 data untuk tabel siswa, dan disimpan di variabel objek siswas
        $siswas = siswa::factory(25)->create();
        //mengambil 3 data secara random dari variabel objek gurus
        $guruRandom = $gurus->random(3);
        //3 guru random dijadikan walas
        foreach ($guruRandom as $guru) {
            walas::factory()->create([
                'idguru' => $guru->idguru
            ]);
        }
        //mengambil data semua walas
        $waliKelasIds = walas::pluck('idwalas')->toArray();
        //mengacak urutan siswa
        $randomSiswas = $siswas->shuffle();
        //mendistribusikan siswa menjadi 3 kelompok sesuai jumlah wali kelas
        $chunks = $randomSiswas->chunk(ceil($randomSiswas->count() /
            count($waliKelasIds)));
        //perulangan tiap wali kelas dan siswanya
        foreach ($waliKelasIds as $index => $idwalas) {
            if (isset($chunks[$index])) {
                foreach ($chunks[$index] as $siswa) {
                    kelas::create([
                        'idwalas' => $idwalas,
                        'idsiswa' => $siswa->idsiswa
                    ]);
                }
            }
        }

    }
}
