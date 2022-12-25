<?php

namespace Routes;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;

class AppApiRouteTests extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private array $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function has_get_app_route()
    {
        $response = $this->actingAs($this->user)
            ->withHeaders($this->headers)
            ->get('/api/apps/get-app');

        $response->assertStatus(422);
    }

    /** @test */
    public function has_create_app_route()
    {
        $response = $this->actingAs($this->user)
            ->withHeaders($this->headers)
            ->post('/api/apps/create-app');

        $response->assertStatus(403);
    }

    /** @test */
    public function has_update_app_route()
    {
        $response = $this->actingAs($this->user)
            ->withHeaders($this->headers)
            ->post('/api/apps/update-app');

        $response->assertStatus(403);
    }
}
