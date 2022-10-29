<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kategori::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama_kategori' => $this->faker->words(2,true),
            'detail' => $this->faker->words(2,true),
            'berkas' =>  $this->faker->words(2,true),
            'isPembimbing' => $this->faker->randomDigit(),
        ];
    }
}
