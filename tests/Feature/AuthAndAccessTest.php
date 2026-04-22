<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
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
                'status',
                'message',
                'data' => [
                    'token',
                    'user' => ['id', 'name', 'email', 'status', 'roles', 'permissions'],
                ],
            ]);
    }

    public function test_authenticated_user_can_update_their_profile(): void
    {
        $this->seed(AccessControlSeeder::class);

        $user = User::where('email', 'admin@example.com')->firstOrFail();

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Updated Admin',
            'email' => 'admin@example.com',
        ]);

        $response->assertRedirect('/profile');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Admin',
        ]);
    }

    public function test_authenticated_user_can_change_their_password(): void
    {
        $this->seed(AccessControlSeeder::class);

        $user = User::where('email', 'admin@example.com')->firstOrFail();

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/profile');
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_guest_can_request_a_password_reset_link(): void
    {
        $this->seed(AccessControlSeeder::class);
        Notification::fake();

        $response = $this->post('/forgot-password', [
            'email' => 'admin@example.com',
        ]);

        $response->assertSessionHas('status');
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'admin@example.com',
        ]);
        Notification::assertSentTo(
            User::where('email', 'admin@example.com')->firstOrFail(),
            ResetPassword::class
        );
    }

    public function test_admin_can_export_users_report(): void
    {
        $this->seed(AccessControlSeeder::class);

        $user = User::where('email', 'admin@example.com')->firstOrFail();

        $response = $this->actingAs($user)->get('/reports/export/users');

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
