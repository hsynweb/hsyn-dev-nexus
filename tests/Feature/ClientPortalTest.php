<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_dashboard_renders(): void
    {
        $client = User::create([
            'name' => 'Client',
            'display_name' => 'Client',
            'email' => 'client@example.com',
            'role' => 'client',
            'password' => 'Password123!',
        ]);

        $response = $this->actingAs($client)->get(route('client.dashboard'));

        $response->assertOk();
        $response->assertSee('Client portal');
    }
}
