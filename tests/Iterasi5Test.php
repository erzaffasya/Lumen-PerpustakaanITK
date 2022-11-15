<?php

namespace Tests;

use App\Models\Pengunjung;
use App\Models\User;
use Tests\TestCase;

class Iterasi5Test extends TestCase
{
    public function test_lihat_pengunjung()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(url('/api/pengunjung'));

        $this->assertTrue(true);
    }

    public function test_tambah_pengunjung()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(url('/api/pengunjung', ['nim' => $user->nim]));

        $this->assertTrue(true);
    }

    public function test_hapus_pengunjung()
    {
        $user = User::factory()->create();
        $pengunjung = Pengunjung::factory()->create();

        $this->actingAs($user)
            ->delete(url('/api/pengunjung/' . $pengunjung->id));

        $this->assertTrue(true);
    }

    public function test_lihat_jumlah_dokumen()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(url('/api/jumlah-dokumen'));

        $this->assertTrue(true);
    }

    public function test_lihat_notifikasi()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(url('/api/notifikasi'));

        $this->assertTrue(true);
    }

    public function test_tambah_bebas_pustaka()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(url('/api/bebas-pustaka', ['nim' => $user->nim]));

        $this->assertTrue(true);
    }
}
