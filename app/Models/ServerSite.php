<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ServerSite extends Model
{
    protected $fillable = [
        'server_id',
        'customer_id',
        'domain',
        'framework',
        'php_version',
        'ssl_status',
        'status',
        'deploy_path',
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
