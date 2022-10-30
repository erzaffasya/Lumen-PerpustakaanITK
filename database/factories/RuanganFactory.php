<?php

namespace Database\Factories;

use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Factories\Factory;

class RuanganFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ruangan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama_ruangan' => $this->faker->words(1,true),
            'deskripsi' => $this->faker->paragraph(),
            'jumlah_orang' =>  $this->faker->randomDigit(),
            'lokasi' => $this->faker->streetAddress(),
        ];
    }
}
