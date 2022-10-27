<?php

namespace Tests;

use App\Models\Bookmark;
use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Iterasi1Test extends TestCase
{
    public function test_lihat_bookmark()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->get(url('/api/bookmark'));

         $this->assertTrue(true);
    }

  
}
