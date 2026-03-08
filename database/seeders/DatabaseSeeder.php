<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Lead;
use App\Models\PaymentNotification;
use App\Models\Project;
use App\Models\Server;
use App\Models\ServerMetric;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@hsyn.dev'],
            [
                'name' => 'HSYN Admin',
                'display_name' => 'HSYN Admin',
                'company_name' => 'hsyn.dev',
                'phone' => '+90 555 000 00 00',
                'role' => 'admin',
                'password' => 'Admin123456!',
            ]
        );

        $client = User::query()->updateOrCreate(
            ['email' => 'client@hsyn.dev'],
            [
                'name' => 'Demo Client',
                'display_name' => 'Demo Client',
                'company_name' => 'Northline Hosting',
                'phone' => '+90 555 111 11 11',
                'role' => 'client',
                'password' => 'Client123456!',
            ]
        );

        Lead::query()->updateOrCreate(
            ['email' => 'lead@northline.test'],
            [
                'name' => 'Northline Lead',
                'company_name' => 'Northline',
                'phone' => '+90 555 222 22 22',
                'channel' => 'landing-contact',
                'status' => 'new',
                'score' => 'hot',
                'message' => 'Yeni yonetilen sunucu ve musteri paneli ihtiyaci.',
                'owner_id' => $admin->id,
            ]
        );

        $project = Project::query()->updateOrCreate(
            ['customer_id' => $client->id, 'name' => 'Musteri Paneli Revizyonu'],
            [
                'status' => 'active',
                'priority' => 'high',
                'starts_on' => now()->subDays(5)->toDateString(),
                'due_on' => now()->addDays(12)->toDateString(),
                'progress' => 64,
                'summary' => 'Portal, billing ve destek akislarinin ayni panelde birlestirilmesi.',
            ]
        );

        $service = Service::query()->updateOrCreate(
            ['customer_id' => $client->id, 'name' => 'Managed VPS Pro'],
            [
                'project_id' => $project->id,
                'category' => 'managed-hosting',
                'plan' => 'Business Managed',
                'status' => 'active',
                'billing_cycle' => 'monthly',
                'monthly_amount' => 9800,
                'renews_at' => now()->addMonth(),
                'notes' => 'Gecelik backup ve izleme dahil.',
            ]
        );

        $invoice = Invoice::query()->updateOrCreate(
            ['invoice_number' => 'INV-DEMO-202603'],
            [
                'customer_id' => $client->id,
                'service_id' => $service->id,
                'status' => 'sent',
                'amount' => 9800,
                'paid_amount' => 0,
                'issued_on' => now()->subDays(3)->toDateString(),
                'due_on' => now()->addDays(4)->toDateString(),
                'notes' => 'Mart 2026 hizmet bedeli.',
            ]
        );

        PaymentNotification::query()->updateOrCreate(
            ['invoice_id' => $invoice->id, 'reference_code' => 'DEMO-REF-001'],
            [
                'customer_id' => $client->id,
                'amount' => 4200,
                'status' => 'pending-review',
                'channel' => 'bank-transfer',
                'payload' => 'Dekont yollandi, kontrol bekleniyor.',
            ]
        );

        $ticket = Ticket::query()->updateOrCreate(
            ['customer_id' => $client->id, 'subject' => 'Mail teslim problemi'],
            [
                'service_id' => $service->id,
                'department' => 'support',
                'priority' => 'high',
                'status' => 'open',
                'assigned_to' => $admin->id,
            ]
        );

        TicketMessage::query()->updateOrCreate(
            ['ticket_id' => $ticket->id, 'author_id' => $client->id],
            [
                'visibility' => 'public',
                'body' => 'Son 24 saatte outbound mailler gecikmeli gidiyor.',
            ]
        );

        $server = Server::query()->updateOrCreate(
            ['name' => 'fra-core-01'],
            [
                'provider' => 'Hetzner',
                'region' => 'Falkenstein',
                'ip_address' => '192.0.2.10',
                'agent_status' => 'online',
                'status' => 'active',
                'os_name' => 'Ubuntu 24.04',
                'cpu_load' => 37.5,
                'ram_usage' => 61.2,
                'disk_usage' => 54.8,
                'last_reported_at' => now(),
            ]
        );

        $server->sites()->updateOrCreate(
            ['domain' => 'client-demo.hsyn.dev'],
            [
                'customer_id' => $client->id,
                'framework' => 'Laravel',
                'php_version' => '8.4',
                'ssl_status' => 'valid',
                'status' => 'active',
                'deploy_path' => '/var/www/client-demo',
            ]
        );

        foreach ([
            ['metric' => 'cpu_load', 'unit' => '%', 'value' => 37.5],
            ['metric' => 'ram_usage', 'unit' => '%', 'value' => 61.2],
            ['metric' => 'disk_usage', 'unit' => '%', 'value' => 54.8],
        ] as $metric) {
            ServerMetric::query()->updateOrCreate(
                ['server_id' => $server->id, 'metric' => $metric['metric']],
                ['unit' => $metric['unit'], 'value' => $metric['value'], 'recorded_at' => now()]
            );
        }
    }
}
