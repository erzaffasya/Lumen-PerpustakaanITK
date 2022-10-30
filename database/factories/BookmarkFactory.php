<?php

namespace Database\Factories;

use App\Models\Bookmark;
use App\Models\Dokumen;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookmarkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bookmark::class;
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
            'dokumen_id' => $Dokumen->id,
            'user_id' => $User->id,
        ];
    }
}
