<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Project;
use App\Models\Skill;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private function authHeaders(): array
    {
        return ['X-Admin-Token' => hash('sha256', config('app.admin_password') . config('app.key'))];
    }

    // ── Projects ──────────────────────────────────

    public function test_can_list_projects(): void
    {
        Project::factory()->count(3)->create();

        $this->withHeaders($this->authHeaders())
             ->getJson('/api/admin/projects')
             ->assertStatus(200)
             ->assertJsonCount(3);
    }

    public function test_can_create_project(): void
    {
        $this->withHeaders($this->authHeaders())
             ->postJson('/api/admin/projects', [
                 'title'       => 'Test Project',
                 'category'    => 'web',
                 'description' => 'A test project description',
             ])
             ->assertStatus(201)
             ->assertJsonFragment(['title' => 'Test Project']);
    }

    public function test_create_project_validates_required_fields(): void
    {
        $this->withHeaders($this->authHeaders())
             ->postJson('/api/admin/projects', [])
             ->assertStatus(422)
             ->assertJsonStructure(['message', 'errors']);
    }

    public function test_can_update_project(): void
    {
        $project = Project::factory()->create();

        $this->withHeaders($this->authHeaders())
             ->putJson("/api/admin/projects/{$project->id}", [
                 'title'       => 'Updated Title',
                 'category'    => 'mobile',
                 'description' => 'Updated description',
             ])
             ->assertStatus(200)
             ->assertJsonFragment(['title' => 'Updated Title']);
    }

    public function test_can_delete_project(): void
    {
        $project = Project::factory()->create();

        $this->withHeaders($this->authHeaders())
             ->deleteJson("/api/admin/projects/{$project->id}")
             ->assertStatus(200)
             ->assertJson(['message' => 'Deleted successfully']);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    // ── Skills ────────────────────────────────────

    public function test_can_create_skill(): void
    {
        $this->withHeaders($this->authHeaders())
             ->postJson('/api/admin/skills', [
                 'name'       => 'Laravel',
                 'percentage' => 95,
             ])
             ->assertStatus(201)
             ->assertJsonFragment(['name' => 'Laravel']);
    }

    public function test_skill_percentage_must_be_between_0_and_100(): void
    {
        $this->withHeaders($this->authHeaders())
             ->postJson('/api/admin/skills', [
                 'name'       => 'Laravel',
                 'percentage' => 150,
             ])
             ->assertStatus(422);
    }

    public function test_delete_nonexistent_resource_returns_404_json(): void
    {
        $response = $this->withHeaders($this->authHeaders())
             ->deleteJson('/api/admin/projects/99999');

        $response->assertStatus(404)
                 ->assertJsonStructure(['message']);

        $this->assertStringNotContainsString('<!DOCTYPE', $response->getContent());
    }
}
