<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // withoutVite() mocks Vite so tests pass in CI without npm build artifacts
        $response = $this->withoutVite()->get('/');

        $response->assertStatus(200);
    }
}
