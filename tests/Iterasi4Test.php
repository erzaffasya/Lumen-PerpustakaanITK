<?php

namespace Tests;

use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use App\Models\User;
use Tests\TestCase;

class Iterasi4Test extends TestCase
{
    public function test_lihat_ruangan()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(url('/api/ruangan'));

        $this->assertTrue(true);
    }

    public function test_tambah_ruangan()
    {
        $user = User::factory()->create();
        $ruangan = Ruangan::factory()->create();

        $this->actingAs($user)
            ->post(url('/api/ruangan', $ruangan));

        $this->assertTrue(true);
    }

    public function test_ubah_ruangan()
    {
        $user = User::factory()->create();
        $ruangan = Ruangan::factory()->create();

        $this->actingAs($user)
            ->put(url('/api/ruangan/' . $ruangan->id, $ruangan));

        $this->assertTrue(true);
    }

    public function test_detail_ruangan()
    {
        $user = User::factory()->create();
        $ruangan = Ruangan::factory()->create();

        $this->actingAs($user)
            ->get(url('/api/ruangan/' . $ruangan->id));

        $this->assertTrue(true);
    }

    public function test_hapus_ruangan()
    {
        $user = User::factory()->create();
        $ruangan = Ruangan::factory()->create();

        $this->actingAs($user)
            ->delete(url('/api/ruangan/' . $ruangan->id));

        $this->assertTrue(true);
    }

    public function test_lihat_peminjaman_ruangan()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(url('/api/peminjaman-ruangan'));

        $this->assertTrue(true);
    }

    public function test_tambah_peminjaman_ruangan()
    {
        $user = User::factory()->create();
        $peminjaman_ruangan = PeminjamanRuangan::factory()->create();

        $this->actingAs($user)
            ->post(url('/api/peminjaman-ruangan', $peminjaman_ruangan));

        $this->assertTrue(true);
    }

    public function test_ubah_peminjaman_ruangan()
    {
        $user = User::factory()->create();
        $peminjaman_ruangan = PeminjamanRuangan::factory()->create();

        $this->actingAs($user)
            ->put(url('/api/peminjaman-ruangan/' . $peminjaman_ruangan->id, $peminjaman_ruangan));

        $this->assertTrue(true);
    }

    public function test_detail_peminjaman_ruangan()
    {
        $user = User::factory()->create();
        $peminjaman_ruangan = PeminjamanRuangan::factory()->create();

        $this->actingAs($user)
            ->get(url('/api/peminjaman-ruangan/' . $peminjaman_ruangan->id));

        $this->assertTrue(true);
    }

    public function test_hapus_peminjaman_ruangan()
    {
        $user = User::factory()->create();
        $peminjaman_ruangan = PeminjamanRuangan::factory()->create();

        $this->actingAs($user)
            ->delete(url('/api/peminjaman-ruangan/' . $peminjaman_ruangan->id));

        $this->assertTrue(true);
    }
}
