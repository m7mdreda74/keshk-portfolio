<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_portfolio_data_endpoint_returns_200(): void
    {
        $response = $this->getJson('/api/portfolio-data');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'personal_info',
                     'skills',
                     'services',
                     'stats',
                     'projects',
                     'resume_items',
                     'testimonials',
                 ]);
    }

    public function test_contact_endpoint_validates_required_fields(): void
    {
        $response = $this->postJson('/api/contact', []);

        $response->assertStatus(422)
                 ->assertJsonStructure(['message', 'errors']);
    }

    public function test_contact_endpoint_returns_json_not_html_on_error(): void
    {
        $response = $this->postJson('/api/contact', []);

        $response->assertHeader('Content-Type', 'application/json');
        $this->assertStringNotContainsString('<!DOCTYPE', $response->getContent());
    }
}
