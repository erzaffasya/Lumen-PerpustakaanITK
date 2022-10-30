<?php

namespace Database\Factories;

use App\Models\Ruangan;
use App\Models\PeminjamanRuangan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanRuanganFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PeminjamanRuangan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $Ruangan = Ruangan::factory()->create();
        $User = User::factory()->create();
        return [
            'kode' =>  $this->faker->swiftBicNumber(),
            'user_id' => $User->id,
            'ruangan_id' => $Ruangan->id,
            'tanggal' => $this->faker->date(),
            'waktu_awal' => $this->faker->time(),
            'waktu_akhir' => $this->faker->time(),
            'keperluan' =>  $this->faker->paragraph(),
            'status' =>  $this->faker->randomElement(['Menunggu', 'Diterima', 'Ditolak']),
            'catatan' => $this->faker->paragraph(),
        ];
    }
}
