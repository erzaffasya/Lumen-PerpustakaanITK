<?php

namespace Database\Factories;

use App\Models\Dokumen;
use App\Models\PeminjamanDokumen;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeminjamanDokumenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PeminjamanDokumen::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $Dokumen = Dokumen::factory()->create();
        $User = User::factory()->create();
        return [
            'tgl_peminjaman' => $this->faker->dateTime(),
            'tgl_pengembalian' => $this->faker->dateTime(),
            'status' =>  $this->faker->boolean(),
            'dokumen_id' => $Dokumen->id,
            'user_id' => $User->id,
        ];
    }
}
