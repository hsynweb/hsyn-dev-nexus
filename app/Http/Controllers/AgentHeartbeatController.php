<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\ServerMetric;
use App\Models\ServerSite;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AgentHeartbeatController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        abort_unless($request->header('X-Agent-Token') === env('AGENT_SHARED_TOKEN', 'hsyn-agent-dev-token'), 403);

        $validated = $request->validate([
            'server.name' => ['required', 'string', 'max:255'],
            'server.provider' => ['nullable', 'string', 'max:255'],
            'server.region' => ['nullable', 'string', 'max:255'],
            'server.ip_address' => ['nullable', 'ip'],
            'server.os_name' => ['nullable', 'string', 'max:255'],
            'server.status' => ['required', 'string', 'max:50'],
            'server.cpu_load' => ['nullable', 'numeric'],
            'server.ram_usage' => ['nullable', 'numeric'],
            'server.disk_usage' => ['nullable', 'numeric'],
            'sites' => ['nullable', 'array'],
            'sites.*.domain' => ['required_with:sites', 'string', 'max:255'],
            'sites.*.framework' => ['nullable', 'string', 'max:255'],
            'sites.*.php_version' => ['nullable', 'string', 'max:50'],
            'sites.*.ssl_status' => ['nullable', 'string', 'max:50'],
            'sites.*.status' => ['nullable', 'string', 'max:50'],
            'sites.*.deploy_path' => ['nullable', 'string', 'max:255'],
            'metrics' => ['nullable', 'array'],
            'metrics.*.metric' => ['required_with:metrics', 'string', 'max:255'],
            'metrics.*.unit' => ['nullable', 'string', 'max:50'],
            'metrics.*.value' => ['required_with:metrics', 'numeric'],
        ]);

        $server = DB::transaction(function () use ($validated) {
            $serverData = $validated['server'];

            $server = Server::updateOrCreate(
                ['name' => $serverData['name']],
                [
                    'provider' => $serverData['provider'] ?? null,
                    'region' => $serverData['region'] ?? null,
                    'ip_address' => $serverData['ip_address'] ?? null,
                    'os_name' => $serverData['os_name'] ?? null,
                    'status' => $serverData['status'],
                    'agent_status' => 'online',
                    'cpu_load' => $serverData['cpu_load'] ?? 0,
                    'ram_usage' => $serverData['ram_usage'] ?? 0,
                    'disk_usage' => $serverData['disk_usage'] ?? 0,
                    'last_reported_at' => now(),
                ]
            );

            ServerSite::query()->where('server_id', $server->id)->delete();

            foreach ($validated['sites'] ?? [] as $site) {
                $server->sites()->create([
                    'domain' => $site['domain'],
                    'framework' => $site['framework'] ?? null,
                    'php_version' => $site['php_version'] ?? null,
                    'ssl_status' => $site['ssl_status'] ?? 'unknown',
                    'status' => $site['status'] ?? 'active',
                    'deploy_path' => $site['deploy_path'] ?? null,
                ]);
            }

            $metricRows = [
                ['metric' => 'cpu_load', 'unit' => '%', 'value' => $server->cpu_load],
                ['metric' => 'ram_usage', 'unit' => '%', 'value' => $server->ram_usage],
                ['metric' => 'disk_usage', 'unit' => '%', 'value' => $server->disk_usage],
            ];

            foreach ($validated['metrics'] ?? [] as $metric) {
                $metricRows[] = $metric;
            }

            foreach ($metricRows as $metric) {
                ServerMetric::create([
                    'server_id' => $server->id,
                    'metric' => $metric['metric'],
                    'unit' => $metric['unit'] ?? null,
                    'value' => $metric['value'],
                    'recorded_at' => now(),
                ]);
            }

            return $server->load('sites');
        });

        return response()->json([
            'ok' => true,
            'server_id' => $server->id,
            'sites' => $server->sites->count(),
        ]);
    }
}
