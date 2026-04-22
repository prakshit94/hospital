<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use App\Models\EmployeeHealthRecord;
use Database\Seeders\AccessControlSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AccessControlSeeder::class);
        $this->admin = User::where('email', 'admin@example.com')->firstOrFail();
    }

    public function test_api_user_toggle_status(): void
    {
        $targetUser = User::factory()->create(['status' => 'active']);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/users/{$targetUser->id}/toggle-status");

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'new_status' => 'inactive',
                ],
            ]);
        
        $this->assertEquals('inactive', $targetUser->fresh()->status);
    }

    public function test_api_user_bulk_delete(): void
    {
        $users = User::factory()->count(3)->create();
        $ids = $users->pluck('id')->toArray();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/users/bulk-action", [
                'action' => 'delete',
                'ids' => $ids,
            ]);

        $response->assertOk();
        foreach ($ids as $id) {
            $this->assertSoftDeleted('users', ['id' => $id]);
        }
    }

    public function test_api_company_crud(): void
    {
        // Store
        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/companies", [
                'name' => 'New Company API',
                'is_active' => true,
            ]);

        $response->assertCreated();
        $companyId = $response->json('data.id');

        // Update
        $this->actingAs($this->admin, 'sanctum')
            ->patchJson("/api/v1/companies/{$companyId}", [
                'name' => 'Updated Company API',
            ])
            ->assertOk();

        $this->assertDatabaseHas('companies', ['id' => $companyId, 'name' => 'Updated Company API']);

        // Delete
        $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/companies/{$companyId}")
            ->assertOk();

        $this->assertSoftDeleted('companies', ['id' => $companyId]);
    }

    public function test_api_health_record_bulk_delete(): void
    {
        $company = Company::factory()->create();
        $records = EmployeeHealthRecord::factory()->count(2)->create([
            'company_id' => $company->id,
            'company_name' => $company->name,
            'created_by' => $this->admin->id,
        ]);
        $ids = $records->pluck('id')->toArray();

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/health-records/bulk-action", [
                'action' => 'delete',
                'ids' => $ids,
            ]);

        $response->assertOk();
        foreach ($ids as $id) {
            $this->assertSoftDeleted('employee_health_records', ['id' => $id]);
        }
    }

    public function test_api_notifications_mark_as_read(): void
    {
        $this->admin->update(['notifications_read_at' => null]);

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/notifications/mark-as-read");

        $response->assertOk();
        $this->assertNotNull($this->admin->fresh()->notifications_read_at);
    }
}
