<?php

namespace Database\Factories;

use App\Models\guru;
use App\Models\walas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\kbm>
 */
class kbmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $guruIds = guru::pluck('idguru')->toArray();
        $kelasIds = walas::pluck('idwalas')->toArray();
        return [
            'idguru' => $this->faker->randomElement($guruIds),
            'idwalas' => $this->faker->randomElement($kelasIds),
            'hari' => $this->faker->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']),
            'mulai' => $this->faker->randomElement(['07:00', '08:30', '10:00', '11:30', '13:00']),
            'selesai' => $this->faker->randomElement(['08:30', '10:00', '11:30', '13:00', '14:30']),
        ];
    }
}
