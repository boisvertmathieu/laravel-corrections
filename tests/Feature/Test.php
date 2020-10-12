<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Test extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRequeteBase()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
