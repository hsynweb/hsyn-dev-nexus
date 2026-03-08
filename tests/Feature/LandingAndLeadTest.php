<?php

namespace Tests\Feature;

use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingAndLeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('HSYN Nexus');
    }

    public function test_public_lead_submission_creates_a_lead(): void
    {
        $response = $this->post(route('lead.store'), [
            'name' => 'Lead Owner',
            'company_name' => 'Acme',
            'email' => 'lead@example.com',
            'phone' => '+90 555 123 45 67',
            'message' => 'Yeni bir proje talebi.',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('leads', [
            'email' => 'lead@example.com',
            'channel' => 'landing-contact',
        ]);
    }
}
