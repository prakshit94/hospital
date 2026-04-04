<?php

namespace Tests\Feature;

use Database\Seeders\AccessControlSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_admin_can_log_into_the_web_dashboard(): void
    {
        $this->seed(AccessControlSeeder::class);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_api_login_returns_a_token_for_the_seeded_admin(): void
    {
        $this->seed(AccessControlSeeder::class);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
            'device_name' => 'phpunit',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'message',
                'token',
                'user' => ['id', 'name', 'email', 'status', 'roles', 'permissions'],
            ]);
    }
}
