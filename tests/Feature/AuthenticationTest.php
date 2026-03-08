<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_is_redirected_to_client_dashboard(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Demo Client',
            'company_name' => 'Client Co',
            'email' => 'client@example.com',
            'phone' => '+90 555 000 00 00',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('dashboard.redirect'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'client@example.com',
            'role' => 'client',
        ]);
    }
}
