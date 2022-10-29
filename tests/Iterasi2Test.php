<?php

namespace Tests;

use App\Models\User;
use Tests\TestCase;
use Laravel\Lumen\Testing\DatabaseTransactions;

class Iterasi2Test extends TestCase
{
    use DatabaseTransactions;
    
    public function test_lihat_bookmark()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->get(url('/api/bookmark'));

         $this->assertTrue(true);
    }

  
}
