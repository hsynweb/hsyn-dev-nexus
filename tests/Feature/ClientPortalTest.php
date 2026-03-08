<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\TicketMessage;
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

    public function test_client_can_reply_to_own_ticket(): void
    {
        $client = User::create([
            'name' => 'Client',
            'display_name' => 'Client',
            'email' => 'client@example.com',
            'role' => 'client',
            'password' => 'Password123!',
        ]);

        $ticket = Ticket::create([
            'customer_id' => $client->id,
            'subject' => 'Destek',
            'department' => 'support',
            'priority' => 'normal',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($client)->post(route('client.tickets.reply', $ticket), [
            'body' => 'Yeni bir guncelleme paylasiyorum.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ticket_messages', [
            'ticket_id' => $ticket->id,
            'body' => 'Yeni bir guncelleme paylasiyorum.',
        ]);
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'open',
        ]);
    }
}
