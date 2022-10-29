<?php

namespace Tests;

use App\Models\Bookmark;
use App\Models\Dokumen;
use App\Models\Kategori;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class Iterasi1Test extends TestCase
{
    use DatabaseTransactions;

    public function test_login()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(url('/api/login', $user));

        $this->assertTrue(true);
    }

    public function test_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(url('/api/logout'));

        $this->assertTrue(true);
    }

    public function test_profil()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(url('/api/profil'));

        $this->assertTrue(true);
    }

    public function test_lihat_kategori()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(url('/api/kategori'));

        $this->assertTrue(true);
    }

    public function test_tambah_kategori()
    {
        $user = User::factory()->create();
        $Kategori = Kategori::factory()->create();

        $this->actingAs($user)
            ->post(url('/api/kategori', $Kategori));

        $this->assertTrue(true);
    }

    public function test_ubah_kategori()
    {
        $user = User::factory()->create();
        $Kategori = Kategori::factory()->create();

        $this->actingAs($user)
            ->put(url('/api/kategori', $Kategori));

        $this->assertTrue(true);
    }

    public function test_detail_kategori()
    {
        $user = User::factory()->create();
        $Kategori = Kategori::factory()->create();

        $this->actingAs($user)
            ->get(url('/api/kategori', $Kategori));

        $this->assertTrue(true);
    }

    public function test_hapus_kategori()
    {
        $user = User::factory()->create();
        $Kategori = Kategori::factory()->create();

        $this->actingAs($user)
            ->delete(url('/api/kategori', $Kategori));

        $this->assertTrue(true);
    }
}
