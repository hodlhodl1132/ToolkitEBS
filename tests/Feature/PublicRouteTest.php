<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRouteTest extends TestCase
{
    /**
     * Test homepage route response.
     *
     * @return void
     */
    public function test_homepage_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
