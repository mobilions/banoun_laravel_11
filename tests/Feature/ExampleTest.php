<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testBasicTest(): void
    {
        $response = $this->get('/');

        // Home route requires authentication in this application and will
        // redirect guests to the login page (302). Assert that behavior.
        $response->assertStatus(302);
    }
}
