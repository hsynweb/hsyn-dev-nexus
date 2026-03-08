<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentHeartbeatTest extends TestCase
{
    use RefreshDatabase;

    public function test_agent_heartbeat_creates_server_and_sites(): void
    {
        $response = $this
            ->withHeader('X-Agent-Token', 'hsyn-agent-dev-token')
            ->postJson(route('api.agent.heartbeat'), [
                'server' => [
                    'name' => 'fra-core-01',
                    'provider' => 'Hetzner',
                    'region' => 'Falkenstein',
                    'ip_address' => '192.0.2.50',
                    'os_name' => 'Ubuntu 24.04',
                    'status' => 'active',
                    'cpu_load' => 40,
                    'ram_usage' => 55,
                    'disk_usage' => 60,
                ],
                'sites' => [
                    [
                        'domain' => 'demo.hsyn.dev',
                        'framework' => 'Laravel',
                        'php_version' => '8.4',
                        'ssl_status' => 'valid',
                        'status' => 'active',
                        'deploy_path' => '/var/www/demo',
                    ],
                ],
                'metrics' => [
                    [
                        'metric' => 'php_fpm_workers',
                        'unit' => 'count',
                        'value' => 8,
                    ],
                ],
            ]);

        $response->assertOk();
        $this->assertDatabaseHas('servers', ['name' => 'fra-core-01']);
        $this->assertDatabaseHas('server_sites', ['domain' => 'demo.hsyn.dev']);
        $this->assertDatabaseHas('server_metrics', ['metric' => 'php_fpm_workers']);
    }
}
