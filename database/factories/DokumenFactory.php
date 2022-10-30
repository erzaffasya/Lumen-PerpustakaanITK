<?php

namespace Database\Factories;

use App\Models\Dokumen;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DokumenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dokumen::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $Kategori = Kategori::factory()->create();
        $User = User::factory()->create();
        // $kategori = Kategori::all()->pluck('id');
        return [
            'judul' => $this->faker->words(2,true),
            'kategori_id' => $Kategori->id,
            'tahun_terbit' =>  $this->faker->year(),
            'nama_pengarang' => $this->faker->name(),
            'penerbit' => $this->faker->companySuffix(),
            'deskripsi' => $this->faker->paragraph(),
            'user_id' => $User->id,
            'cover' => $this->faker->fileExtension(),
            'lembar_pengesahan' => $this->faker->fileExtension(),
            'kata_pengantar' => $this->faker->fileExtension(),
            'ringkasan' => $this->faker->fileExtension(),
            'daftar_isi' => $this->faker->fileExtension(),
            'daftar_gambar' => $this->faker->fileExtension(),
            'daftar_tabel' => $this->faker->fileExtension(),
            'daftar_notasi' => $this->faker->fileExtension(),
            'abstract_en' => $this->faker->fileExtension(),
            'abstract_id' => $this->faker->fileExtension(),
            'bab1' => $this->faker->fileExtension(),
            'bab2' => $this->faker->fileExtension(),
            'bab3' => $this->faker->fileExtension(),
            'bab4' => $this->faker->fileExtension(),
            'kesimpulan' => $this->faker->fileExtension(),
            'daftar_pustaka' => $this->faker->fileExtension(),
            'lampiran' => $this->faker->fileExtension(),
            'paper' => $this->faker->fileExtension(),
            'lembar_persetujuan' => $this->faker->fileExtension(),
            'full_dokumen' => $this->faker->fileExtension(),
            'status' => $this->faker->randomElement(['Diproses', 'Diterima', 'Ditolak', 'Revisi']),
            // 'data_tambahan' => $this->faker->randomDigit(),
            'catatan' => $this->faker->paragraph(),
        ];
    }
}
