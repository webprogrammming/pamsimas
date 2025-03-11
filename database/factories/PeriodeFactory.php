<?php

namespace Database\Factories;

use App\Models\Bulan;
use App\Models\Tahun;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Periode>
 */
class PeriodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'periode'   => fake()->randomElement(['Januari-Februari', 'Maret-April', 'Mei-Juni']), // Menggunakan fake()
            'bulan_id'  => Bulan::factory(),
            'tahun_id'  => Tahun::factory(),
            'status'    => 'aktif',
        ];
    }
}