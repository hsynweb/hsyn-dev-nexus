<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_update_profile(): void
    {
        $user = User::create([
            'name' => 'Client',
            'display_name' => 'Client',
            'email' => 'client@example.com',
            'role' => 'client',
            'password' => 'Password123!',
        ]);

        $response = $this->actingAs($user)->patch(route('account.update'), [
            'name' => 'Updated Client',
            'display_name' => 'Updated Display',
            'company_name' => 'Updated Co',
            'phone' => '+90 555 000 00 00',
            'timezone' => 'Europe/Istanbul',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Client',
            'display_name' => 'Updated Display',
            'company_name' => 'Updated Co',
        ]);
    }
}
