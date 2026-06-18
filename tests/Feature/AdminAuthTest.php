<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    private function makeToken(): string
    {
        return hash('sha256', config('app.admin_password') . config('app.key'));
    }

    public function test_login_with_correct_password_returns_token(): void
    {
        $response = $this->postJson('/api/admin/login', [
            'password' => config('app.admin_password'),
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'token']);
    }

    public function test_login_with_wrong_password_returns_401(): void
    {
        $response = $this->postJson('/api/admin/login', [
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Incorrect password']);
    }

    public function test_login_without_password_returns_422(): void
    {
        $response = $this->postJson('/api/admin/login', []);

        $response->assertStatus(422)
                 ->assertJsonStructure(['message', 'errors']);
    }

    public function test_check_auth_returns_true_with_valid_token(): void
    {
        $response = $this->getJson('/api/admin/check-auth', [
            'X-Admin-Token' => $this->makeToken(),
        ]);

        $response->assertStatus(200)
                 ->assertJson(['authenticated' => true]);
    }

    public function test_check_auth_returns_false_with_invalid_token(): void
    {
        $response = $this->getJson('/api/admin/check-auth', [
            'X-Admin-Token' => 'invalid-token',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['authenticated' => false]);
    }

    public function test_protected_routes_reject_request_without_token(): void
    {
        $this->getJson('/api/admin/projects')->assertStatus(401);
        $this->getJson('/api/admin/skills')->assertStatus(401);
        $this->getJson('/api/admin/messages')->assertStatus(401);
    }

    public function test_protected_routes_accept_request_with_valid_token(): void
    {
        $response = $this->withHeaders([
            'X-Admin-Token' => $this->makeToken(),
        ])->getJson('/api/admin/projects');

        $response->assertStatus(200);
    }
}
