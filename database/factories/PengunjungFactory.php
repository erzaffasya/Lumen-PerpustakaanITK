<?php

namespace Database\Factories;

use App\Models\Dokumen;
use App\Models\PeminjamanDokumen;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengunjungFactory extends Factory
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
        $User = User::factory()->create();
        return [
            'user_id' => $User->id,
        ];
    }
}
