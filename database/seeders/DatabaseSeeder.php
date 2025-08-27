<?php

namespace Database\Seeders;

use App\Models\admin;
use App\Models\guru;
use App\Models\konten;
use App\Models\siswa;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        konten::factory()->count(3)->create();
    }
}
