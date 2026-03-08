<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_renders(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'display_name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => 'Password123!',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Control Center');
    }

    public function test_admin_can_access_dashboard_and_create_customer(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'display_name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => 'Password123!',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.customers.store'), [
            'name' => 'Client User',
            'company_name' => 'Client Co',
            'email' => 'new-client@example.com',
            'phone' => '+90 555 333 22 11',
            'password' => 'Secret123!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'new-client@example.com',
            'role' => 'client',
        ]);
    }

    public function test_admin_can_update_lead(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'display_name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => 'Password123!',
        ]);

        $lead = Lead::create([
            'name' => 'Lead',
            'email' => 'lead@example.com',
            'channel' => 'landing-contact',
            'status' => 'new',
            'score' => 'warm',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.leads.update', $lead), [
            'status' => 'qualified',
            'score' => 'hot',
            'owner_id' => $admin->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'status' => 'qualified',
            'score' => 'hot',
            'owner_id' => $admin->id,
        ]);
    }
}
