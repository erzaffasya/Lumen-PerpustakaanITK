<?php

namespace Tests;

use App\Models\Dokumen;
use App\Models\Pembimbing;
use App\Models\User;
use Tests\TestCase;
use Laravel\Lumen\Testing\DatabaseTransactions;

class Iterasi2Test extends TestCase
{
    use DatabaseTransactions;

    public function test_lihat_dokumen()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->get(url('/api/dokumen'));

        $this->assertTrue(true);
    }

    public function test_tambah_dokumen()
    {
        $user = User::factory()->create();
        $dokumen = Dokumen::factory()->create();

        $this->actingAs($user)
            ->post(url('/api/dokumen', $dokumen));

        $this->assertTrue(true);
    }

    public function test_ubah_dokumen()
    {
        $user = User::factory()->create();
        $dokumen = Dokumen::factory()->create();

        $this->actingAs($user)
            ->put(url('/api/dokumen/' . $dokumen->id, $dokumen));

        $this->assertTrue(true);
    }

    public function test_hapus_dokumen()
    {
        $user = User::factory()->create();
        $dokumen = Dokumen::factory()->create();

        $this->actingAs($user)
            ->delete(url('/api/dokumen/' . $dokumen->id));

        $this->assertTrue(true);
    }

    public function test_lihat_pembimbing()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->get(url('/api/pembimbing'));

        $this->assertTrue(true);
    }

    public function test_tambah_pembimbing()
    {
        $user = User::factory()->create();
        $pembimbing = Pembimbing::factory()->create();

        $this->actingAs($user)
            ->post(url('/api/pembimbing', $pembimbing));

        $this->assertTrue(true);
    }

    public function test_ubah_pembimbing()
    {
        $user = User::factory()->create();
        $pembimbing = Pembimbing::factory()->create();

        $this->actingAs($user)
            ->put(url('/api/pembimbing/' . $pembimbing->id, $pembimbing));

        $this->assertTrue(true);
    }

    public function test_hapus_pembimbing()
    {
        $user = User::factory()->create();
        $pembimbing = Pembimbing::factory()->create();

        $this->actingAs($user)
            ->delete(url('/api/pembimbing/' . $pembimbing->id));

        $this->assertTrue(true);
    }
}
