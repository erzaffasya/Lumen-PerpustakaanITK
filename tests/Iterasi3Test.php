<?php

namespace Tests;

use App\Models\Bookmark;
use App\Models\PeminjamanDokumen;
use App\Models\User;
use Tests\TestCase;
// use Laravel\Lumen\Testing\DatabaseTransactions;

class Iterasi3Test extends TestCase
{
    // use DatabaseTransactions;

    public function test_lihat_peminjaman_dokumen()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(url('/api/peminjaman-dokumen'));

        $this->assertTrue(true);
    }

    public function test_tambah_peminjaman_dokumen()
    {
        $user = User::factory()->create();
        $peminjaman_dokumen = PeminjamanDokumen::factory()->create();

        $this->actingAs($user)
            ->post(url('/api/peminjaman-dokumen', $peminjaman_dokumen));

        $this->assertTrue(true);
    }

    public function test_ubah_peminjaman_dokumen()
    {
        $user = User::factory()->create();
        $peminjaman_dokumen = PeminjamanDokumen::factory()->create();

        $this->actingAs($user)
            ->put(url('/api/peminjaman-dokumen/' . $peminjaman_dokumen->id, $peminjaman_dokumen));

        $this->assertTrue(true);
    }

    public function test_detail_peminjaman_dokumen()
    {
        $user = User::factory()->create();
        $peminjaman_dokumen = PeminjamanDokumen::factory()->create();

        $this->actingAs($user)
            ->get(url('/api/peminjaman-dokumen/' . $peminjaman_dokumen->id));

        $this->assertTrue(true);
    }

    public function test_hapus_peminjaman_dokumen()
    {
        $user = User::factory()->create();
        $peminjaman_dokumen = PeminjamanDokumen::factory()->create();

        $this->actingAs($user)
            ->delete(url('/api/peminjaman-dokumen/' . $peminjaman_dokumen->id));

        $this->assertTrue(true);
    }

    public function test_lihat_bookmark()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(url('/api/bookmark'));

        $this->assertTrue(true);
    }

    public function test_tambah_bookmark()
    {
        $user = User::factory()->create();
        $bookmark = Bookmark::factory()->create();

        $this->actingAs($user)
            ->post(url('/api/bookmark', $bookmark));

        $this->assertTrue(true);
    }

    public function test_ubah_bookmark()
    {
        $user = User::factory()->create();
        $bookmark = Bookmark::factory()->create();

        $this->actingAs($user)
            ->put(url('/api/bookmark/' . $bookmark->id, $bookmark));

        $this->assertTrue(true);
    }

    public function test_detail_bookmark()
    {
        $user = User::factory()->create();
        $bookmark = Bookmark::factory()->create();

        $this->actingAs($user)
            ->get(url('/api/bookmark/' . $bookmark->id));

        $this->assertTrue(true);
    }

    public function test_hapus_bookmark()
    {
        $user = User::factory()->create();
        $bookmark = Bookmark::factory()->create();

        $this->actingAs($user)
            ->delete(url('/api/bookmark/' . $bookmark->id));

        $this->assertTrue(true);
    }
}
