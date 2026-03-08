<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = [
        'name',
        'provider',
        'region',
        'ip_address',
        'agent_status',
        'status',
        'os_name',
        'cpu_load',
        'ram_usage',
        'disk_usage',
        'last_reported_at',
    ];

    protected function casts(): array
    {
        return [
            'cpu_load' => 'decimal:2',
            'ram_usage' => 'decimal:2',
            'disk_usage' => 'decimal:2',
            'last_reported_at' => 'datetime',
        ];
    }

    public function sites(): HasMany
    {
        return $this->hasMany(ServerSite::class);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(ServerMetric::class);
    }
}
